<?php
/**
 * Controlador del Dashboard
 * 
 * Muestra estadísticas y resumen del sistema
 * 
 * @version 3.0 - Corregido para usar vista_atletas (sin columna nivel)
 */
class DashboardController extends Controller {
    
    public function index() {
        try {

        $db = Database::getInstance();
            $this->requireLogin();
            $dasData= new Dashboard();
            $stats = $dasData->stats();
            $ultimos = $dasData->ultimos();
            $nivelesQuery = $dasData->niveles();

            $atletasNivel = [
                'Novato' => (int)($nivelesQuery['Novato'] ?? 0),
                'Intermedio' => (int)($nivelesQuery['Intermedio'] ?? 0),
                'Avanzado' => (int)($nivelesQuery['Avanzado'] ?? 0),
                'Experto' => (int)($nivelesQuery['Experto'] ?? 0)
            ];
            $combatesMes = $dasData->combatesMes();
            $torneosDetalle = $dasData->torneosDetalle();
            $this->view('dashboard/index', compact('stats', 'ultimos', 'atletasNivel', 'combatesMes', 'torneosDetalle'));
            
        } catch (Throwable $e) {
            // 🚨 MODO DEBUG - Mostrar error en pantalla en lugar de redirigir en bucle
            error_log("[DashboardController::index] " . $e->getMessage());
            
            // Detectar si es un error de bucle (ya estamos en el dashboard)
            $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
            if (strpos($currentUrl, 'c=dashboard') !== false && strpos($currentUrl, 'a=index') !== false) {
                // Mostrar el error en pantalla
                die("
                    <div style='font-family:sans-serif;padding:20px;max-width:900px;margin:auto'>
                        <h1 style='color:#dc3545'>🚨 Error en el Dashboard</h1>
                        <p style='font-size:1.1rem'><strong>Mensaje:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
                        <p><strong>Archivo:</strong> <code>" . htmlspecialchars($e->getFile()) . "</code></p>
                        <p><strong>Línea:</strong> <code>" . $e->getLine() . "</code></p>
                        <hr>
                        <h3>Stack trace (para depurar):</h3>
                        <pre style='background:#f5f5f5;padding:15px;font-size:12px;overflow:auto;border-radius:5px'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
                        <hr>
                        <p><a href='?c=atletas&a=index'>← Ir a Atletas (funciona)</a></p>
                    </div>
                ");
            } else {
                // Si es otra ruta, redirigir normal
                $_SESSION['error'] = "Error al cargar el dashboard";
                $this->redirect('index.php?c=dashboard&a=index');
            }
        }
    }
}
