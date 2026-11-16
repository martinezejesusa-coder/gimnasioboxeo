<?php
class ClubesController extends Controller {
    
    public function index(): void {
        try {
            $this->requireLogin();
            $clubes = (new Club())->todos();
            $this->view('clubes/index', compact('clubes'));
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al cargar la lista de clubes";
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
    
    public function ver(): void {
        try {
            $this->requireLogin();
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de club inválido");
            
            $m = new Club();
            $club = $m->obtener($id);
            if (!$club) throw new Exception("Club no encontrado");
            
            $atletas = $m->atletasDe($id);
            $entrenadores = $m->entrenadoresDe($id);
            $this->view('clubes/ver', compact('club', 'atletas', 'entrenadores'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=clubes&a=index');
        }
    }
    
    public function form(): void {
        try {
            $this->requireRol(['Administrador', 'Entrenador']);
            $id = (int)($_GET['id'] ?? 0);
            $club = []; $entrenadorActual = null; $errores = [];
            
            if ($id > 0) {
                $m = new Club();
                $club = $m->obtener($id);
                if (!$club) throw new Exception("Club no encontrado");
                $entrenadorActual = $m->obtenerEntrenadorResponsable($id);
            }
            
            $entrenadores = (new Entrenador())->todos();
            $estados = (new Club())->getEstados();
            $paises = Club::getPaises();

            $this->view('clubes/form', compact('club', 'entrenadores', 'entrenadorActual', 'errores', 'estados', 'paises'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=clubes&a=index');
        }
    }
    
    public function guardar(): void {
        $this->requireRol(['Administrador', 'Entrenador']);
        $id = (int)($_POST['id'] ?? 0);
        $errores = [];
        
        try {
            // ==================== VALIDACIONES BACKEND CON REGEX ====================
            
            // --- Nombre ---
            $nombre = trim($_POST['nombre_club'] ?? '');
            $regexNombre = '/^[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.,&]{3,100}$/u';
            if ($nombre === '') {
                $errores['nombre_club'] = 'El nombre del club es obligatorio';
            } elseif (!preg_match($regexNombre, $nombre)) {
                $errores['nombre_club'] = 'Solo letras, números, espacios, guiones, puntos, comas y &';
            } else {
                $m = new Club();
                if ($m->existeNombre($nombre, $id > 0 ? $id : null)) {
                    $errores['nombre_club'] = 'Ya existe un club con ese nombre';
                }
            }
            
            // --- Ubicación ---
            $ubicacion = trim($_POST['ubicacion'] ?? '');
            if ($ubicacion !== '' && !preg_match('/^[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.,#°ºª]+$/u', $ubicacion)) {
                $errores['ubicacion'] = 'La dirección contiene caracteres no permitidos';
            }
            if (mb_strlen($ubicacion) > 255) $errores['ubicacion'] = 'Máximo 255 caracteres';
            
            // --- Geografía ---
            $id_parroquia = !empty($_POST['id_parroquia']) ? (int)$_POST['id_parroquia'] : null;
            
            // --- Teléfono ---
            $telefono = trim($_POST['telefono'] ?? '');
            if ($telefono !== '') {
                if (!preg_match('/^\+?[\d\s\-\(\)\.]+$/', $telefono)) {
                    $errores['telefono'] = 'Solo números, +, -, (), espacios y puntos';
                } else {
                    $soloDigitos = preg_replace('/\D/', '', $telefono);
                    if (strlen($soloDigitos) < 7) $errores['telefono'] = 'El teléfono debe tener al menos 7 dígitos';
                    elseif (strlen($soloDigitos) > 20) $errores['telefono'] = 'El teléfono no puede tener más de 20 dígitos';
                }
            }
            
            // --- Correo ---
            $correo = trim($_POST['correo'] ?? '');
            if ($correo !== '') {
                $regexCorreo = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
                if (!preg_match($regexCorreo, $correo)) {
                    $errores['correo'] = 'Correo electrónico inválido';
                } else {
                    $m = new Club();
                    if ($m->existeCorreo($correo, $id > 0 ? $id : null)) {
                        $errores['correo'] = 'Ya existe un club con ese correo';
                    }
                }
            }
            
            // --- Logo ---
            $logoRuta = null;
            if (!empty($_FILES['logo']['name']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $extension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $errores['logo'] = 'Formato no permitido';
                } elseif ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
                    $errores['logo'] = 'El logo excede los 2MB';
                } else {
                    $nombreArchivo = 'club_' . uniqid() . '.' . $extension;
                    $directorio = __DIR__ . '/../public/uploads/clubes/';
                    if (!is_dir($directorio)) mkdir($directorio, 0755, true);
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $directorio . $nombreArchivo)) {
                        $logoRuta = 'public/uploads/clubes/' . $nombreArchivo;
                    } else {
                        $errores['logo'] = 'Error al subir el logo.';
                    }
                }
            }
            
            // ==================== RE-RENDERIZAR SI HAY ERRORES ====================
            if (!empty($errores)) {
                $_SESSION['error_toast'] = 'Error: ' . implode(' | ', $errores);
                
                $m = new Club();
                $club = ($id > 0) ? $m->obtener($id) : [];
                $club = array_merge($club ?: [], [
                    'id_club' => $id, 'nombre_club' => $nombre, 'ubicacion' => $ubicacion,
                    'id_parroquia' => $id_parroquia, 'telefono' => $telefono, 'correo' => $correo,
                    'logo' => $logoRuta ?? ($club['logo'] ?? null),
                ]);
                $entrenadores = (new Entrenador())->todos();
                $estados = (new Club())->getEstados();
                $entrenadorActual = !empty($_POST['id_entrenador']) ? (int)$_POST['id_entrenador'] : null;
                $paises = Club::getPaises();
                $this->view('clubes/form', compact('club', 'entrenadores', 'entrenadorActual', 'errores', 'estados', 'paises'));
                return;
            }
            
            // ==================== GUARDAR ====================
            $datos = [
                'nombre_club' => $nombre, 'ubicacion' => $ubicacion ?: null, 'id_parroquia' => $id_parroquia,
                'telefono' => $telefono ?: null, 'correo' => $correo ?: null, 'logo' => $logoRuta,
                'id_entrenador' => !empty($_POST['id_entrenador']) ? (int)$_POST['id_entrenador'] : null,
            ];
            
            $m = new Club();
            if ($id > 0) {
                $m->actualizar($id, $datos);
                $_SESSION['ok'] = "Club actualizado correctamente";
            } else {
                $id = $m->crear($datos);
                $_SESSION['ok'] = "Club creado correctamente";
            }
            
            $this->redirect('index.php?c=clubes&a=index');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=clubes&a=form' . ($id > 0 ? "&id=$id" : ""));
        }
    }
    
    public function eliminar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            (new Club())->eliminar($id);
            $_SESSION['ok'] = "Club eliminado correctamente";
        } catch (Exception $e) { $_SESSION['error'] = $e->getMessage(); }
        $this->redirect('index.php?c=clubes&a=index');
    }

    // ==================== ENDPOINTS AJAX ====================
    public function municipiosAjax(): void {
        header('Content-Type: application/json; charset=utf-8');
        $id_estado = (int)($_GET['id_estado'] ?? 0);
        echo json_encode((new Club())->getMunicipios($id_estado), JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function parroquiasAjax(): void {
        header('Content-Type: application/json; charset=utf-8');
        $id_municipio = (int)($_GET['id_municipio'] ?? 0);
        echo json_encode((new Club())->getParroquias($id_municipio), JSON_UNESCAPED_UNICODE);
        exit;
    }
}