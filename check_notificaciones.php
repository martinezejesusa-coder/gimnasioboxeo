<?php
/**
 * Endpoint para verificar notificaciones en tiempo real
 */

session_start();

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit;
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';

try {
    $db = Database::getInstance();
    $respuesta = [
        'nuevos_combates' => 0,
        'combates_pendientes' => 0,
        'atletas_nuevos' => 0,
        'torneos_activos' => 0,
        'mensaje' => null,
        'tipo' => null,
        'timestamp' => date('H:i:s')
    ];
    
    // 1. Combates de las últimas 24 horas
    try {
        $stmt = $db->query("SELECT COUNT(*) FROM combates WHERE fecha >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        $respuesta['nuevos_combates'] = (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        error_log("[Notificaciones] Error en combates 24h: " . $e->getMessage());
        $respuesta['nuevos_combates'] = 0;
    }
    
    // 2. Combates pendientes
    try {
        $stmt = $db->query("SELECT COUNT(*) FROM combates WHERE resultado = 'Pendiente'");
        $respuesta['combates_pendientes'] = (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        error_log("[Notificaciones] Error en combates pendientes: " . $e->getMessage());
        $respuesta['combates_pendientes'] = 0;
    }
    
    // 3. Atletas nuevos (últimos 7 días) - Solo si existe la columna
    try {
        // Primero verificar si existe la columna fecha_creacion
        $stmt = $db->query("SHOW COLUMNS FROM atletas LIKE 'fecha_creacion'");
        if ($stmt->rowCount() > 0) {
            $stmt2 = $db->query("SELECT COUNT(*) FROM atletas WHERE fecha_creacion >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND activo = 1");
            $respuesta['atletas_nuevos'] = (int)$stmt2->fetchColumn();
        } else {
            // Si no existe, contar atletas creados recientemente por ID (aproximado)
            $stmt2 = $db->query("SELECT COUNT(*) FROM atletas WHERE id_atleta > (SELECT MAX(id_atleta) - 10 FROM atletas)");
            $respuesta['atletas_nuevos'] = (int)$stmt2->fetchColumn();
        }
    } catch (Exception $e) {
        error_log("[Notificaciones] Error en atletas nuevos: " . $e->getMessage());
        $respuesta['atletas_nuevos'] = 0;
    }
    
    // 4. Torneos activos (solo si existe la tabla)
    try {
        $stmt = $db->query("SHOW TABLES LIKE 'torneos'");
        if ($stmt->rowCount() > 0) {
            $stmt2 = $db->query("SELECT COUNT(*) FROM torneos WHERE fecha_inicio <= CURDATE() AND (fecha_fin IS NULL OR fecha_fin >= CURDATE())");
            $respuesta['torneos_activos'] = (int)$stmt2->fetchColumn();
        }
    } catch (Exception $e) {
        error_log("[Notificaciones] Error en torneos activos: " . $e->getMessage());
        $respuesta['torneos_activos'] = 0;
    }
    
    // 5. Generar mensaje según prioridad
    if ($respuesta['combates_pendientes'] > 5) {
        $respuesta['mensaje'] = "⚠️ Hay {$respuesta['combates_pendientes']} combates pendientes por resolver";
        $respuesta['tipo'] = 'warning';
    } elseif ($respuesta['nuevos_combates'] > 0) {
        $respuesta['mensaje'] = "🥊 {$respuesta['nuevos_combates']} combate(s) nuevo(s) en las últimas 24 horas";
        $respuesta['tipo'] = 'info';
    } elseif ($respuesta['atletas_nuevos'] > 0) {
        $respuesta['mensaje'] = "👤 {$respuesta['atletas_nuevos']} atleta(s) nuevo(s) registrados recientemente";
        $respuesta['tipo'] = 'success';
    } elseif ($respuesta['torneos_activos'] > 0) {
        $respuesta['mensaje'] = "🏆 {$respuesta['torneos_activos']} torneo(s) activo(s) en curso";
        $respuesta['tipo'] = 'primary';
    }
    
    header('Content-Type: application/json');
    echo json_encode($respuesta);
    
} catch (Exception $e) {
    error_log("[Notificaciones] Error general: " . $e->getMessage());
    echo json_encode([
        'error' => 'Error al obtener notificaciones',
        'detalle' => $e->getMessage()
    ]);
}