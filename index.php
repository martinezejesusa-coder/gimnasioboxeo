<?php

/**
 * Front controller (Router básico)
 * URL: index.php?c=controlador&a=accion
 * 
 * @version 2.0 - Con validaciones de archivos y manejo de errores mejorado
 */

// ==================== VALIDACIÓN DE ARCHIVOS DE CONFIGURACIÓN ====================

$configFile = __DIR__ . '/config/config.php';
if (!file_exists($configFile)) {
    die("Error: Archivo de configuración no encontrado en: config/config.php");
}
require_once $configFile;

// ==================== VALIDACIÓN DE ARCHIVOS DEL CORE ====================

$coreFiles = [
    'Database' => __DIR__ . '/core/Database.php',
    'Model' => __DIR__ . '/core/Model.php',
    'Controller' => __DIR__ . '/core/Controller.php',
    'helpers' => __DIR__ . '/core/helpers.php',
];

foreach ($coreFiles as $nombre => $ruta) {
    if (!file_exists($ruta)) {
        die("Error: Archivo core '$nombre' no encontrado en: $ruta");
    }
    require_once $ruta;
}


// ==================== AUTOLOAD DE MODELOS Y CONTROLADORES ====================

/**
 * Autoloader con validación de archivos
 */
spl_autoload_register(function ($class) {
    // Directorios a buscar
    $directorios = [
        'models' => __DIR__ . '/models/',
        'controllers' => __DIR__ . '/controllers/',
    ];
    
    foreach ($directorios as $tipo => $directorio) {
        $archivo = $directorio . $class . '.php';
        
        if (file_exists($archivo)) {
            require_once $archivo;
            return true;
        }
    }
    
    // Log de error si no se encuentra la clase (opcional)
    error_log("Clase no encontrada: $class");
    return false;
});

// ==================== VALIDACIÓN DE PARÁMETROS ====================

$controlador = $_GET['c'] ?? 'auth';
$accion = $_GET['a'] ?? 'login';

// Validar que el controlador no contenga caracteres maliciosos
if (!preg_match('/^[a-zA-Z0-9_]+$/', $controlador)) {
    die("Error: Nombre de controlador inválido");
}

// Validar que la acción no contenga caracteres maliciosos
if (!preg_match('/^[a-zA-Z0-9_]+$/', $accion)) {
    die("Error: Nombre de acción inválido");
}

// ==================== INSTANCIAR CONTROLADOR ====================

$claseControlador = ucfirst($controlador) . 'Controller';

if (!class_exists($claseControlador)) {
    // Mostrar error amigable
    http_response_code(404);
    die("Error 404: Controlador '$claseControlador' no existe");
}

try {
    $ctrl = new $claseControlador();
    
    if (!method_exists($ctrl, $accion)) {
        http_response_code(404);
        die("Error 404: Acción '$accion' no existe en el controlador '$claseControlador'");
    }
    
    // Ejecutar la acción
    $ctrl->$accion();
    
} catch (Exception $e) {
    // Manejo de excepciones
    error_log("Error en el controlador: " . $e->getMessage());
    
    if (defined('DEBUG') && DEBUG === true) {
        die("Error: " . $e->getMessage());
    } else {
        die("Error interno del servidor. Por favor, contacte al administrador.");
    }
}