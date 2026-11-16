<?php
/**
 * Controlador de Torneos
 * @version 4.0 - Toasts, validaciones con Regex, sin redirect loop
 */
class TorneosController extends Controller {
    
    public function index(): void {
        try {
            $this->requireLogin();
            $torneos = (new Torneo())->todos();
            $this->view('torneos/index', compact('torneos'));
        } catch (Exception $e) {
            error_log("[TorneosController::index] " . $e->getMessage());
            $_SESSION['error'] = "Error al cargar torneos";
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
    
    public function ver(): void {
        try {
            $this->requireLogin();
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de torneo inválido");
            $model = new Torneo();
            $torneo = $model->obtener($id);
            if (!$torneo) throw new Exception("Torneo no encontrado");
            $llaves = $model->getLlaves($id);
            $this->view('torneos/ver', compact('torneo', 'llaves'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=torneos&a=index');
        }
    }
    
    public function llaves(): void {
        try {
            $this->requireLogin();
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de torneo inválido");
            $model = new Torneo();
            $torneo = $model->obtener($id);
            if (!$torneo) throw new Exception("Torneo no encontrado");
            $llaves = $model->getLlaves($id);
            $this->view('torneos/llaves', compact('torneo', 'llaves'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=torneos&a=index');
        }
    }
    
    public function form(): void {
        try {
            $this->requireRol(['Administrador', 'Entrenador']);
            $id = (int)($_GET['id'] ?? 0);
            $torneo = [];
            $errores = [];
            $stats = ['total' => 0, 'con_resultado' => 0];
            $model = new Torneo();
            
            if ($id > 0) {
                $torneo = $model->obtener($id);
                if (!$torneo) throw new Exception("Torneo no encontrado");
                $stats = $model->contarCombates($id);
            }
            
            $this->view('torneos/form', compact('torneo', 'errores', 'stats'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=torneos&a=index');
        }
    }
    
    public function guardar(): void {
        $this->requireRol(['Administrador', 'Entrenador']);
        $id = (int)($_POST['id'] ?? 0);
        $errores = [];
        
        try {
            // ==================== VALIDACIONES CON REGEX ====================
            
            // --- Nombre ---
            $nombre = trim($_POST['nombre_torneo'] ?? '');
            $regexNombre = '/^[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.,&\(\)]{3,100}$/u';
            if ($nombre === '') {
                $errores['nombre_torneo'] = 'El nombre del torneo es obligatorio';
            } elseif (!preg_match($regexNombre, $nombre)) {
                $errores['nombre_torneo'] = 'Entre 3 y 100 caracteres. Solo letras, números y símbolos básicos';
            } else {
                $m = new Torneo();
                if ($m->existeNombre($nombre, $id > 0 ? $id : null)) {
                    $errores['nombre_torneo'] = 'Ya existe un torneo con ese nombre';
                }
            }
            
            // --- Fecha inicio ---
            $fechaInicio = trim($_POST['fecha_inicio'] ?? '');
            if ($fechaInicio === '') {
                $errores['fecha_inicio'] = 'La fecha de inicio es obligatoria';
            } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaInicio)) {
                $errores['fecha_inicio'] = 'Formato de fecha inválido';
            }
            
            // --- Fecha fin (opcional) ---
            $fechaFin = trim($_POST['fecha_fin'] ?? '');
            if ($fechaFin !== '') {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaFin)) {
                    $errores['fecha_fin'] = 'Formato de fecha inválido';
                } elseif ($fechaInicio !== '' && $fechaFin < $fechaInicio) {
                    $errores['fecha_fin'] = 'La fecha de fin no puede ser anterior a la fecha de inicio';
                }
            }
            
            // --- Lugar ---
            $lugar = trim($_POST['lugar'] ?? '');
            if ($lugar !== '' && !preg_match('/^[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.,#°ºª]{3,255}$/u', $lugar)) {
                $errores['lugar'] = 'Solo letras, números, espacios y símbolos básicos';
            }
            
            // ==================== RE-RENDERIZAR SI HAY ERRORES ====================
            if (!empty($errores)) {
                $_SESSION['error_toast'] = 'Error: ' . implode(' | ', array_values($errores));
                $this->reRenderForm($id, $errores);
                return;
            }
            
            // ==================== GUARDAR ====================
            $datos = [
                'nombre_torneo' => $nombre,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin ?: null,
                'lugar' => $lugar ?: null,
            ];
            
            $model = new Torneo();
            if ($id > 0) {
                $model->actualizar($id, $datos);
                $mensaje = "Torneo actualizado correctamente";
            } else {
                $id = $model->crear($datos);
                $mensaje = "Torneo creado correctamente";
            }
            
            $_SESSION['ok'] = $mensaje;
            $this->redirect('index.php?c=torneos&a=index');
            
        } catch (InvalidArgumentException $e) {
            $_SESSION['error_toast'] = $e->getMessage();
            $this->reRenderForm($id, ['general' => $e->getMessage()]);
        } catch (Exception $e) {
            error_log("[TorneosController::guardar] " . $e->getMessage());
            $_SESSION['error_toast'] = "Error al guardar: " . $e->getMessage();
            $this->reRenderForm($id, ['general' => "Error al guardar: " . $e->getMessage()]);
        }
    }
    
    private function reRenderForm(int $id, array $errores): void {
        $model = new Torneo();
        $torneo = ($id > 0) ? $model->obtener($id) : [];
        
        if (!empty($_POST)) {
            $torneo = array_merge($torneo ?: [], [
                'id_torneo' => $id,
                'nombre_torneo' => trim($_POST['nombre_torneo'] ?? ''),
                'fecha_inicio' => trim($_POST['fecha_inicio'] ?? ''),
                'fecha_fin' => trim($_POST['fecha_fin'] ?? ''),
                'lugar' => trim($_POST['lugar'] ?? ''),
            ]);
        }
        
        $stats = ($id > 0) ? $model->contarCombates($id) : ['total' => 0, 'con_resultado' => 0];
        $this->view('torneos/form', compact('torneo', 'errores', 'stats'));
    }
    
    public function eliminar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID inválido");
            $model = new Torneo();
            if (!$model->obtener($id)) throw new Exception("Torneo no encontrado");
            $model->eliminar($id);
            $_SESSION['ok'] = "Torneo eliminado correctamente";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $this->redirect('index.php?c=torneos&a=index');
    }
}