<?php
/**
 * Controlador de Categorías
 * @version 2.0 - Toasts, validaciones con Regex, sin redirect loop
 */
class CategoriasController extends Controller {
    
    public function index(): void {
        try {
            $this->requireRol(['Administrador']);
            $categorias = (new Categoria())->todos();
            $modelo = new Categoria();
            $atletasPorCategoria = [];
            foreach ($categorias as $c) {
                $atletasPorCategoria[$c['id_categoria']] = $modelo->contarAtletas((int)$c['id_categoria']);
            }
            $this->view('categorias/index', compact('categorias', 'atletasPorCategoria'));
        } catch (Exception $e) {
            error_log("[CategoriasController::index] " . $e->getMessage());
            $_SESSION['error'] = "Error al cargar la lista de categorías";
            $this->redirect('index.php?c=dashboard&a=index');
        }
    }
    
    public function form(): void {
        try {
            $this->requireRol(['Administrador']);
            $id = (int)($_GET['id'] ?? 0);
            $categoria = null;
            $errores = [];
            $old = [];
            
            if ($id > 0) {
                $categoria = (new Categoria())->obtener($id);
                if (!$categoria) {
                    $_SESSION['error'] = "Categoría no encontrada";
                    $this->redirect('index.php?c=categorias&a=index');
                    return;
                }
            }
            
            $this->view('categorias/form', compact('categoria', 'errores', 'old'));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('index.php?c=categorias&a=index');
        }
    }
    
    public function guardar(): void {
        $this->requireRol(['Administrador']);
        $id = (int)($_POST['id'] ?? 0);
        $errores = [];
        $old = $_POST;
        
        try {
            // ==================== VALIDACIONES CON REGEX ====================
            
            // --- Nombre ---
            $nombre = trim($_POST['nombre_categoria'] ?? '');
            if ($nombre === '') {
                $errores['nombre_categoria'] = 'El nombre de la categoría es obligatorio';
            } elseif (mb_strlen($nombre) < 2) {
                $errores['nombre_categoria'] = 'El nombre debe tener al menos 2 caracteres';
            } elseif (mb_strlen($nombre) > 50) {
                $errores['nombre_categoria'] = 'El nombre no puede superar los 50 caracteres';
            } elseif (!preg_match('/^[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.]+$/u', $nombre)) {
                $errores['nombre_categoria'] = 'Solo letras, números, espacios, guiones y puntos';
            }
            
            // --- Pesos ---
            $pesoMin = ($_POST['peso_min'] ?? '') !== '' ? $_POST['peso_min'] : null;
            $pesoMax = ($_POST['peso_max'] ?? '') !== '' ? $_POST['peso_max'] : null;
            
            if ($pesoMin !== null && !is_numeric($pesoMin)) {
                $errores['peso_min'] = 'El peso mínimo debe ser un número';
            } elseif ($pesoMin !== null && (float)$pesoMin < 0) {
                $errores['peso_min'] = 'El peso mínimo no puede ser negativo';
            } elseif ($pesoMin !== null && (float)$pesoMin > 999.99) {
                $errores['peso_min'] = 'El peso mínimo no puede superar 999.99 kg';
            }
            
            if ($pesoMax !== null && !is_numeric($pesoMax)) {
                $errores['peso_max'] = 'El peso máximo debe ser un número';
            } elseif ($pesoMax !== null && (float)$pesoMax < 0) {
                $errores['peso_max'] = 'El peso máximo no puede ser negativo';
            } elseif ($pesoMax !== null && (float)$pesoMax > 999.99) {
                $errores['peso_max'] = 'El peso máximo no puede superar 999.99 kg';
            }
            
            if ($pesoMin !== null && $pesoMax !== null && (float)$pesoMax <= (float)$pesoMin) {
                $errores['peso_max'] = 'El peso máximo debe ser mayor que el peso mínimo';
            }
            
            // ==================== SI HAY ERRORES, MOSTRAR TOAST Y RE-RENDERIZAR ====================
            if (!empty($errores)) {
                $_SESSION['error_toast'] = 'Error: ' . implode(' | ', array_values($errores));
                $categoria = $old;
                $this->view('categorias/form', compact('categoria', 'errores', 'old'));
                return;
            }
            
            // ==================== GUARDAR ====================
            $datos = [
                'nombre_categoria' => $nombre,
                'peso_min' => $pesoMin,
                'peso_max' => $pesoMax,
            ];
            
            $m = new Categoria();
            if ($id > 0) {
                $m->actualizar($id, $datos);
                $mensaje = "Categoría actualizada correctamente";
            } else {
                $m->crear($datos);
                $mensaje = "Categoría creada correctamente";
            }
            
            $_SESSION['ok'] = $mensaje;
            $this->redirect('index.php?c=categorias&a=index');
            
        } catch (InvalidArgumentException $e) {
            $_SESSION['error_toast'] = $e->getMessage();
            $errores['general'] = $e->getMessage();
            $categoria = $old;
            $this->view('categorias/form', compact('categoria', 'errores', 'old'));
        } catch (Exception $e) {
            error_log("[CategoriasController::guardar] " . $e->getMessage());
            $_SESSION['error_toast'] = "Error al guardar: " . $e->getMessage();
            $errores['general'] = "Error al guardar: " . $e->getMessage();
            $categoria = $old;
            $this->view('categorias/form', compact('categoria', 'errores', 'old'));
        }
    }
    
    public function eliminar(): void {
        $this->requireRol(['Administrador']);
        try {
            $id = (int)($_GET['id'] ?? 0);
            if ($id <= 0) throw new InvalidArgumentException("ID de categoría inválido");
            $m = new Categoria();
            if (!$m->obtener($id)) throw new Exception("Categoría no encontrada");
            if ($m->eliminar($id)) $_SESSION['ok'] = "Categoría eliminada correctamente";
            else $_SESSION['error'] = "No se pudo eliminar la categoría";
        } catch (Exception $e) { $_SESSION['error'] = $e->getMessage(); }
        $this->redirect('index.php?c=categorias&a=index');
    }
}