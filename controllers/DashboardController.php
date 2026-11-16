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
            $this->requireLogin();
            $db = Database::getInstance();
            
            // ========== ESTADÍSTICAS PRINCIPALES ==========
            $stats = [
                // Atletas
                'atletas' => $db->query("SELECT COUNT(*) FROM atletas WHERE activo=1")->fetchColumn(),
                'atletas_inactivos' => $db->query("SELECT COUNT(*) FROM atletas WHERE activo=0")->fetchColumn(),
                
                // Combates
                'combates' => $db->query("SELECT COUNT(*) FROM combates")->fetchColumn(),
                'combates_pendientes' => $db->query("SELECT COUNT(*) FROM combates WHERE resultado='Pendiente'")->fetchColumn(),
                'combates_finalizados' => $db->query("SELECT COUNT(*) FROM combates WHERE resultado!='Pendiente'")->fetchColumn(),
                
                // Entrenadores y Clubes
                'entrenadores' => $db->query("SELECT COUNT(*) FROM entrenadores")->fetchColumn(),
                'clubes' => $db->query("SELECT COUNT(*) FROM clubes")->fetchColumn(),
                
                // Torneos (activos en curso)
                'torneos_activos' => $db->query("SELECT COUNT(*) FROM torneos WHERE fecha_inicio <= CURDATE() AND (fecha_fin IS NULL OR fecha_fin >= CURDATE())")->fetchColumn(),
                'torneos_proximos' => $db->query("SELECT COUNT(*) FROM torneos WHERE fecha_inicio > CURDATE()")->fetchColumn(),
            ];
            
            // ========== ÚLTIMOS COMBATES ==========
            $ultimos = $db->query("SELECT cb.*, 
                CONCAT(al.pnombre,' ',al.papellido) local_nombre,
                CONCAT(av.pnombre,' ',av.papellido) visitante_nombre
                FROM combates cb
                LEFT JOIN atletas al ON al.id_atleta=cb.id_atleta_local
                LEFT JOIN atletas av ON av.id_atleta=cb.id_atleta_visitante
                ORDER BY cb.fecha DESC LIMIT 5")->fetchAll();
            
            // ========== ATLETAS POR NIVEL (CORREGIDO) ==========
            // Usamos vista_atletas (que calcula total_combates) y agrupamos por rangos en SQL
            $nivelesQuery = $db->query("
                SELECT 
                    SUM(CASE WHEN total_combates <= 5 THEN 1 ELSE 0 END) AS Novato,
                    SUM(CASE WHEN total_combates BETWEEN 6 AND 20 THEN 1 ELSE 0 END) AS Intermedio,
                    SUM(CASE WHEN total_combates BETWEEN 21 AND 40 THEN 1 ELSE 0 END) AS Avanzado,
                    SUM(CASE WHEN total_combates >= 41 THEN 1 ELSE 0 END) AS Experto
                FROM vista_atletas 
                WHERE activo = 1
            ")->fetch();
            
            $atletasNivel = [
                'Novato' => (int)($nivelesQuery['Novato'] ?? 0),
                'Intermedio' => (int)($nivelesQuery['Intermedio'] ?? 0),
                'Avanzado' => (int)($nivelesQuery['Avanzado'] ?? 0),
                'Experto' => (int)($nivelesQuery['Experto'] ?? 0)
            ];
            
            // ========== COMBATES DEL MES ==========
            $combatesMes = $db->query("SELECT COUNT(*) FROM combates WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())")->fetchColumn();
            
            // ========== TORNEOS ACTIVOS (DETALLE) ==========
            $torneosDetalle = $db->query("SELECT nombre_torneo, fecha_inicio, fecha_fin FROM torneos WHERE fecha_inicio <= CURDATE() AND (fecha_fin IS NULL OR fecha_fin >= CURDATE()) LIMIT 3")->fetchAll();
            
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