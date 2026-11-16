<?php
/**
 * Modelo Usuario
 * 
 * @version 3.0 - Validaciones completas + protección último admin
 */
class Usuario extends Model {
    
    private ?int $id_usuario = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?string $rol = null;
    private int $activo = 1;
    
    private bool $skipValidation = false;
    
    public const ROLES = ['Administrador', 'Entrenador', 'Consulta'];
    
    // ==================== GETTERS ====================
    
    public function getIdUsuario(): ?int { return $this->id_usuario; }
    public function getUsername(): ?string { return $this->username; }
    public function getRol(): ?string { return $this->rol; }
    public function getActivo(): int { return $this->activo; }
    
    /** Nunca devolver la contraseña por seguridad */
    public function getPassword(): ?string { return null; }
    
    // ==================== SETTERS ====================
    
    public function setIdUsuario(?int $id): self {
        if (!$this->skipValidation && $id !== null && $id <= 0) {
            throw new InvalidArgumentException("ID de usuario inválido");
        }
        $this->id_usuario = $id;
        return $this;
    }
    
    public function setUsername(?string $username, ?int $excludeId = null): self {
        if (!$this->skipValidation && !empty($username)) {
            $username = trim($username);
            if (!preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
                throw new InvalidArgumentException("El usuario debe tener entre 3 y 50 caracteres (letras, números, guión bajo)");
            }
            if (!$this->isUsernameUnique($username, $excludeId)) {
                throw new InvalidArgumentException("El nombre de usuario ya está en uso");
            }
            $this->username = $username;
        } else {
            $this->username = $username;
        }
        return $this;
    }
    
    /**
     * Establece la contraseña (SIEMPRE hasheada)
     */
    public function setPassword(?string $password): self {
        if (!$this->skipValidation && !empty($password)) {
            // Validar fortaleza
            if (strlen($password) < 8) {
                throw new InvalidArgumentException("La contraseña debe tener al menos 8 caracteres");
            }
            if (strlen($password) > 72) {
                throw new InvalidArgumentException("La contraseña no puede tener más de 72 caracteres");
            }
            if (!preg_match('/[A-Z]/', $password)) {
                throw new InvalidArgumentException("La contraseña debe contener al menos una letra mayúscula");
            }
            if (!preg_match('/[a-z]/', $password)) {
                throw new InvalidArgumentException("La contraseña debe contener al menos una letra minúscula");
            }
            if (!preg_match('/[0-9]/', $password)) {
                throw new InvalidArgumentException("La contraseña debe contener al menos un número");
            }
            if ($this->username && strtolower($password) === strtolower($this->username)) {
                throw new InvalidArgumentException("La contraseña no puede ser igual al nombre de usuario");
            }
            
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $this->password = $password;
        }
        return $this;
    }
    
    public function setRol(?string $rol): self {
        if (!$this->skipValidation && !empty($rol)) {
            if (!in_array($rol, self::ROLES)) {
                throw new InvalidArgumentException("Rol inválido. Opciones: " . implode(', ', self::ROLES));
            }
            $this->rol = $rol;
        } else {
            $this->rol = $rol;
        }
        return $this;
    }
    
    public function setActivo(int $activo): self {
        $this->activo = $activo ? 1 : 0;
        return $this;
    }
    
    // ==================== VALIDACIONES PÚBLICAS ====================
    
    /**
     * Verifica si un username ya existe (wrapper público)
     */
    public function existeUsername(string $username, ?int $excluirId = null): bool {
        return !$this->isUsernameUnique(trim($username), $excluirId);
    }
    
    /**
     * Verifica si un username ya existe (privado)
     */
    private function isUsernameUnique(string $username, ?int $excludeId = null): bool {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE LOWER(username) = LOWER(?)";
            $params = [$username];
            if ($excludeId !== null) {
                $sql .= " AND id_usuario != ?";
                $params[] = $excludeId;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn() == 0;
        } catch (Exception $e) {
            $this->logError("Error en isUsernameUnique()", ['message' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Cuenta los administradores activos
     */
    public function contarAdminsActivos(?int $excluirId = null): int {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE rol = 'Administrador' AND activo = 1";
            $params = [];
            if ($excluirId !== null) {
                $sql .= " AND id_usuario != ?";
                $params[] = $excluirId;
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            $this->logError("Error en contarAdminsActivos()", ['message' => $e->getMessage()]);
            return 0;
        }
    }
    
    /**
     * Verifica si un usuario es el último admin activo
     */
    public function esUltimoAdmin(int $id): bool {
        try {
            $usuario = $this->obtener($id);
            if (!$usuario) return false;
            if ($usuario['rol'] !== 'Administrador') return false;
            if ((int)$usuario['activo'] !== 1) return false;
            return $this->contarAdminsActivos($id) === 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    // ==================== CONTROL ====================
    
    public function disableValidation(): self {
        $this->skipValidation = true;
        return $this;
    }
    
    public function enableValidation(): self {
        $this->skipValidation = false;
        return $this;
    }
    
    // ==================== AUTENTICACIÓN ====================
    
    public function autenticar(string $username, string $password): array|false {
        try {
            $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $usuario = $stmt->fetch();
            
            if (!$usuario) {
                $this->logError("Login fallido", ['username' => $username, 'razon' => 'no existe']);
                return false;
            }
            
            if ((int)$usuario['activo'] === 0) {
                $this->logError("Login fallido", ['username' => $username, 'razon' => 'inactivo']);
                return false;
            }
            
            if (!password_verify($password, $usuario['password'])) {
                $this->logError("Login fallido", ['username' => $username, 'razon' => 'contraseña incorrecta']);
                return false;
            }
            
            unset($usuario['password']);
            $this->logInfo("Login exitoso", ['id' => $usuario['id_usuario'], 'username' => $username]);
            return $usuario;
        } catch (Exception $e) {
            $this->logError("Error en autenticar()", ['message' => $e->getMessage()]);
            return false;
        }
    }
    
    // ==================== AUXILIARES ====================
    
    public function loadFromArray(array $data): self {
        $this->disableValidation();
        
        if (isset($data['id_usuario'])) $this->setIdUsuario((int)$data['id_usuario']);
        if (isset($data['username'])) $this->setUsername($data['username'], $this->getIdUsuario());
        if (isset($data['rol'])) $this->setRol($data['rol']);
        if (isset($data['activo'])) $this->setActivo((int)$data['activo']);
        
        $this->enableValidation();
        return $this;
    }
    
    public function toArray(): array {
        return [
            'id_usuario' => $this->getIdUsuario(),
            'username' => $this->getUsername(),
            'rol' => $this->getRol(),
            'activo' => $this->getActivo()
        ];
    }
    
    // ==================== CRUD ====================
    
    public function todos(): array {
        try {
            $sql = "SELECT id_usuario, username, rol, activo FROM usuarios ORDER BY id_usuario DESC";
            return $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            $this->logError("Error en todos()", ['message' => $e->getMessage()]);
            return [];
        }
    }
    
    public function obtener(int $id): ?array {
        try {
            $this->disableValidation();
            
            $sql = "SELECT id_usuario, username, rol, activo FROM usuarios WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            
            if ($result) $this->loadFromArray($result);
            
            $this->enableValidation();
            return $result ?: null;
        } catch (Exception $e) {
            $this->logError("Error en obtener()", ['id' => $id, 'message' => $e->getMessage()]);
            return null;
        }
    }
    
    public function crear(array $d): int|false {
        try {
            $this->enableValidation();
            
            $this->setUsername($d['username'] ?? null, null)
                 ->setPassword($d['password'] ?? null)
                 ->setRol($d['rol'] ?? 'Consulta')
                 ->setActivo($d['activo'] ?? 1);
            
            $stmt = $this->db->prepare("INSERT INTO usuarios (username, password, rol, activo) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([
                $this->getUsername(),
                $this->password,
                $this->getRol(),
                $this->getActivo()
            ]);
            
            $id = (int)$this->db->lastInsertId();
            $this->setIdUsuario($id);
            $this->logInfo("Usuario creado", ['id' => $id, 'username' => $this->getUsername()]);
            return $result ? $id : false;
        } catch (Exception $e) {
            $this->logError("Error en crear()", ['message' => $e->getMessage()]);
            throw $e;
        }
    }
    
    public function actualizar(int $id, array $d): bool {
        try {
            $this->enableValidation();
            $this->setIdUsuario($id);
            
            $this->setUsername($d['username'] ?? null, $id)
                 ->setRol($d['rol'] ?? 'Consulta')
                 ->setActivo($d['activo'] ?? 1);
            
            if (!empty($d['password'])) {
                $this->setPassword($d['password']);
                $sql = "UPDATE usuarios SET username = ?, password = ?, rol = ?, activo = ? WHERE id_usuario = ?";
                $params = [$this->getUsername(), $this->password, $this->getRol(), $this->getActivo(), $id];
            } else {
                $sql = "UPDATE usuarios SET username = ?, rol = ?, activo = ? WHERE id_usuario = ?";
                $params = [$this->getUsername(), $this->getRol(), $this->getActivo(), $id];
            }
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            $this->logInfo("Usuario actualizado", ['id' => $id]);
            return $result;
        } catch (Exception $e) {
            $this->logError("Error en actualizar()", ['id' => $id, 'message' => $e->getMessage()]);
            throw $e;
        }
    }
    
    public function eliminar(int $id): bool {
        try {
            $result = $this->db->prepare("DELETE FROM usuarios WHERE id_usuario = ?")->execute([$id]);
            if ($result) $this->logInfo("Usuario eliminado", ['id' => $id]);
            return $result;
        } catch (Exception $e) {
            $this->logError("Error en eliminar()", ['id' => $id, 'message' => $e->getMessage()]);
            return false;
        }
    }
    
    public function desactivar(int $id): bool {
        try {
            $result = $this->db->prepare("UPDATE usuarios SET activo = 0 WHERE id_usuario = ?")->execute([$id]);
            if ($result) $this->logInfo("Usuario desactivado", ['id' => $id]);
            return $result;
        } catch (Exception $e) {
            $this->logError("Error en desactivar()", ['id' => $id, 'message' => $e->getMessage()]);
            return false;
        }
    }
    
    public function activar(int $id): bool {
        try {
            $result = $this->db->prepare("UPDATE usuarios SET activo = 1 WHERE id_usuario = ?")->execute([$id]);
            if ($result) $this->logInfo("Usuario activado", ['id' => $id]);
            return $result;
        } catch (Exception $e) {
            $this->logError("Error en activar()", ['id' => $id, 'message' => $e->getMessage()]);
            return false;
        }
    }
    
    // ==================== LOGGING ====================
    
    private function logError(string $message, array $context = []): void {
        $log = "[Usuario ERROR] $message";
        if (!empty($context)) $log .= " | " . json_encode($context);
        error_log($log);
    }
    
    private function logInfo(string $message, array $context = []): void {
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            $log = "[Usuario INFO] $message";
            if (!empty($context)) $log .= " | " . json_encode($context);
            error_log($log);
        }
    }
}