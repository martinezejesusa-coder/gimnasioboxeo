<?php
/**
 * Controlador de Atletas
 * @version 4.0 - Validaciones con Regex, Toasts, sin recalcular récord
 */
class AtletasController extends Controller {
    
    private const UPLOAD_FOTOS_DIR = '/../public/uploads/atletas/';
    private const UPLOAD_DOCS_DIR = '/../public/uploads/documentos/';
    private const MAX_FILE_SIZE = 5 * 1024 * 1024;
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    private const ALLOWED_DOC_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
    
    public function index(): void {
        try {
            $this->requireLogin();
            $filtros = [
                'club' => $_GET['club'] ?? '',
                'categoria' => $_GET['categoria'] ?? '',
                'activo' => $_GET['activo'] ?? '',
            ];
            $atletas = (new Atleta())->todos($filtros);
            $clubes = (new Club())->todos();
            $categorias = (new Categoria())->todos();
            $this->view('atletas/index', compact('atletas', 'clubes', 'categorias', 'filtros'));
        } catch (Exception $e) {
            error_log("[AtletasController::index] " . $e->getMessage());
            $_SESSION['error'] = "Error al cargar la lista de atletas";
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
    
    public function ver(): void {
        try {
            $this->requireLogin();
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de atleta inválido");
            
            $model = new Atleta();
            $atleta = $model->obtener($id);
            if (!$atleta) throw new Exception("Atleta no encontrado");
            
            $documentos = $model->documentos($id);
            $historial = $model->historialCombates($id);
            
            $this->view('atletas/ver', compact('atleta', 'documentos', 'historial'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=atletas&a=index');
        }
    }
    
    public function form(): void {
        try {
            $this->requireRol(['Administrador', 'Entrenador']);
            $id = (int)($_GET['id'] ?? 0);
            $atleta = [];
            $documentosExistentes = [];
            $errores = [];
            
            if ($id > 0) {
                $model = new Atleta();
                $atleta = $model->obtener($id);
                $documentosExistentes = $model->documentos($id);
                if (!$atleta) throw new Exception("Atleta no encontrado");
            }
            
            $clubes = (new Club())->todos();
            $categorias = (new Categoria())->todos();
            
            $this->view('atletas/form', compact('atleta', 'clubes', 'categorias', 'documentosExistentes', 'errores'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=atletas&a=index');
        }
    }
    
    public function guardar(): void {
        $this->requireRol(['Administrador', 'Entrenador']);
        $id = (int)($_POST['id'] ?? 0);
        $errores = [];
        
        try {
            // ==================== VALIDACIONES BACKEND CON REGEX ====================
            
            $pnombre = trim($_POST['pnombre'] ?? '');
            if ($pnombre === '') {
                $errores['pnombre'] = 'El primer nombre es obligatorio';
            } elseif (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $pnombre)) {
                $errores['pnombre'] = 'Solo letras, entre 2 y 50 caracteres';
            }
            
            $snombre = trim($_POST['snombre'] ?? '');
            if ($snombre !== '' && !preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $snombre)) {
                $errores['snombre'] = 'Solo letras, máximo 50 caracteres';
            }
            
            $papellido = trim($_POST['papellido'] ?? '');
            if ($papellido === '') {
                $errores['papellido'] = 'El primer apellido es obligatorio';
            } elseif (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $papellido)) {
                $errores['papellido'] = 'Solo letras, entre 2 y 50 caracteres';
            }
            
            $sapellido = trim($_POST['sapellido'] ?? '');
            if ($sapellido !== '' && !preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $sapellido)) {
                $errores['sapellido'] = 'Solo letras, máximo 50 caracteres';
            }
            
            // --- Cédula ---
            $cedula = strtoupper(trim($_POST['cedula'] ?? ''));
            if ($cedula === '') {
                $errores['cedula'] = 'La cédula es obligatoria';
            } elseif (!preg_match('/^[VEJP]?-?\d{6,9}$/u', $cedula)) {
                $errores['cedula'] = 'Formato inválido. Ejemplos: V-12345678, E-12345678, 12345678';
            } else {
                $modelo = new Atleta();
                $normalizada = strtoupper(preg_replace('/[\s\-]/', '', $cedula));
                if (preg_match('/^\d+$/', $normalizada)) $normalizada = 'V' . $normalizada;
                if ($modelo->existeCedula($normalizada, $id > 0 ? $id : null)) {
                    $errores['cedula'] = 'Ya existe un atleta registrado con esa cédula';
                }
            }
            
            // --- Fecha de nacimiento ---
            $fechaNac = trim($_POST['fecha_nacimiento'] ?? '');
            if ($fechaNac !== '') {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaNac)) {
                    $errores['fecha_nacimiento'] = 'Formato de fecha inválido';
                } else {
                    $fechaObj = new DateTime($fechaNac);
                    $hoy = new DateTime();
                    $edad = $hoy->diff($fechaObj)->y;
                    if ($edad < 5) $errores['fecha_nacimiento'] = 'El atleta debe tener al menos 5 años';
                    elseif ($edad > 100) $errores['fecha_nacimiento'] = 'Fecha fuera de rango (máx. 100 años)';
                }
            }
            
            // ==================== SI HAY ERRORES, RE-RENDERIZAR ====================
            if (!empty($errores)) {
                $_SESSION['error_toast'] = 'Error: ' . implode(' | ', array_values($errores));
                
                $atleta = $id > 0 ? (new Atleta())->obtener($id) : [];
                $clubes = (new Club())->todos();
                $categorias = (new Categoria())->todos();
                $documentosExistentes = $id > 0 ? (new Atleta())->documentos($id) : [];
                
                $this->view('atletas/form', compact('atleta', 'clubes', 'categorias', 'documentosExistentes', 'errores'));
                return;
            }
            
            // ==================== PREPARAR DATOS Y GUARDAR ====================
            $datos = [
                'pnombre' => $pnombre,
                'snombre' => $snombre ?: null,
                'papellido' => $papellido,
                'sapellido' => $sapellido ?: null,
                'cedula' => $cedula,
                'fecha_nacimiento' => $fechaNac ?: null,
                'id_club' => !empty($_POST['id_club']) ? (int)$_POST['id_club'] : null,
                'id_categoria' => !empty($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : null,
                'foto' => null,
            ];
            
            if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                try { $this->procesarFoto($datos); }
                catch (InvalidArgumentException $e) { $errores['foto'] = $e->getMessage(); }
            }
            
            if (!empty($errores)) {
                $_SESSION['error_toast'] = 'Error: ' . implode(' | ', array_values($errores));
                $atleta = $id > 0 ? (new Atleta())->obtener($id) : [];
                $clubes = (new Club())->todos();
                $categorias = (new Categoria())->todos();
                $documentosExistentes = $id > 0 ? (new Atleta())->documentos($id) : [];
                $this->view('atletas/form', compact('atleta', 'clubes', 'categorias', 'documentosExistentes', 'errores'));
                return;
            }
            
            $model = new Atleta();
            if ($id > 0) {
                $model->actualizar($id, $datos);
                $mensaje = "Atleta actualizado correctamente";
            } else {
                $id = $model->crear($datos);
                $mensaje = "Atleta creado correctamente";
            }
            
            $this->procesarDocumentos($model, $id);
            
            $_SESSION['ok'] = $mensaje;
            $this->redirect('index.php?c=atletas&a=index');
            
        } catch (InvalidArgumentException $e) {
            $_SESSION['error_toast'] = $e->getMessage();
            $atleta = $id > 0 ? (new Atleta())->obtener($id) : [];
            $clubes = (new Club())->todos();
            $categorias = (new Categoria())->todos();
            $documentosExistentes = $id > 0 ? (new Atleta())->documentos($id) : [];
            $errores['general'] = $e->getMessage();
            $this->view('atletas/form', compact('atleta', 'clubes', 'categorias', 'documentosExistentes', 'errores'));
        } catch (Exception $e) {
            error_log("[AtletasController::guardar] " . $e->getMessage());
            $_SESSION['error_toast'] = "Error al guardar: " . $e->getMessage();
            $this->redirect('index.php?c=atletas&a=form' . ($id > 0 ? "&id=$id" : ""));
        }
    }
    
    public function eliminar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de atleta inválido");
            $model = new Atleta();
            if (!$model->obtener($id)) throw new Exception("Atleta no encontrado");
            if ($model->eliminar($id)) $_SESSION['ok'] = "Atleta desactivado correctamente";
            else throw new Exception("No se pudo desactivar el atleta");
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $this->redirect('index.php?c=atletas&a=index');
    }
    
    // ==================== MÉTODOS PRIVADOS ====================
    private function procesarFoto(array &$datos): void {
        $archivo = $_FILES['foto'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS)) {
            throw new InvalidArgumentException("Tipo de foto no permitido. Use: JPG, PNG, GIF o WEBP");
        }
        if ($archivo['size'] > self::MAX_FILE_SIZE) {
            throw new InvalidArgumentException("La foto excede el tamaño máximo de 5MB");
        }
        
        $nombreArchivo = 'atleta_' . uniqid() . '.' . $extension;
        $rutaDestino = __DIR__ . self::UPLOAD_FOTOS_DIR . $nombreArchivo;
        $directorio = dirname($rutaDestino);
        if (!is_dir($directorio)) mkdir($directorio, 0755, true);
        
        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            $datos['foto'] = 'public/uploads/atletas/' . $nombreArchivo;
        } else {
            throw new Exception("Error al subir la foto. Verifica los permisos del directorio.");
        }
    }
    
    private function procesarDocumentos(Atleta $model, int $atletaId): void {
        if (empty($_FILES['documentos']['name'][0])) return;
        
        $archivos = $_FILES['documentos'];
        $subidos = 0;
        
        for ($i = 0; $i < count($archivos['name']); $i++) {
            if (empty($archivos['name'][$i]) || $archivos['error'][$i] !== UPLOAD_ERR_OK) continue;
            
            $nombreOriginal = $archivos['name'][$i];
            $tmpPath = $archivos['tmp_name'][$i];
            $tamaño = $archivos['size'][$i];
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            
            if (!in_array($extension, self::ALLOWED_DOC_EXTENSIONS)) continue;
            if ($tamaño > self::MAX_FILE_SIZE) continue;
            
            $nombreArchivo = 'doc_' . uniqid() . '_' . time() . '.' . $extension;
            $rutaDestino = __DIR__ . self::UPLOAD_DOCS_DIR . $nombreArchivo;
            $directorio = dirname($rutaDestino);
            if (!is_dir($directorio)) mkdir($directorio, 0755, true);
            
            if (move_uploaded_file($tmpPath, $rutaDestino)) {
                $model->agregarDocumento($atletaId, $nombreOriginal, 'public/uploads/documentos/' . $nombreArchivo);
                $subidos++;
            }
        }
        
        if ($subidos > 0) {
            $_SESSION['ok'] = ($_SESSION['ok'] ?? '') . " Se subieron $subidos documento(s)";
        }
    }
}