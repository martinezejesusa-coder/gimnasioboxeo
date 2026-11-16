<?php
/**
 * Controlador de Reportes
 * @version 5.2 - Orden por defecto en rango de combates: Cantidad DESC, Win Rate DESC
 */
class ReportesController extends Controller {
    
    public function index() {
        try {
            $this->requireLogin();
            
            $tipo = $_GET['tipo'] ?? '';
            $datos = [];
            $clubes = (new Club())->todos();
            $categorias = (new Categoria())->todos();
            $tituloReporte = '';
            
            $resultado = $this->obtenerDatosReporte($tipo, $_GET);
            $datos = $resultado['datos'];
            $tituloReporte = $resultado['titulo'];
            
            $this->view('reportes/index', compact('tipo', 'datos', 'clubes', 'categorias', 'tituloReporte'));
            
        } catch (Exception $e) {
            error_log("[ReportesController::index] " . $e->getMessage());
            $_SESSION['error'] = "Error al cargar los reportes";
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
    
    public function ajax() {
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $this->requireLogin();
            $tipo = $_GET['tipo'] ?? '';
            $resultado = $this->obtenerDatosReporte($tipo, $_GET);
            
            echo json_encode([
                'status' => 'success',
                'tipo' => $tipo,
                'titulo' => $resultado['titulo'],
                'datos' => $resultado['datos'],
                'total' => count($resultado['datos'])
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    private function obtenerDatosReporte(string $tipo, array $params): array {
        $db = Database::getInstance();
        $datos = [];
        $titulo = '';
        
        // Detectar ordenamiento
        $ordenColumna = $params['orden_columna'] ?? '';
        $ordenDireccion = strtoupper($params['orden_direccion'] ?? 'ASC');
        if (!in_array($ordenDireccion, ['ASC', 'DESC'])) $ordenDireccion = 'ASC';
        
        // Mapeo de columnas
        $columnasPermitidas = [
            'cedula' => 'a.cedula',
            'nombre' => 'a.pnombre',
            'club' => 'c.nombre_club',
            'nivel' => 'a.total_combates',
            'victorias' => 'a.victorias',
            'derrotas' => 'a.derrotas',
            'empates' => 'a.empates',
            'total' => 'a.total_combates',
            'winrate' => '((a.victorias / NULLIF(a.total_combates, 0)) * 100)'
        ];
        
        // Construir ORDER BY base
        $orderBy = '';
        if (!empty($ordenColumna) && isset($columnasPermitidas[$ordenColumna])) {
            $orderBy = " ORDER BY " . $columnasPermitidas[$ordenColumna] . " " . $ordenDireccion;
        } else {
            // ⭐ ÓRDENES POR DEFECTO SEGÚN EL TIPO
            if ($tipo === 'ranking') {
                $orderBy = " ORDER BY a.victorias DESC, a.total_combates DESC";
            } elseif ($tipo === 'rango_combates') {
                // ⭐ NUEVO ORDEN: Primero por cantidad de combates (Desc), luego por Win Rate (Desc)
                $orderBy = " ORDER BY a.total_combates DESC, ((a.victorias / NULLIF(a.total_combates, 0)) * 100) DESC";
            } else {
                $orderBy = " ORDER BY a.papellido ASC, a.pnombre ASC";
            }
        }
        
        if ($tipo === 'atletas_categoria' && !empty($params['id_categoria'])) {
            $idCat = (int)$params['id_categoria'];
            $sql = "SELECT a.*, c.nombre_club FROM vista_atletas a 
                    LEFT JOIN clubes c ON c.id_club=a.id_club 
                    WHERE a.id_categoria=? AND a.activo=1" . $orderBy;
            $stmt = $db->prepare($sql);
            $stmt->execute([$idCat]);
            $datos = $stmt->fetchAll();
            
            foreach ($datos as &$a) {
                $a['nivel_calculado'] = Atleta::calcularNivel((int)$a['total_combates']);
            }
            unset($a);
            
            $catNombre = $db->prepare("SELECT nombre_categoria FROM categorias WHERE id_categoria=?");
            $catNombre->execute([$idCat]);
            $nombreCat = $catNombre->fetchColumn() ?: 'Sin categoría';
            $titulo = "Atletas por Categoría: " . $nombreCat;
            
        } elseif ($tipo === 'atletas_club' && !empty($params['id_club'])) {
            $idClub = (int)$params['id_club'];
            $sql = "SELECT a.*, c.nombre_club FROM vista_atletas a 
                    LEFT JOIN clubes c ON c.id_club=a.id_club 
                    WHERE a.id_club=? AND a.activo=1" . $orderBy;
            $stmt = $db->prepare($sql);
            $stmt->execute([$idClub]);
            $datos = $stmt->fetchAll();
            
            foreach ($datos as &$a) {
                $a['nivel_calculado'] = Atleta::calcularNivel((int)$a['total_combates']);
            }
            unset($a);
            
            $clubNombre = $db->prepare("SELECT nombre_club FROM clubes WHERE id_club=?");
            $clubNombre->execute([$idClub]);
            $nombreClub = $clubNombre->fetchColumn() ?: 'Sin club';
            $titulo = "Atletas por Club: " . $nombreClub;
            
        } elseif ($tipo === 'ranking') {
            $sql = "SELECT a.*, c.nombre_club FROM vista_atletas a 
                    LEFT JOIN clubes c ON c.id_club=a.id_club 
                    WHERE a.activo=1" . $orderBy . " LIMIT 50";
            $datos = $db->query($sql)->fetchAll();
            
            foreach ($datos as &$a) {
                $a['nivel_calculado'] = Atleta::calcularNivel((int)$a['total_combates']);
            }
            unset($a);
            
            $titulo = "Ranking del Gimnasio - Top 50";
            
        } elseif ($tipo === 'rango_combates') {
            $min = (int)($params['min'] ?? 0);
            $max = (int)($params['max'] ?? 100);
            if ($min < 0) $min = 0;
            if ($max < $min) $max = $min;
            
            $sql = "SELECT a.*, c.nombre_club FROM vista_atletas a 
                    LEFT JOIN clubes c ON c.id_club=a.id_club 
                    WHERE a.total_combates BETWEEN ? AND ? AND a.activo=1" . $orderBy;
            $stmt = $db->prepare($sql);
            $stmt->execute([$min, $max]);
            $datos = $stmt->fetchAll();
            
            foreach ($datos as &$a) {
                $a['nivel_calculado'] = Atleta::calcularNivel((int)$a['total_combates']);
            }
            unset($a);
            
            $titulo = "Atletas con {$min} - {$max} combates";
        }
        
        return ['datos' => $datos, 'titulo' => $titulo];
    }
    
    public function exportar() {
        try {
            $this->requireLogin();
            
            $tipo = $_GET['tipo'] ?? '';
            $resultado = $this->obtenerDatosReporte($tipo, $_GET);
            $datos = $resultado['datos'];
            $tituloReporte = $resultado['titulo'];
            
            $ordenColumna = $_GET['orden_columna'] ?? '';
            $ordenDireccion = $_GET['orden_direccion'] ?? '';
            
            $textoOrden = '';
            if (!empty($ordenColumna)) {
                $nombres = [
                    'cedula' => 'Cédula', 'nombre' => 'Nombre', 'club' => 'Club',
                    'nivel' => 'Nivel', 'victorias' => 'Victorias', 'derrotas' => 'Derrotas',
                    'empates' => 'Empates', 'total' => 'Total Combates', 'winrate' => 'Win Rate'
                ];
                $nombreCol = $nombres[$ordenColumna] ?? $ordenColumna;
                $dir = $ordenDireccion === 'DESC' ? 'descendente' : 'ascendente';
                $textoOrden = "Ordenado por: {$nombreCol} ({$dir})";
            }
            
            include __DIR__ . '/../views/reportes/pdf.php';
            
        } catch (Exception $e) {
            error_log("[ReportesController::exportar] " . $e->getMessage());
            $_SESSION['error'] = "Error al exportar el reporte";
            $this->redirect('index.php?c=reportes&a=index');
        }
    }
}