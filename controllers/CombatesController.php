<?php
/**
 * Controlador de Combates
 * 
 * Usa los SETTERS del modelo para validar cada campo (sin duplicar lógica).
 * Los métodos crear()/actualizar() del modelo NO reciben parámetros.
 */
class CombatesController extends Controller {
    
    public function index(): void {
        try {
            $this->requireLogin();
            $scriptsModulo = ['public/js/combates.js'];
            $this->view('combates/index', compact('scriptsModulo'));
        } catch (Exception $e) {
            error_log("[CombatesController::index] " . $e->getMessage());
            $_SESSION['error'] = "Error al cargar la lista de combates";
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
    
    public function ajaxListar(): void {
        $this->requireLogin();
        try {
            $resultado = (new Combate())->listarServerSide($_POST);
            $this->json($resultado);
        } catch (Exception $e) {
            error_log("[CombatesController::ajaxListar] " . $e->getMessage());
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function ver(): void {
        $this->requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            $_SESSION['error'] = "ID de combate inválido";
            $this->redirect('index.php?c=combates&a=index');
            return;
        }
        
        $modelo = new Combate();
        $combate = $modelo->obtener($id);
        
        if (!$combate) {
            $_SESSION['error'] = "Combate no encontrado";
            $this->redirect('index.php?c=combates&a=index');
            return;
        }
        
        $tarjetas = (new TarjetaPuntuacion())->obtenerPorCombate($id);
        $resumenJueces = (new TarjetaPuntuacion())->resumenPorJuez($id);
        
        // Obtener atletas desde el modelo
        $atletaModel = new Atleta();
        $local = $atletaModel->obtener($combate['id_atleta_local']);
        $visitante = $atletaModel->obtener($combate['id_atleta_visitante']);
        
        // Lógica de negocio en el CONTROLADOR
        $resultado = $combate['resultado'];
        $colorRes = 'secondary';
        $textoRes = $resultado;
        $ganadorNombre = '';
        
        $nombreLocal = isset($local['pnombre']) ? $local['pnombre'] . ' ' . $local['papellido'] : 'N/A';
        $nombreVisitante = isset($visitante['pnombre']) ? $visitante['pnombre'] . ' ' . $visitante['papellido'] : 'N/A';
        
        if ($resultado === 'Local') {
            $colorRes = 'success'; $textoRes = 'GANA LOCAL'; $ganadorNombre = $nombreLocal;
        } elseif ($resultado === 'Visitante') {
            $colorRes = 'success'; $textoRes = 'GANA VISITANTE'; $ganadorNombre = $nombreVisitante;
        } elseif ($resultado === 'Empate') {
            $colorRes = 'secondary'; $textoRes = 'EMPATE';
        } elseif ($resultado === 'Pendiente') {
            $colorRes = 'warning'; $textoRes = 'PENDIENTE';
        } elseif ($resultado === 'No Contest') {
            $colorRes = 'dark'; $textoRes = 'SIN DECISIÓN';
        }
        
        $this->view('combates/ver', compact(
            'combate', 'tarjetas', 'resumenJueces',
            'local', 'visitante',
            'colorRes', 'textoRes', 'ganadorNombre',
            'nombreLocal', 'nombreVisitante'
        ));
    }
    
    public function form(): void {
        try {
            $this->requireRol(['Administrador', 'Entrenador']);
            
            $modelo = new Combate();
            $id = (int)($_GET['id'] ?? 0);
            $combate = null;
            $tarjetas = [];
            $errores = [];
            
            $localId = (int)($_GET['local'] ?? 0);
            $visitanteId = (int)($_GET['visitante'] ?? 0);
            $idTorneo = (int)($_GET['id_torneo'] ?? 0);
            
            if ($id > 0) {
                $combate = $modelo->obtener($id);
                if (!$combate) throw new Exception("Combate no encontrado");
                $tarjetas = (new TarjetaPuntuacion())->obtenerPorCombate($id);
            } else {
                $combate = [
                    'id_combate' => 0,
                    'id_atleta_local' => $localId > 0 ? $localId : null,
                    'id_atleta_visitante' => $visitanteId > 0 ? $visitanteId : null,
                    'id_torneo' => $idTorneo > 0 ? $idTorneo : null,
                    'fecha' => date('Y-m-d'),
                    'ronda' => 1,
                    'rounds_pautados' => 3,
                    'round_finalizacion' => null,
                    'decision_victoria' => null,
                    'resultado' => 'Pendiente',
                    'posicion_llave' => null
                ];
            }
            
            $torneos = $modelo->torneos();
            $atletas = (new Atleta())->todos(['activo' => 1]);
            $jueces = (new Juez())->todos();
            
            $scriptsModulo = ['public/js/combates.js'];
            
            $this->view('combates/form', compact(
                'combate', 'torneos', 'atletas', 'jueces', 'tarjetas', 'errores', 'scriptsModulo'
            ));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=combates&a=index');
        }
    }
    
    
    public function guardar(): void {
        $this->requireRol(['Administrador', 'Entrenador']);
        
        $id = (int)($_POST['id'] ?? 0);
        $errores = [];
        
        try {
            $modelo = new Combate();
            
            // ENCAPSULAMIENTO:
            // Cada setter valida. Si falla, capturamos el error específico del campo.
            
            try { $modelo->setIdTorneo(!empty($_POST['id_torneo']) ? (int)$_POST['id_torneo'] : null); }
            catch (InvalidArgumentException $e) { $errores['id_torneo'] = $e->getMessage(); }
            
            try { $modelo->setIdAtletaLocal((int)($_POST['id_atleta_local'] ?? 0)); }
            catch (InvalidArgumentException $e) { $errores['id_atleta_local'] = $e->getMessage(); }
            
            try { $modelo->setIdAtletaVisitante((int)($_POST['id_atleta_visitante'] ?? 0)); }
            catch (InvalidArgumentException $e) { $errores['id_atleta_visitante'] = $e->getMessage(); }
            
            try { $modelo->setFecha($_POST['fecha'] ?? ''); }
            catch (InvalidArgumentException $e) { $errores['fecha'] = $e->getMessage(); }
            
            try { $modelo->setRonda((int)($_POST['ronda'] ?? 0)); }
            catch (InvalidArgumentException $e) { $errores['ronda'] = $e->getMessage(); }
            
            try { $modelo->setRoundsPautados((int)($_POST['rounds_pautados'] ?? 3)); }
            catch (InvalidArgumentException $e) { $errores['rounds_pautados'] = $e->getMessage(); }
            
            try { $modelo->setRoundFinalizacion(!empty($_POST['round_finalizacion']) ? (int)$_POST['round_finalizacion'] : null); }
            catch (InvalidArgumentException $e) { $errores['round_finalizacion'] = $e->getMessage(); }
            
            try { $modelo->setDecisionVictoria($_POST['decision_victoria'] ?? null); }
            catch (InvalidArgumentException $e) { $errores['decision_victoria'] = $e->getMessage(); }
            
            try { $modelo->setResultado($_POST['resultado'] ?? 'Pendiente'); }
            catch (InvalidArgumentException $e) { $errores['resultado'] = $e->getMessage(); }
            
            try { $modelo->setPosicionLlave(!empty($_POST['posicion_llave']) ? (int)$_POST['posicion_llave'] : null); }
            catch (InvalidArgumentException $e) { $errores['posicion_llave'] = $e->getMessage(); }
            
            // Validación de reglas cruzadas (ronda final <= rounds pautados)
            $roundFinal = !empty($_POST['round_finalizacion']) ? (int)$_POST['round_finalizacion'] : null;
            $roundsPautados = (int)($_POST['rounds_pautados'] ?? 3);
            if ($roundFinal !== null && $roundFinal > $roundsPautados) {
                $errores['round_finalizacion'] = "El asalto no puede superar los {$roundsPautados} pautados";
            }
            
            // Validación de tarjetas
            $tarjetas = $_POST['tarjetas'] ?? [];
            $tarjetasLimpias = [];
            $combinaciones = [];
            $resultado = $_POST['resultado'] ?? 'Pendiente';
            
            if ($resultado === 'Pendiente' && !empty($tarjetas)) {
                foreach ($tarjetas as $t) {
                    if (!empty($t['id_juez']) || !empty($t['puntos_local']) || !empty($t['puntos_visitante'])) {
                        $errores['tarjetas'] = 'No puedes registrar tarjetas si el combate está Pendiente';
                        break;
                    }
                }
            }
            
            if (empty($errores['tarjetas']) && !empty($tarjetas) && is_array($tarjetas)) {
                foreach ($tarjetas as $t) {
                    if (empty($t['id_juez']) && empty($t['puntos_local']) && empty($t['puntos_visitante'])) continue;
                    if (empty($t['id_juez'])) { $errores['tarjetas'] = 'Cada tarjeta debe tener un juez'; break; }
                    if (empty($t['numero_round']) || (int)$t['numero_round'] <= 0) { $errores['tarjetas'] = 'El round debe ser positivo'; break; }
                    $pl = (int)($t['puntos_local'] ?? 0);
                    $pv = (int)($t['puntos_visitante'] ?? 0);
                    if ($pl < 6 || $pl > 10 || $pv < 6 || $pv > 10) { $errores['tarjetas'] = 'Puntos entre 6 y 10'; break; }
                    $key = $t['id_juez'] . '-' . $t['numero_round'];
                    if (isset($combinaciones[$key])) { $errores['tarjetas'] = 'No puedes duplicar tarjetas del mismo juez y round'; break; }
                    $combinaciones[$key] = true;
                    $tarjetasLimpias[] = [
                        'id_juez' => (int)$t['id_juez'],
                        'numero_round' => (int)$t['numero_round'],
                        'puntos_local' => $pl,
                        'puntos_visitante' => $pv
                    ];
                }
            }
            
            if (!empty($errores)) {
                $this->json([
                    'status' => 'error',
                    'mensaje' => 'Por favor, corrige los errores del formulario',
                    'errores' => $errores
                ], 422);
            }
            
            // Persistir usando los métodos SIN parámetros
            if ($id > 0) {
                $modelo->setIdCombate($id);
                $modelo->actualizar();
                $mensaje = "Combate actualizado correctamente";
            } else {
                $id = $modelo->crear();
                $mensaje = "Combate creado correctamente";
            }
            
            (new TarjetaPuntuacion())->guardarTarjetas($id, $tarjetasLimpias);
            
            $this->json([
                'status' => 'success',
                'mensaje' => $mensaje,
                'url_redirect' => BASE_URL . 'index.php?c=combates&a=index'
            ]);
            
        } catch (InvalidArgumentException $e) {
            $this->json(['status' => 'error', 'mensaje' => $e->getMessage()], 422);
        } catch (Exception $e) {
            error_log("[CombatesController::guardar] " . $e->getMessage());
            $this->json(['status' => 'error', 'mensaje' => 'Error interno del servidor'], 500);
        }
    }
    
    public function eliminar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID inválido");
            $modelo = new Combate();
            if (!$modelo->obtener($id)) throw new Exception("Combate no encontrado");
            if ($modelo->eliminar($id)) $_SESSION['ok'] = "Combate eliminado correctamente";
            else $_SESSION['error'] = "No se pudo eliminar";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $this->redirect('index.php?c=combates&a=index');
    }
    
    public function sugerir(): void {
        try {
            $this->requireRol(['Administrador', 'Entrenador']);
            
            $categorias = (new Categoria())->todos();
            $sugerencias = [];
            $filtros = ['categoria' => '', 'min' => 0, 'max' => 100];
            $errores = [];
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $filtros = [
                    'categoria' => (int)($_POST['categoria'] ?? 0),
                    'min' => (int)($_POST['min'] ?? 0),
                    'max' => (int)($_POST['max'] ?? 100),
                ];
                
                if ($filtros['categoria'] <= 0) $errores['categoria'] = 'Selecciona una categoría';
                
                if (empty($errores)) {
                    $sugerencias = (new Combate())->sugerir($filtros['categoria'], $filtros['min'], $filtros['max']);
                }
                
                if ($this->isAjax()) {
                    if (!empty($errores)) $this->json(['status' => 'error', 'mensaje' => reset($errores)], 422);
                    $this->json(['status' => 'success', 'sugerencias' => $sugerencias]);
                }
            }
            
            $scriptsModulo = ['public/js/combates.js'];
            $this->view('combates/sugerir', compact('categorias', 'sugerencias', 'filtros', 'scriptsModulo'));
        } catch (Exception $e) {
            error_log("[CombatesController::sugerir] " . $e->getMessage());
            if ($this->isAjax()) $this->json(['status' => 'error', 'mensaje' => $e->getMessage()], 500);
            $_SESSION['error'] = "Error al generar emparejamientos";
            $this->redirect('index.php?c=combates&a=index');
        }
    }
}
