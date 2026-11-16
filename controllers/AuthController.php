<?php
/**
 * Controlador de Autenticación
 * 
 * Maneja el login, logout y control de sesiones
 * 
 * @version 2.0 - Refactorizado con try-catch y logging
 */
class AuthController extends Controller {
    
    /**
     * Muestra formulario de login
     */
    public function login(): void {
        // Si ya está logueado, redirigir al dashboard
        if (isset($_SESSION['usuario'])) {
            $this->redirect('index.php?c=dashboard&a=index');
        }
        
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                
                if (empty($username) || empty($password)) {
                    throw new Exception("Por favor, complete todos los campos");
                }
                
                $usuarioModel = new Usuario();
                $usuario = $usuarioModel->autenticar($username, $password);
                
                if ($usuario) {
                    $_SESSION['usuario'] = $usuario;
                    $this->redirect('index.php?c=dashboard&a=index');
                } else {
                    throw new Exception("Usuario o contraseña incorrectos");
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                error_log("[AuthController::login] Error: " . $e->getMessage());
            }
        }
        
        // Incluir vista de login
        include __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * Cierra sesión
     */
    public function logout(): void {
        session_destroy();
        $_SESSION = [];
        $this->redirect('index.php?c=auth&a=login');
    }
}