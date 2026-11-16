<?php
// crear_directorios.php
echo "<h1>Configurando directorios de uploads</h1>";

$directorios = [
    'public/uploads' => __DIR__ . '/public/uploads',
    'public/uploads/atletas' => __DIR__ . '/public/uploads/atletas',
    'public/uploads/documentos' => __DIR__ . '/public/uploads/documentos',
];

foreach ($directorios as $nombre => $ruta) {
    echo "<h3>Directorio: $nombre</h3>";
    
    // Verificar si existe
    if (!file_exists($ruta)) {
        echo "❌ No existe. Creando... ";
        if (mkdir($ruta, 0777, true)) {
            echo "✅ CREADO<br>";
        } else {
            echo "❌ ERROR AL CREAR<br>";
        }
    } else {
        echo "✅ Ya existe<br>";
    }
    
    // Cambiar permisos
    if (file_exists($ruta)) {
        if (chmod($ruta, 0777)) {
            echo "✅ Permisos cambiados a 777<br>";
        } else {
            echo "⚠️ No se pudieron cambiar permisos. Hazlo manualmente<br>";
        }
    }
    
    // Probar escritura
    $testFile = $ruta . '/test_write.txt';
    if (file_put_contents($testFile, 'test')) {
        echo "✅ Permiso de escritura OK<br>";
        unlink($testFile);
    } else {
        echo "❌ NO se puede escribir. Permisos incorrectos<br>";
    }
    
    echo "<br>";
}

echo "<h2>Permisos actuales:</h2>";
foreach ($directorios as $nombre => $ruta) {
    if (file_exists($ruta)) {
        $perms = fileperms($ruta);
        $perms_decimal = substr(sprintf('%o', $perms), -4);
        echo "$nombre: $perms_decimal<br>";
    }
}