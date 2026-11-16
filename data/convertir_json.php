<?php
/**
 * Convierte venezuela.json al formato de árbol PHP esperado por el sistema.
 * 
 * Uso:
 *     php data/convertir_json.php
 * 
 * Genera:
 *     data/venezuela_geo.php
 */

$jsonFile = __DIR__ . '/venezuela.json';
$outFile  = __DIR__ . '/venezuela_geo.php';

if (!file_exists($jsonFile)) {
    fwrite(STDERR, "❌ No se encontró: $jsonFile\n");
    exit(1);
}

$json = file_get_contents($jsonFile);
$data = json_decode($json, true);

if (!is_array($data)) {
    fwrite(STDERR, "❌ El JSON no es válido o está vacío\n");
    exit(1);
}

$arbol = [];

foreach ($data as $estadoData) {
    $estadoNombre = trim($estadoData['estado'] ?? '');
    if ($estadoNombre === '') continue;
    
    $arbol[$estadoNombre] = [];
    
    foreach ($estadoData['municipios'] ?? [] as $munData) {
        $munNombre = trim($munData['municipio'] ?? '');
        if ($munNombre === '') continue;
        
        $parroquias = [];
        foreach ($munData['parroquias'] ?? [] as $parroquia) {
            $parroquia = trim($parroquia);
            if ($parroquia !== '') {
                $parroquias[] = $parroquia;
            }
        }
        
        sort($parroquias, SORT_NATURAL | SORT_FLAG_CASE);
        $arbol[$estadoNombre][$munNombre] = $parroquias;
    }
    
    ksort($arbol[$estadoNombre], SORT_NATURAL | SORT_FLAG_CASE);
}

ksort($arbol, SORT_NATURAL | SORT_FLAG_CASE);

// Contar totales
$totalEstados = count($arbol);
$totalMunicipios = 0;
$totalParroquias = 0;
foreach ($arbol as $municipios) {
    $totalMunicipios += count($municipios);
    foreach ($municipios as $parroquias) {
        $totalParroquias += count($parroquias);
    }
}

// Generar el PHP
$phpContent = "<?php\n";
$phpContent .= "/**\n";
$phpContent .= " * División político-territorial de Venezuela\n";
$phpContent .= " * Generado automáticamente desde venezuela.json\n";
$phpContent .= " * Fecha: " . date('Y-m-d H:i:s') . "\n";
$phpContent .= " * Estados: $totalEstados | Municipios: $totalMunicipios | Parroquias: $totalParroquias\n";
$phpContent .= " */\n";
$phpContent .= "return " . var_export($arbol, true) . ";\n";

if (file_put_contents($outFile, $phpContent) === false) {
    fwrite(STDERR, "❌ No se pudo escribir: $outFile\n");
    exit(1);
}

echo "✅ Archivo generado: $outFile\n";
echo "   Estados:     $totalEstados\n";
echo "   Municipios:  $totalMunicipios\n";
echo "   Parroquias:  $totalParroquias\n";