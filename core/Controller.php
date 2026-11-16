<?php
/**
 * Controlador Base
 * Proporciona funcionalidades comunes a todos los controladores.
 */
abstract class Controller {
    
    /**
     * Renderiza una vista dentro de su plantilla principal.
     */
    protected function view(string $vista, array $datos = [], string $layout = 'main'): void {
        extract($datos);
        $contenidoVista = __DIR__ . '/../views/' . $vista . '.php';
        
        if (!file_exists($contenidoVista)) {
            die("Vista no encontrada: $vista");
        }
        
        ob_start();
        include $contenidoVista;
        $contenido = ob_get_clean();
        
        include __DIR__ . '/../views/layouts/' . $layout . '.php';
    }

    /**
     * Envía una respuesta en formato JSON y termina la ejecución del script.
     */
    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Determina si la petición actual se realizó mediante AJAX.
     */
    protected function isAjax(): bool {
        $isXmlHttpRequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        $acceptsJson = isset($_SERVER['HTTP_ACCEPT']) && 
                       strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;

        return $isXmlHttpRequest || $acceptsJson;
    }

    /**
     * Verifica que el usuario haya iniciado sesión. Redirige o devuelve error según el tipo de petición.
     */
    protected function requireLogin(): void {
        if (!isset($_SESSION['usuario'])) {
            if ($this->isAjax()) {
                $this->json(['status' => 'error', 'mensaje' => 'Sesión expirada', 'redirect' => 'index.php?c=auth&a=login'], 401);
            }
            header('Location: ' . BASE_URL . 'index.php?c=auth&a=login');
            exit;
        }
    }

    /**
     * Restringe el acceso a roles específicos. Redirige o devuelve error según el tipo de petición.
     */
    protected function requireRol(array $roles): void {
        $this->requireLogin();
        
        if (!in_array($_SESSION['usuario']['rol'], $roles)) {
            if ($this->isAjax()) {
                $this->json(['status' => 'error', 'mensaje' => 'No tienes permisos para esta acción'], 403);
            }
            $_SESSION['error'] = 'No tiene permisos para acceder a esta sección.';
            header('Location: ' . BASE_URL . 'index.php?c=dashboard&a=index');
            exit;
        }
    }

    /**
     * Redirige a una URL interna.
     */
    protected function redirect(string $url): void {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
}