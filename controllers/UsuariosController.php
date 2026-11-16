<?php
/**
 * Controlador de Usuarios
 * @version 4.0 - Toasts, validaciones sin redirect loop
 */
class UsuariosController extends Controller {
    
    public function index(): void {
        try {
            $this->requireRol(['Administrador']);
            $usuarios = (new Usuario())->todos();
            $this->view('usuarios/index', compact('usuarios'));
        } catch (Exception $e) {
            error_log("[UsuariosController::index] " . $e->getMessage());
            $_SESSION['error'] = "Error al cargar la lista de usuarios";
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
    
    public function form(): void {
        try {
            $this->requireRol(['Administrador']);
            $id = (int)($_GET['id'] ?? 0);
            $usuario = [];
            $errores = [];
            if ($id > 0) {
                $usuario = (new Usuario())->obtener($id);
                if (!$usuario) throw new Exception("Usuario no encontrado");
            }
            $this->view('usuarios/form', compact('usuario', 'errores'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=usuarios&a=index');
        }
    }
    
    public function guardar(): void {
        $this->requireRol(['Administrador']);
        $id = (int)($_POST['id'] ?? 0);
        $errores = [];
        $idActual = (int)($_SESSION['usuario']['id_usuario'] ?? 0);
        
        try {
            // ==================== VALIDACIONES ====================
            
            // --- Username ---
            $username = trim($_POST['username'] ?? '');
            if ($username === '') {
                $errores['username'] = 'El nombre de usuario es obligatorio';
            } elseif (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
                $errores['username'] = 'Entre 3 y 50 caracteres. Solo letras, números y guión bajo';
            } else {
                $m = new Usuario();
                if ($m->existeUsername($username, $id > 0 ? $id : null)) {
                    $errores['username'] = 'El nombre de usuario ya está en uso';
                }
            }
            
            // --- Contraseña ---
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $cambiaPassword = ($password !== '' || $passwordConfirm !== '');
            
            if ($id === 0 && $password === '') {
                $errores['password'] = 'La contraseña es obligatoria';
            }
            
            if ($cambiaPassword) {
                if (strlen($password) < 8) {
                    $errores['password'] = 'Mínimo 8 caracteres';
                } elseif (strlen($password) > 72) {
                    $errores['password'] = 'Máximo 72 caracteres';
                } elseif (!preg_match('/[A-Z]/', $password)) {
                    $errores['password'] = 'Debe contener al menos una mayúscula';
                } elseif (!preg_match('/[a-z]/', $password)) {
                    $errores['password'] = 'Debe contener al menos una minúscula';
                } elseif (!preg_match('/[0-9]/', $password)) {
                    $errores['password'] = 'Debe contener al menos un número';
                } elseif ($username !== '' && strtolower($password) === strtolower($username)) {
                    $errores['password'] = 'La contraseña no puede ser igual al nombre de usuario';
                }
                
                if (empty($errores['password']) && $password !== $passwordConfirm) {
                    $errores['password_confirm'] = 'Las contraseñas no coinciden';
                }
            }
            
            // --- Rol ---
            $rol = $_POST['rol'] ?? 'Consulta';
            if (!in_array($rol, Usuario::ROLES, true)) {
                $errores['rol'] = 'Rol inválido';
            }
            
            // --- Activo ---
            $activo = isset($_POST['activo']) ? 1 : 0;
            
            // ==================== PROTECCIONES ====================
            $m = new Usuario();
            
            if ($id > 0 && $id === $idActual && $activo === 0) {
                $errores['activo'] = 'No puedes desactivar tu propio usuario';
            }
            
            if ($id > 0 && $id === $idActual && $rol !== 'Administrador') {
                $usuarioActual = $m->obtener($id);
                if ($usuarioActual && $usuarioActual['rol'] === 'Administrador' && $m->esUltimoAdmin($id)) {
                    $errores['rol'] = 'No puedes cambiar tu rol porque eres el único Administrador activo';
                }
            }
            
            if ($id > 0 && $activo === 0) {
                if ($m->esUltimoAdmin($id)) {
                    $errores['activo'] = 'No puedes desactivar al último Administrador activo';
                }
            }
            
            // ==================== RE-RENDERIZAR SI HAY ERRORES ====================
            if (!empty($errores)) {
                $_SESSION['error_toast'] = 'Error: ' . implode(' | ', array_values($errores));
                $usuario = ($id > 0) ? $m->obtener($id) : [];
                $usuario = array_merge($usuario ?: [], [
                    'id_usuario' => $id,
                    'username' => $username,
                    'rol' => $rol,
                    'activo' => $activo,
                ]);
                $this->view('usuarios/form', compact('usuario', 'errores'));
                return;
            }
            
            // ==================== GUARDAR ====================
            $datos = ['username' => $username, 'rol' => $rol, 'activo' => $activo];
            if ($cambiaPassword) $datos['password'] = $password;
            
            if ($id > 0) {
                $m->actualizar($id, $datos);
                $mensaje = "Usuario actualizado correctamente";
            } else {
                $m->crear($datos);
                $mensaje = "Usuario creado correctamente";
            }
            
            $_SESSION['ok'] = $mensaje;
            $this->redirect('index.php?c=usuarios&a=index');
            
        } catch (InvalidArgumentException $e) {
            $_SESSION['error_toast'] = $e->getMessage();
            $this->reRenderForm($id, ['general' => $e->getMessage()]);
        } catch (Exception $e) {
            error_log("[UsuariosController::guardar] " . $e->getMessage());
            $_SESSION['error_toast'] = "Error al guardar: " . $e->getMessage();
            $this->reRenderForm($id, ['general' => "Error al guardar: " . $e->getMessage()]);
        }
    }
    
    private function reRenderForm(int $id, array $errores): void {
        $m = new Usuario();
        $usuario = ($id > 0) ? $m->obtener($id) : [];
        if (!empty($_POST)) {
            $usuario = array_merge($usuario ?: [], [
                'id_usuario' => $id,
                'username' => trim($_POST['username'] ?? ''),
                'rol' => $_POST['rol'] ?? 'Consulta',
                'activo' => isset($_POST['activo']) ? 1 : 0,
            ]);
        }
        $this->view('usuarios/form', compact('usuario', 'errores'));
    }
    
    public function desactivar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            $idActual = (int)($_SESSION['usuario']['id_usuario'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de usuario inválido");
            if ($id === $idActual) throw new Exception("No puedes desactivar tu propio usuario");
            $m = new Usuario();
            if (!$m->obtener($id)) throw new Exception("Usuario no encontrado");
            if ($m->esUltimoAdmin($id)) throw new Exception("No puedes desactivar al último Administrador activo");
            if ($m->desactivar($id)) $_SESSION['ok'] = "Usuario desactivado correctamente";
            else $_SESSION['error'] = "No se pudo desactivar el usuario";
        } catch (Exception $e) { $_SESSION['error'] = $e->getMessage(); }
        $this->redirect('index.php?c=usuarios&a=index');
    }
    
    public function activar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de usuario inválido");
            $m = new Usuario();
            if ($m->activar($id)) $_SESSION['ok'] = "Usuario activado correctamente";
            else $_SESSION['error'] = "No se pudo activar el usuario";
        } catch (Exception $e) { $_SESSION['error'] = $e->getMessage(); }
        $this->redirect('index.php?c=usuarios&a=index');
    }
    
    public function eliminar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            $idActual = (int)($_SESSION['usuario']['id_usuario'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de usuario inválido");
            if ($id === $idActual) throw new Exception("No puedes eliminar tu propio usuario");
            $m = new Usuario();
            if (!$m->obtener($id)) throw new Exception("Usuario no encontrado");
            if ($m->esUltimoAdmin($id)) throw new Exception("No puedes eliminar al último Administrador activo");
            if ($m->eliminar($id)) $_SESSION['ok'] = "Usuario eliminado correctamente";
            else $_SESSION['error'] = "No se pudo eliminar el usuario";
        } catch (Exception $e) { $_SESSION['error'] = $e->getMessage(); }
        $this->redirect('index.php?c=usuarios&a=index');
    }
}