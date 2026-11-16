<?php
/**
 * Modelo Entrenador
 * @version 3.1 - Regex estricta de nombres, sin números ni guiones bajos
 */
class Entrenador extends Model {
    
    private ?int $id_entrenador = null;
    private ?string $pnombre_entrenador = null;
    private ?string $snombre_entrenador = null;
    private ?string $apellido_entrenador = null;
    private ?string $sapellido_entrenador = null;
    private ?string $cedula_entrenador = null;
    private ?string $telefono = null;
    private ?string $correo = null;
    private int $activo = 1;
    
    private bool $skipValidation = false;
    
    // ==================== GETTERS ====================
    public function getIdEntrenador(): ?int { return $this->id_entrenador; }
    public function getPnombreEntrenador(): ?string { return $this->pnombre_entrenador; }
    public function getSnombreEntrenador(): ?string { return $this->snombre_entrenador; }
    public function getApellidoEntrenador(): ?string { return $this->apellido_entrenador; }
    public function getSapellidoEntrenador(): ?string { return $this->sapellido_entrenador; }
    public function getCedulaEntrenador(): ?string { return $this->cedula_entrenador; }
    public function getTelefono(): ?string { return $this->telefono; }
    public function getCorreo(): ?string { return $this->correo; }
    public function getActivo(): int { return $this->activo; }
    
    // ==================== SETTERS ====================
    public function setIdEntrenador(?int $id): self {
        if (!$this->skipValidation && $id !== null && $id <= 0) throw new InvalidArgumentException("ID de entrenador inválido");
        $this->id_entrenador = $id; return $this;
    }
    
    public function setPnombreEntrenador(?string $nombre): self {
        if (!$this->skipValidation && !empty($nombre)) {
            $nombre = trim($nombre);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s\'\.\-]+$/u', $nombre)) {
                throw new InvalidArgumentException("El primer nombre solo puede contener letras, espacios, apóstrofes, puntos y guiones");
            }
            if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 50) {
                throw new InvalidArgumentException("El primer nombre debe tener entre 2 y 50 caracteres");
            }
        }
        $this->pnombre_entrenador = $nombre; return $this;
    }
    
    public function setSnombreEntrenador(?string $nombre): self {
        if (!$this->skipValidation && !empty($nombre)) {
            $nombre = trim($nombre);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s\'\.\-]+$/u', $nombre)) {
                throw new InvalidArgumentException("El segundo nombre solo puede contener letras y espacios");
            }
            if (mb_strlen($nombre) > 50) throw new InvalidArgumentException("El segundo nombre no puede superar los 50 caracteres");
        }
        $this->snombre_entrenador = $nombre; return $this;
    }
    
    public function setApellidoEntrenador(?string $apellido): self {
        if (!$this->skipValidation && !empty($apellido)) {
            $apellido = trim($apellido);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s\'\.\-]+$/u', $apellido)) {
                throw new InvalidArgumentException("El primer apellido solo puede contener letras, espacios, apóstrofes, puntos y guiones");
            }
            if (mb_strlen($apellido) < 2 || mb_strlen($apellido) > 50) {
                throw new InvalidArgumentException("El primer apellido debe tener entre 2 y 50 caracteres");
            }
        }
        $this->apellido_entrenador = $apellido; return $this;
    }
    
    public function setSapellidoEntrenador(?string $apellido): self {
        if (!$this->skipValidation && !empty($apellido)) {
            $apellido = trim($apellido);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s\'\.\-]+$/u', $apellido)) {
                throw new InvalidArgumentException("El segundo apellido solo puede contener letras y espacios");
            }
            if (mb_strlen($apellido) > 50) throw new InvalidArgumentException("El segundo apellido no puede superar los 50 caracteres");
        }
        $this->sapellido_entrenador = $apellido; return $this;
    }
    
    public function setCedulaEntrenador(?string $cedula): self {
        if (!$this->skipValidation && !empty($cedula)) {
            $cedula = strtoupper(trim($cedula));
            if (!preg_match('/^[VEJP]?-?[0-9]{6,9}$/', $cedula)) {
                throw new InvalidArgumentException("Formato inválido. Use V-, E-, J-, P- seguido de 6 a 9 dígitos, o solo números");
            }
            if (preg_match('/^([VEJP]?)-?([0-9]{6,9})$/', $cedula, $m)) {
                $letra = $m[1] !== '' ? $m[1] : 'V';
                $cedula = $letra . '-' . $m[2];
            }
            $this->cedula_entrenador = $cedula;
        } else {
            $this->cedula_entrenador = $cedula;
        }
        return $this;
    }
    
    public function setTelefono(?string $telefono): self {
        if (!$this->skipValidation && !empty($telefono)) {
            $telefono = trim($telefono);
            if (!preg_match('/^\+?[\d\s\-\(\)\.]+$/', $telefono)) {
                throw new InvalidArgumentException("El teléfono solo puede contener números, +, -, (), espacios y puntos");
            }
            $soloDigitos = preg_replace('/\D/', '', $telefono);
            if (strlen($soloDigitos) < 7) throw new InvalidArgumentException("El teléfono debe tener al menos 7 dígitos");
            if (strlen($soloDigitos) > 20) throw new InvalidArgumentException("El teléfono no puede tener más de 20 dígitos");
            $this->telefono = preg_replace('/\s+/', ' ', $telefono);
        } else {
            $this->telefono = $telefono;
        }
        return $this;
    }
    
    public function setCorreo(?string $correo): self {
        if (!$this->skipValidation && !empty($correo)) {
            $correo = trim($correo);
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException("Correo electrónico inválido");
            $this->correo = $correo;
        } else {
            $this->correo = $correo;
        }
        return $this;
    }
    
    public function setActivo(int $activo): self { $this->activo = $activo ? 1 : 0; return $this; }
    
    // ==================== CONTROL DE VALIDACIÓN ====================
    public function disableValidation(): self { $this->skipValidation = true; return $this; }
    public function enableValidation(): self { $this->skipValidation = false; return $this; }
    
    // ==================== AUXILIARES ====================
    public function loadFromArray(array $data): self {
        $this->disableValidation();
        if (isset($data['id_entrenador'])) $this->setIdEntrenador((int)$data['id_entrenador']);
        if (isset($data['pnombre_entrenador'])) $this->setPnombreEntrenador($data['pnombre_entrenador']);
        if (isset($data['snombre_entrenador'])) $this->setSnombreEntrenador($data['snombre_entrenador']);
        if (isset($data['apellido_entrenador'])) $this->setApellidoEntrenador($data['apellido_entrenador']);
        if (isset($data['sapellido_entrenador'])) $this->setSapellidoEntrenador($data['sapellido_entrenador']);
        if (isset($data['cedula_entrenador'])) $this->setCedulaEntrenador($data['cedula_entrenador']);
        if (isset($data['telefono'])) $this->setTelefono($data['telefono']);
        if (isset($data['correo'])) $this->setCorreo($data['correo']);
        if (isset($data['activo'])) $this->setActivo((int)$data['activo']);
        $this->enableValidation();
        return $this;
    }
    
    public function toArray(): array {
        return [
            'id_entrenador' => $this->getIdEntrenador(),
            'pnombre_entrenador' => $this->getPnombreEntrenador(),
            'snombre_entrenador' => $this->getSnombreEntrenador(),
            'apellido_entrenador' => $this->getApellidoEntrenador(),
            'sapellido_entrenador' => $this->getSapellidoEntrenador(),
            'cedula_entrenador' => $this->getCedulaEntrenador(),
            'telefono' => $this->getTelefono(),
            'correo' => $this->getCorreo(),
            'activo' => $this->getActivo()
        ];
    }
    
    public function getNombreCompleto(): string {
        return trim($this->getPnombreEntrenador() . ' ' . ($this->getSnombreEntrenador() ?? '') . ' ' . 
                     $this->getApellidoEntrenador() . ' ' . ($this->getSapellidoEntrenador() ?? ''));
    }
    
    // ==================== CRUD ====================
    public function todos(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM entrenadores ORDER BY id_entrenador DESC");
            return $stmt->fetchAll();
        } catch (Exception $e) { $this->logError("Error en todos()", ['message' => $e->getMessage()]); return []; }
    }
    
    public function obtener(int $id): ?array {
        try {
            $this->disableValidation();
            $stmt = $this->db->prepare("SELECT * FROM entrenadores WHERE id_entrenador = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            if ($result) $this->loadFromArray($result);
            $this->enableValidation();
            return $result ?: null;
        } catch (Exception $e) { $this->logError("Error en obtener()", ['id' => $id, 'message' => $e->getMessage()]); return null; }
    }
    
    public function crear(array $d): int|false {
        try {
            $this->enableValidation();
            $this->setPnombreEntrenador($d['pnombre_entrenador'] ?? null)
                 ->setSnombreEntrenador($d['snombre_entrenador'] ?? null)
                 ->setApellidoEntrenador($d['apellido_entrenador'] ?? null)
                 ->setSapellidoEntrenador($d['sapellido_entrenador'] ?? null)
                 ->setCedulaEntrenador($d['cedula_entrenador'] ?? null)
                 ->setTelefono($d['telefono'] ?? null)
                 ->setCorreo($d['correo'] ?? null)
                 ->setActivo($d['activo'] ?? 1);
            $stmt = $this->db->prepare("INSERT INTO entrenadores 
                (pnombre_entrenador, snombre_entrenador, apellido_entrenador, sapellido_entrenador, cedula_entrenador, telefono, correo, activo) 
                VALUES (?,?,?,?,?,?,?,?)");
            $result = $stmt->execute([
                $this->getPnombreEntrenador(), $this->getSnombreEntrenador(),
                $this->getApellidoEntrenador(), $this->getSapellidoEntrenador(),
                $this->getCedulaEntrenador(), $this->getTelefono(),
                $this->getCorreo(), $this->getActivo()
            ]);
            $id = (int)$this->db->lastInsertId();
            $this->setIdEntrenador($id);
            return $result ? $id : false;
        } catch (Exception $e) { $this->logError("Error en crear()", ['message' => $e->getMessage()]); throw $e; }
    }
    
    public function actualizar(int $id, array $d): bool {
        try {
            $this->enableValidation();
            $this->setIdEntrenador($id);
            $this->setPnombreEntrenador($d['pnombre_entrenador'] ?? null)
                 ->setSnombreEntrenador($d['snombre_entrenador'] ?? null)
                 ->setApellidoEntrenador($d['apellido_entrenador'] ?? null)
                 ->setSapellidoEntrenador($d['sapellido_entrenador'] ?? null)
                 ->setCedulaEntrenador($d['cedula_entrenador'] ?? null)
                 ->setTelefono($d['telefono'] ?? null)
                 ->setCorreo($d['correo'] ?? null)
                 ->setActivo($d['activo'] ?? 1);
            $stmt = $this->db->prepare("UPDATE entrenadores SET 
                pnombre_entrenador = ?, snombre_entrenador = ?, apellido_entrenador = ?,
                sapellido_entrenador = ?, cedula_entrenador = ?, telefono = ?, correo = ?, activo = ?
                WHERE id_entrenador = ?");
            return $stmt->execute([
                $this->getPnombreEntrenador(), $this->getSnombreEntrenador(),
                $this->getApellidoEntrenador(), $this->getSapellidoEntrenador(),
                $this->getCedulaEntrenador(), $this->getTelefono(),
                $this->getCorreo(), $this->getActivo(), $id
            ]);
        } catch (Exception $e) { $this->logError("Error en actualizar()", ['id' => $id, 'message' => $e->getMessage()]); throw $e; }
    }
    
    public function eliminar(int $id): bool {
        try {
            $this->db->prepare("DELETE FROM entrenador_club WHERE id_entrenador=?")->execute([$id]);
            return $this->db->prepare("DELETE FROM entrenadores WHERE id_entrenador=?")->execute([$id]);
        } catch (Exception $e) { $this->logError("Error en eliminar()", ['id' => $id, 'message' => $e->getMessage()]); return false; }
    }
    
    public function asignarClubes(int $id, array $clubes): void {
        try {
            $this->db->prepare("DELETE FROM entrenador_club WHERE id_entrenador=?")->execute([$id]);
            $stmt = $this->db->prepare("INSERT IGNORE INTO entrenador_club (id_entrenador, id_club) VALUES (?,?)");
            foreach ($clubes as $idClub) $stmt->execute([$id, (int)$idClub]);
        } catch (Exception $e) { $this->logError("Error en asignarClubes()", ['id' => $id, 'message' => $e->getMessage()]); }
    }
    
    public function clubesDe(int $id): array {
        try {
            $stmt = $this->db->prepare("SELECT id_club FROM entrenador_club WHERE id_entrenador=?");
            $stmt->execute([$id]);
            return array_column($stmt->fetchAll(), 'id_club');
        } catch (Exception $e) { $this->logError("Error en clubesDe()", ['id' => $id, 'message' => $e->getMessage()]); return []; }
    }
    
    public function existeCedula(string $cedula, ?int $excluirId = null): bool {
        try {
            $normalizada = $this->normalizarCedula($cedula);
            $sql = "SELECT cedula_entrenador FROM entrenadores";
            $params = [];
            if ($excluirId !== null) { $sql .= " WHERE id_entrenador != ?"; $params[] = $excluirId; }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $existentes = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($existentes as $existente) {
                if ($this->normalizarCedula((string)$existente) === $normalizada) return true;
            }
            return false;
        } catch (Exception $e) { $this->logError("Error en existeCedula()", ['message' => $e->getMessage()]); return false; }
    }
    
    private function normalizarCedula(string $cedula): string {
        $c = strtoupper(preg_replace('/[\s\-]/', '', trim($cedula)));
        if (preg_match('/^[0-9]+$/', $c)) $c = 'V' . $c;
        return $c;
    }
    
    private function logError(string $message, array $context = []): void {
        $log = "[Entrenador ERROR] $message";
        if (!empty($context)) $log .= " | " . json_encode($context);
        error_log($log);
    }
}