<?php
/**
 * Controlador de Jueces
 * @version 2.1 - Index muestra activos e inactivos
 */
class JuecesController extends Controller {
    
    public function index(): void {
        try {
            $this->requireRol(['Administrador']);
            // ⭐ CAMBIO: usar todosConInactivos() para mostrar TODOS
            $jueces = (new Juez())->todosConInactivos();
            $this->view('jueces/index', compact('jueces'));
        } catch (Exception $e) {
            error_log("[JuecesController::index] " . $e->getMessage());
            $_SESSION['error'] = "Error al cargar la lista de jueces";
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
    
    public function form(): void {
        try {
            $this->requireRol(['Administrador']);
            $id = (int)($_GET['id'] ?? 0);
            $juez = [];
            $errores = [];
            
            if ($id > 0) {
                $juez = (new Juez())->obtener($id);
                if (!$juez) throw new Exception("Juez no encontrado");
            }
            
            $this->view('jueces/form', compact('juez', 'errores'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=jueces&a=index');
        }
    }
    
    public function guardar(): void {
        $this->requireRol(['Administrador']);
        $id = (int)($_POST['id'] ?? 0);
        $errores = [];
        
        try {
            $regexNombre = '/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u';
            
            $pnombre = trim($_POST['pnombre'] ?? '');
            if ($pnombre === '') {
                $errores['pnombre'] = 'El nombre del juez es obligatorio';
            } elseif (!preg_match($regexNombre, $pnombre)) {
                $errores['pnombre'] = 'Solo letras y espacios (2-50 caracteres)';
            }
            
            $papellido = trim($_POST['papellido'] ?? '');
            if ($papellido === '') {
                $errores['papellido'] = 'El apellido del juez es obligatorio';
            } elseif (!preg_match($regexNombre, $papellido)) {
                $errores['papellido'] = 'Solo letras y espacios (2-50 caracteres)';
            }
            
            $cedula = trim($_POST['cedula'] ?? '');
            if ($cedula !== '') {
                if (!preg_match('/^[VEJP]?-?[0-9]{6,9}$/i', $cedula)) {
                    $errores['cedula'] = 'Formato inválido. Use V-, E-, J-, P- seguido de 6 a 9 dígitos, o solo números';
                } else {
                    $modelo = new Juez();
                    if ($modelo->existeCedula($cedula, $id > 0 ? $id : null)) {
                        $errores['cedula'] = 'Ya existe un juez registrado con esa cédula';
                    }
                }
            }
            
            if (!empty($errores)) {
                $_SESSION['error_toast'] = 'Error: ' . implode(' | ', array_values($errores));
                $juez = ($id > 0) ? (new Juez())->obtener($id) : [];
                $juez = array_merge($juez ?: [], [
                    'id_juez'   => $id,
                    'pnombre'   => $pnombre,
                    'papellido' => $papellido,
                    'cedula'    => $cedula,
                    'activo'    => isset($_POST['activo']) ? 1 : 0,
                ]);
                $this->view('jueces/form', compact('juez', 'errores'));
                return;
            }
            
            $datos = [
                'pnombre'   => $pnombre,
                'papellido' => $papellido,
                'cedula'    => $cedula !== '' ? $cedula : null,
                'activo'    => isset($_POST['activo']) ? 1 : 0,
            ];
            
            $m = new Juez();
            if ($id > 0) {
                $m->actualizar($id, $datos);
                $mensaje = "Juez actualizado correctamente";
            } else {
                $id = $m->crear($datos);
                $mensaje = "Juez creado correctamente";
            }
            
            $_SESSION['ok'] = $mensaje;
            $this->redirect('index.php?c=jueces&a=index');
            
        } catch (InvalidArgumentException $e) {
            $_SESSION['error_toast'] = $e->getMessage();
            $juez = ($id > 0) ? (new Juez())->obtener($id) : [];
            $juez = array_merge($juez ?: [], [
                'id_juez'   => $id,
                'pnombre'   => trim($_POST['pnombre'] ?? ''),
                'papellido' => trim($_POST['papellido'] ?? ''),
                'cedula'    => trim($_POST['cedula'] ?? ''),
                'activo'    => isset($_POST['activo']) ? 1 : 0,
            ]);
            $errores['general'] = $e->getMessage();
            $this->view('jueces/form', compact('juez', 'errores'));
        } catch (Exception $e) {
            error_log("[JuecesController::guardar] " . $e->getMessage());
            $_SESSION['error_toast'] = "Error al guardar: " . $e->getMessage();
            $juez = ($id > 0) ? (new Juez())->obtener($id) : [];
            $errores['general'] = "Error al guardar: " . $e->getMessage();
            $this->view('jueces/form', compact('juez', 'errores'));
        }
    }
    
    public function eliminar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de juez inválido");
            $m = new Juez();
            if (!$m->obtener($id)) throw new Exception("Juez no encontrado");
            if ($m->eliminar($id)) $_SESSION['ok'] = "Juez eliminado correctamente";
            else $_SESSION['error'] = "No se pudo eliminar el juez";
        } catch (Exception $e) { $_SESSION['error'] = $e->getMessage(); }
        $this->redirect('index.php?c=jueces&a=index');
    }
    
    public function activar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de juez inválido");
            $m = new Juez();
            if (!$m->obtener($id)) throw new Exception("Juez no encontrado");
            $m->activar($id);
            $_SESSION['ok'] = "Juez activado correctamente";
        } catch (Exception $e) { $_SESSION['error'] = $e->getMessage(); }
        $this->redirect('index.php?c=jueces&a=index');
    }
    
    public function desactivar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de juez inválido");
            $m = new Juez();
            if (!$m->obtener($id)) throw new Exception("Juez no encontrado");
            $m->desactivar($id);
            $_SESSION['ok'] = "Juez desactivado correctamente";
        } catch (Exception $e) { $_SESSION['error'] = $e->getMessage(); }
        $this->redirect('index.php?c=jueces&a=index');
    }
}