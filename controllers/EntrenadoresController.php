<?php
/**
 * Controlador de Entrenadores
 * @version 4.0 - Regex estricta, Toasts, sin redirect loop
 */
class EntrenadoresController extends Controller {
    
    public function index(): void {
        try {
            $this->requireLogin();
            $entrenadores = (new Entrenador())->todos();
            $clubes = (new Club())->todos();
            $modeloEnt = new Entrenador();
            $clubesPorEntrenador = [];
            foreach ($entrenadores as $e) {
            $clubesPorEntrenador[$e['id_entrenador']] = $modeloEnt->clubesDe((int)$e['id_entrenador']);
}
            $this->view('entrenadores/index', compact('entrenadores', 'clubes','clubesPorEntrenador'));
        } catch (Exception $e) {
            error_log("[EntrenadoresController::index] " . $e->getMessage());
            $_SESSION['error'] = "Error al cargar la lista de entrenadores";
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
    
    public function form(): void {
        try {
            $this->requireRol(['Administrador', 'Entrenador']);
            $id = (int)($_GET['id'] ?? 0);
            $m = new Entrenador();
            $entrenador = [];
            $clubesAsignados = [];
            $errores = [];
            
            if ($id > 0) {
                $entrenador = $m->obtener($id);
                if (!$entrenador) throw new Exception("Entrenador no encontrado");
                $clubesAsignados = $m->clubesDe($id);
            }
            
            $clubes = (new Club())->todos();
            $paises = Club::getPaises();
            
            $this->view('entrenadores/form', compact('entrenador', 'clubes', 'clubesAsignados', 'errores', 'paises'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=entrenadores&a=index');
        }
    }
    
    public function guardar(): void {
        $this->requireRol(['Administrador', 'Entrenador']);
        $id = (int)($_POST['id'] ?? 0);
        $errores = [];
        
        try {
            // ==================== VALIDACIONES CON REGEX ====================
            
            $regexNombre = '/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s\'\.\-]+$/u';
            
            // --- Primer nombre ---
            $pnombre = trim($_POST['pnombre_entrenador'] ?? '');
            if ($pnombre === '') {
                $errores['pnombre_entrenador'] = 'El primer nombre es obligatorio';
            } elseif (mb_strlen($pnombre) < 2 || mb_strlen($pnombre) > 50) {
                $errores['pnombre_entrenador'] = 'Debe tener entre 2 y 50 caracteres';
            } elseif (!preg_match($regexNombre, $pnombre)) {
                $errores['pnombre_entrenador'] = 'Solo letras, espacios, apóstrofes, puntos y guiones';
            }
            
            // --- Segundo nombre (opcional) ---
            $snombre = trim($_POST['snombre_entrenador'] ?? '');
            if ($snombre !== '' && !preg_match($regexNombre, $snombre)) {
                $errores['snombre_entrenador'] = 'Solo letras y espacios';
            }
            
            // --- Primer apellido ---
            $papellido = trim($_POST['apellido_entrenador'] ?? '');
            if ($papellido === '') {
                $errores['apellido_entrenador'] = 'El primer apellido es obligatorio';
            } elseif (mb_strlen($papellido) < 2 || mb_strlen($papellido) > 50) {
                $errores['apellido_entrenador'] = 'Debe tener entre 2 y 50 caracteres';
            } elseif (!preg_match($regexNombre, $papellido)) {
                $errores['apellido_entrenador'] = 'Solo letras, espacios, apóstrofes, puntos y guiones';
            }
            
            // --- Segundo apellido (opcional) ---
            $sapellido = trim($_POST['sapellido_entrenador'] ?? '');
            if ($sapellido !== '' && !preg_match($regexNombre, $sapellido)) {
                $errores['sapellido_entrenador'] = 'Solo letras y espacios';
            }
            
            // --- Cédula ---
            $cedula = strtoupper(trim($_POST['cedula_entrenador'] ?? ''));
            if ($cedula === '') {
                $errores['cedula_entrenador'] = 'La cédula es obligatoria';
            } elseif (!preg_match('/^[VEJP]?-?[0-9]{6,9}$/', $cedula)) {
                $errores['cedula_entrenador'] = 'Formato inválido. Ejemplos: V-12345678, E-12345678, 12345678';
            } else {
                $m = new Entrenador();
                if ($m->existeCedula($cedula, $id > 0 ? $id : null)) {
                    $errores['cedula_entrenador'] = 'Ya existe un entrenador con esa cédula';
                }
            }
            
            // --- Teléfono ---
            $telefono = trim($_POST['telefono'] ?? '');
            if ($telefono !== '') {
                if (!preg_match('/^\+?[\d\s\-\(\)\.]+$/', $telefono)) {
                    $errores['telefono'] = 'Solo números, +, -, (), espacios y puntos';
                } else {
                    $soloDigitos = preg_replace('/\D/', '', $telefono);
                    if (strlen($soloDigitos) < 7) $errores['telefono'] = 'Mínimo 7 dígitos';
                    elseif (strlen($soloDigitos) > 20) $errores['telefono'] = 'Máximo 20 dígitos';
                }
            }
            
            // --- Correo ---
            $correo = trim($_POST['correo'] ?? '');
            if ($correo !== '') {
                $regexCorreo = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
                if (!preg_match($regexCorreo, $correo)) $errores['correo'] = 'Correo electrónico inválido';
            }
            
            // ==================== RE-RENDERIZAR SI HAY ERRORES ====================
            if (!empty($errores)) {
                $_SESSION['error_toast'] = 'Error: ' . implode(' | ', array_values($errores));
                $this->reRenderForm($id, $errores);
                return;
            }
            
            // ==================== GUARDAR ====================
            $datos = [
                'pnombre_entrenador' => $pnombre,
                'snombre_entrenador' => $snombre ?: null,
                'apellido_entrenador' => $papellido,
                'sapellido_entrenador' => $sapellido ?: null,
                'cedula_entrenador' => $cedula,
                'telefono' => $telefono ?: null,
                'correo' => $correo ?: null,
                'activo' => isset($_POST['activo']) ? 1 : 0,
            ];
            
            $m = new Entrenador();
            if ($id > 0) {
                $m->actualizar($id, $datos);
                $mensaje = "Entrenador actualizado correctamente";
            } else {
                $id = $m->crear($datos);
                $mensaje = "Entrenador creado correctamente";
            }
            
            if ($id) $m->asignarClubes($id, $_POST['clubes'] ?? []);
            
            $_SESSION['ok'] = $mensaje;
            $this->redirect('index.php?c=entrenadores&a=index');
            
        } catch (InvalidArgumentException $e) {
            $_SESSION['error_toast'] = $e->getMessage();
            $this->reRenderForm($id, ['general' => $e->getMessage()]);
        } catch (Exception $e) {
            error_log("[EntrenadoresController::guardar] " . $e->getMessage());
            $_SESSION['error_toast'] = "Error al guardar: " . $e->getMessage();
            $this->reRenderForm($id, ['general' => "Error al guardar: " . $e->getMessage()]);
        }
    }
    
    private function reRenderForm(int $id, array $errores): void {
        $m = new Entrenador();
        $entrenador = ($id > 0) ? $m->obtener($id) : [];
        
        if (!empty($_POST)) {
            $entrenador = array_merge($entrenador ?: [], [
                'id_entrenador' => $id,
                'pnombre_entrenador' => trim($_POST['pnombre_entrenador'] ?? ''),
                'snombre_entrenador' => trim($_POST['snombre_entrenador'] ?? ''),
                'apellido_entrenador' => trim($_POST['apellido_entrenador'] ?? ''),
                'sapellido_entrenador' => trim($_POST['sapellido_entrenador'] ?? ''),
                'cedula_entrenador' => trim($_POST['cedula_entrenador'] ?? ''),
                'telefono' => trim($_POST['telefono'] ?? ''),
                'correo' => trim($_POST['correo'] ?? ''),
                'activo' => isset($_POST['activo']) ? 1 : 0,
            ]);
        }
        
        $clubesAsignados = !empty($_POST['clubes']) 
            ? array_map('intval', (array)$_POST['clubes']) 
            : (($id > 0) ? $m->clubesDe($id) : []);
        
        $clubes = (new Club())->todos();
        $paises = Club::getPaises();
        $this->view('entrenadores/form', compact('entrenador', 'clubes', 'clubesAsignados', 'errores', 'paises'));
    }
    
    public function eliminar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID inválido");
            $m = new Entrenador();
            if (!$m->obtener($id)) throw new Exception("Entrenador no encontrado");
            if ($m->eliminar($id)) $_SESSION['ok'] = "Entrenador eliminado correctamente";
            else $_SESSION['error'] = "No se pudo eliminar";
        } catch (Exception $e) { $_SESSION['error'] = $e->getMessage(); }
        $this->redirect('index.php?c=entrenadores&a=index');
    }
}
