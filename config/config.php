<?php
/**
 * Configuración global del sistema
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'jesus');
define('DB_PASS', '1234'); 
define('DB_NAME', 'gimnasio_boxeo');

// URL base: ajustar según despliegue
define('BASE_URL', 'http://localhost/boxeo/');

// Zona horaria
date_default_timezone_set('America/Caracas');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
