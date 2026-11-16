<?php
/**
 * Modelo Categoria
 * 
 * Gestiona las categorías de peso del gimnasio de boxeo.
 * Incluye el método original `todos()` usado por otras partes del sistema
 * y los métodos CRUD necesarios para el módulo de administración.
 * 
 * @version 2.0
 */
class Categoria extends Model {
    
    // ==================== PROPIEDADES PRIVADAS ====================
    
    private ?int $id_categoria = null;
    private ?string $nombre_categoria = null;
    private ?float $peso_min = null;
    private ?float $peso_max = null;
    private bool $skipValidation = false;
    
    // ==================== MÉTODO ORIGINAL (NO TOCAR) ====================
    
    /**
     * Devuelve todas las categorías ordenadas por nombre.
     * Usado por otros módulos (atletas, combates, etc.).
     */
    public function todos(): array {
        return $this->db->query("SELECT * FROM categorias ORDER BY nombre_categoria")->fetchAll();
    }
    
    // ==================== GETTERS ====================
    
    public function getIdCategoria(): ?int { return $this->id_categoria; }
    public function getNombreCategoria(): ?string { return $this->nombre_categoria; }
    public function getPesoMin(): ?float { return $this->peso_min; }
    public function getPesoMax(): ?float { return $this->peso_max; }
    
    public function getRango(): string {
        if ($this->peso_min === null && $this->peso_max === null) {
            return 'Sin rango';
        }
        $min = $this->peso_min !== null ? number_format($this->peso_min, 2) : '?';
        $max = $this->peso_max !== null ? number_format($this->peso_max, 2) : '?';
        return "{$min} - {$max} kg";
    }
    
    // ==================== SETTERS CON VALIDACIONES ====================
    
    public function setIdCategoria(?int $id): self {
        if (!$this->skipValidation && $id !== null && $id <= 0) {
            throw new InvalidArgumentException("ID de categoría inválido");
        }
        $this->id_categoria = $id;
        return $this;
    }
    
    public function setNombreCategoria(?string $nombre): self {
        if (!$this->skipValidation) {
            $nombre = trim((string)$nombre);
            if ($nombre === '') {
                throw new InvalidArgumentException("El nombre de la categoría es requerido");
            }
            if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 50) {
                throw new InvalidArgumentException("El nombre debe tener entre 2 y 50 caracteres");
            }
            if (!preg_match('/^[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.]+$/u', $nombre)) {
                throw new InvalidArgumentException("El nombre solo puede contener letras, números, espacios, guiones y puntos");
            }
            $this->nombre_categoria = $nombre;
        } else {
            $this->nombre_categoria = $nombre;
        }
        return $this;
    }
    
    public function setPesoMin($peso): self {
        if (!$this->skipValidation && $peso !== null && $peso !== '') {
            if (!is_numeric($peso)) {
                throw new InvalidArgumentException("El peso mínimo debe ser un número");
            }
            $peso = (float)$peso;
            if ($peso < 0) {
                throw new InvalidArgumentException("El peso mínimo no puede ser negativo");
            }
            if ($peso > 999.99) {
                throw new InvalidArgumentException("El peso mínimo no puede superar 999.99 kg");
            }
            $this->peso_min = round($peso, 2);
        } else {
            $this->peso_min = ($peso === '' || $peso === null) ? null : (float)$peso;
        }
        return $this;
    }
    
    public function setPesoMax($peso): self {
        if (!$this->skipValidation && $peso !== null && $peso !== '') {
            if (!is_numeric($peso)) {
                throw new InvalidArgumentException("El peso máximo debe ser un número");
            }
            $peso = (float)$peso;
            if ($peso < 0) {
                throw new InvalidArgumentException("El peso máximo no puede ser negativo");
            }
            if ($peso > 999.99) {
                throw new InvalidArgumentException("El peso máximo no puede superar 999.99 kg");
            }
            $this->peso_max = round($peso, 2);
        } else {
            $this->peso_max = ($peso === '' || $peso === null) ? null : (float)$peso;
        }
        return $this;
    }
    
    private function validarRangoPesos(): void {
        if ($this->peso_min !== null && $this->peso_max !== null) {
            if ($this->peso_max <= $this->peso_min) {
                throw new InvalidArgumentException("El peso máximo debe ser mayor que el peso mínimo");
            }
        }
    }
    
    public function disableValidation(): self {
        $this->skipValidation = true;
        return $this;
    }
    
    public function enableValidation(): self {
        $this->skipValidation = false;
        return $this;
    }
    
    // ==================== MÉTODOS AUXILIARES ====================
    
    public function loadFromArray(array $data): self {
        $this->disableValidation();
        if (isset($data['id_categoria'])) $this->setIdCategoria((int)$data['id_categoria']);
        if (isset($data['nombre_categoria'])) $this->setNombreCategoria($data['nombre_categoria']);
        if (array_key_exists('peso_min', $data)) $this->setPesoMin($data['peso_min']);
        if (array_key_exists('peso_max', $data)) $this->setPesoMax($data['peso_max']);
        $this->enableValidation();
        return $this;
    }
    
    public function toArray(): array {
        return [
            'id_categoria' => $this->getIdCategoria(),
            'nombre_categoria' => $this->getNombreCategoria(),
            'peso_min' => $this->getPesoMin(),
            'peso_max' => $this->getPesoMax()
        ];
    }
    
    // ==================== MÉTODOS CRUD (NUEVOS) ====================
    
    /**
     * Obtiene una categoría por su ID
     */
    public function obtener(int $id): ?array {
        try {
            $this->disableValidation();
            $stmt = $this->db->prepare("SELECT * FROM categorias WHERE id_categoria = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            if ($result) {
                $this->loadFromArray($result);
            }
            $this->enableValidation();
            return $result ?: null;
        } catch (Exception $e) {
            $this->logError("Error en obtener()", ['id' => $id, 'message' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * Crea una nueva categoría
     */
    public function crear(array $d): int|false {
        try {
            $this->enableValidation();
            $this->setNombreCategoria($d['nombre_categoria'] ?? null)
                 ->setPesoMin($d['peso_min'] ?? null)
                 ->setPesoMax($d['peso_max'] ?? null);
            
            $this->validarRangoPesos();
            $this->verificarNombreUnico();
            
            $stmt = $this->db->prepare("INSERT INTO categorias (nombre_categoria, peso_min, peso_max) VALUES (?, ?, ?)");
            $result = $stmt->execute([
                $this->getNombreCategoria(),
                $this->getPesoMin(),
                $this->getPesoMax()
            ]);
            
            $id = (int)$this->db->lastInsertId();
            $this->setIdCategoria($id);
            $this->logInfo("Categoría creada", ['id' => $id, 'nombre' => $this->getNombreCategoria()]);
            return $result ? $id : false;
        } catch (Exception $e) {
            $this->logError("Error en crear()", ['message' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Actualiza una categoría existente
     */
    public function actualizar(int $id, array $d): bool {
        try {
            $this->enableValidation();
            $this->setIdCategoria($id);
            $this->setNombreCategoria($d['nombre_categoria'] ?? null)
                 ->setPesoMin($d['peso_min'] ?? null)
                 ->setPesoMax($d['peso_max'] ?? null);
            
            $this->validarRangoPesos();
            $this->verificarNombreUnico($id);
            
            $stmt = $this->db->prepare("UPDATE categorias SET nombre_categoria = ?, peso_min = ?, peso_max = ? WHERE id_categoria = ?");
            $result = $stmt->execute([
                $this->getNombreCategoria(),
                $this->getPesoMin(),
                $this->getPesoMax(),
                $id
            ]);
            
            $this->logInfo("Categoría actualizada", ['id' => $id]);
            return $result;
        } catch (Exception $e) {
            $this->logError("Error en actualizar()", ['id' => $id, 'message' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Elimina una categoría (solo si no tiene atletas asociados)
     */
    public function eliminar(int $id): bool {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM atletas WHERE id_categoria = ?");
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                throw new Exception("No se puede eliminar la categoría porque tiene $count atleta(s) asociado(s). Reasígnalos primero.");
            }
            
            $result = $this->db->prepare("DELETE FROM categorias WHERE id_categoria = ?")->execute([$id]);
            if ($result) {
                $this->logInfo("Categoría eliminada", ['id' => $id]);
            }
            return $result;
        } catch (Exception $e) {
            $this->logError("Error en eliminar()", ['id' => $id, 'message' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Verifica que el nombre no esté duplicado
     */
    private function verificarNombreUnico(?int $excluirId = null): void {
        try {
            $sql = "SELECT COUNT(*) FROM categorias WHERE nombre_categoria = ?";
            $params = [$this->getNombreCategoria()];
            if ($excluirId !== null) {
                $sql .= " AND id_categoria != ?";
                $params[] = $excluirId;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            if ($stmt->fetchColumn() > 0) {
                throw new InvalidArgumentException("Ya existe una categoría con ese nombre");
            }
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Exception $e) {
            $this->logError("Error en verificarNombreUnico()", ['message' => $e->getMessage()]);
            throw new Exception("Error al verificar el nombre de la categoría");
        }
    }
    
    /**
     * Cuenta los atletas asociados a una categoría
     */
    public function contarAtletas(int $id): int {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM atletas WHERE id_categoria = ?");
            $stmt->execute([$id]);
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
    
    // ==================== MÉTODOS DE LOGGING ====================
    
    private function logError(string $message, array $context = []): void {
        $log = "[Categoria ERROR] $message";
        if (!empty($context)) {
            $log .= " | " . json_encode($context);
        }
        error_log($log);
    }
    
    private function logInfo(string $message, array $context = []): void {
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            $log = "[Categoria INFO] $message";
            if (!empty($context)) {
                $log .= " | " . json_encode($context);
            }
            error_log($log);
        }
    }
}