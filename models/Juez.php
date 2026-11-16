<?php
/**
 * Modelo Juez
 * @version 1.1 - Regex estricta y métodos activar/desactivar
 */
class Juez extends Model {
    
    private ?int $id_juez = null;
    private ?string $pnombre = null;
    private ?string $papellido = null;
    private ?string $cedula = null;
    private int $activo = 1;
    private bool $skipValidation = false;
    
    // ==================== GETTERS ====================
    public function getIdJuez(): ?int { return $this->id_juez; }
    public function getPnombre(): ?string { return $this->pnombre; }
    public function getPapellido(): ?string { return $this->papellido; }
    public function getCedula(): ?string { return $this->cedula; }
    public function getActivo(): int { return $this->activo; }
    public function getNombreCompleto(): string { return trim($this->getPnombre() . ' ' . $this->getPapellido()); }
    
    // ==================== SETTERS ====================
    public function setIdJuez(?int $id): self {
        if (!$this->skipValidation && $id !== null && $id <= 0) throw new InvalidArgumentException("ID de juez inválido");
        $this->id_juez = $id; return $this;
    }
    
    public function setPnombre(?string $nombre): self {
        if (!$this->skipValidation && !empty($nombre)) {
            $nombre = trim($nombre);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $nombre)) {
                throw new InvalidArgumentException("El nombre solo puede contener letras (2-50 caracteres)");
            }
        }
        $this->pnombre = $nombre; return $this;
    }
    
    public function setPapellido(?string $apellido): self {
        if (!$this->skipValidation && !empty($apellido)) {
            $apellido = trim($apellido);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $apellido)) {
                throw new InvalidArgumentException("El apellido solo puede contener letras (2-50 caracteres)");
            }
        }
        $this->papellido = $apellido; return $this;
    }
    
    public function setCedula(?string $cedula): self {
        if (!$this->skipValidation && !empty($cedula)) {
            $cedula = strtoupper(trim($cedula));
            if (!preg_match('/^[VEJP]?-?[0-9]{6,9}$/', $cedula)) {
                throw new InvalidArgumentException("Formato inválido. Use V-, E-, J-, P- seguido de 6 a 9 dígitos, o solo números");
            }
            if (preg_match('/^([VEJP]?)-?([0-9]{6,9})$/', $cedula, $m)) {
                $letra = $m[1] !== '' ? $m[1] : 'V';
                $cedula = $letra . '-' . $m[2];
            }
        }
        $this->cedula = $cedula; return $this;
    }
    
    public function setActivo(int $activo): self { $this->activo = $activo ? 1 : 0; return $this; }
    public function disableValidation(): self { $this->skipValidation = true; return $this; }
    public function enableValidation(): self { $this->skipValidation = false; return $this; }
    
    // ==================== AUXILIARES ====================
    public function loadFromArray(array $data): self {
        $this->disableValidation();
        if (isset($data['id_juez'])) $this->setIdJuez((int)$data['id_juez']);
        if (isset($data['pnombre'])) $this->setPnombre($data['pnombre']);
        if (isset($data['papellido'])) $this->setPapellido($data['papellido']);
        if (isset($data['cedula'])) $this->setCedula($data['cedula']);
        if (isset($data['activo'])) $this->setActivo((int)$data['activo']);
        $this->enableValidation();
        return $this;
    }
    
    public function toArray(): array {
        return [
            'id_juez' => $this->getIdJuez(),
            'pnombre' => $this->getPnombre(),
            'papellido' => $this->getPapellido(),
            'cedula' => $this->getCedula(),
            'activo' => $this->getActivo()
        ];
    }
    
    // ==================== CRUD ====================
    public function todos(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM jueces WHERE activo = 1 ORDER BY papellido, pnombre");
            return $stmt->fetchAll();
        } catch (Exception $e) { $this->logError("Error en todos()", ['message' => $e->getMessage()]); return []; }
    }
    
    public function todosConInactivos(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM jueces ORDER BY activo DESC, papellido, pnombre");
            return $stmt->fetchAll();
        } catch (Exception $e) { $this->logError("Error en todosConInactivos()", ['message' => $e->getMessage()]); return []; }
    }
    
    public function obtener(int $id): ?array {
        try {
            $this->disableValidation();
            $stmt = $this->db->prepare("SELECT * FROM jueces WHERE id_juez = ?");
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
            $this->setPnombre($d['pnombre'] ?? null)
                 ->setPapellido($d['papellido'] ?? null)
                 ->setCedula($d['cedula'] ?? null)
                 ->setActivo($d['activo'] ?? 1);
            $stmt = $this->db->prepare("INSERT INTO jueces (pnombre, papellido, cedula, activo) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([
                $this->getPnombre(), $this->getPapellido(), $this->getCedula(), $this->getActivo()
            ]);
            $id = (int)$this->db->lastInsertId();
            $this->setIdJuez($id);
            return $result ? $id : false;
        } catch (Exception $e) { $this->logError("Error en crear()", ['message' => $e->getMessage()]); throw $e; }
    }
    
    public function actualizar(int $id, array $d): bool {
        try {
            $this->enableValidation();
            $this->setIdJuez($id);
            $this->setPnombre($d['pnombre'] ?? null)
                 ->setPapellido($d['papellido'] ?? null)
                 ->setCedula($d['cedula'] ?? null)
                 ->setActivo($d['activo'] ?? 1);
            $stmt = $this->db->prepare("UPDATE jueces SET pnombre = ?, papellido = ?, cedula = ?, activo = ? WHERE id_juez = ?");
            return $stmt->execute([
                $this->getPnombre(), $this->getPapellido(), $this->getCedula(), $this->getActivo(), $id
            ]);
        } catch (Exception $e) { $this->logError("Error en actualizar()", ['id' => $id, 'message' => $e->getMessage()]); throw $e; }
    }
    
    public function eliminar(int $id): bool {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM tarjetas_puntuacion WHERE id_juez = ?");
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                throw new Exception("No se puede eliminar el juez porque tiene $count tarjetas de puntuación asociadas. Desactívelo en su lugar.");
            }
            return $this->db->prepare("DELETE FROM jueces WHERE id_juez = ?")->execute([$id]);
        } catch (Exception $e) { $this->logError("Error en eliminar()", ['id' => $id, 'message' => $e->getMessage()]); throw $e; }
    }
    
    public function activar(int $id): bool {
        try { return $this->db->prepare("UPDATE jueces SET activo = 1 WHERE id_juez = ?")->execute([$id]); }
        catch (Exception $e) { $this->logError("Error en activar()", ['id' => $id, 'message' => $e->getMessage()]); return false; }
    }
    
    public function desactivar(int $id): bool {
        try { return $this->db->prepare("UPDATE jueces SET activo = 0 WHERE id_juez = ?")->execute([$id]); }
        catch (Exception $e) { $this->logError("Error en desactivar()", ['id' => $id, 'message' => $e->getMessage()]); return false; }
    }
    
    public function existeCedula(string $cedula, ?int $excluirId = null): bool {
        try {
            $normalizada = $this->normalizarCedula($cedula);
            $sql = "SELECT cedula FROM jueces";
            $params = [];
            if ($excluirId !== null) { $sql .= " WHERE id_juez != ?"; $params[] = $excluirId; }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $existentes = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($existentes as $existente) {
                if ($this->normalizarCedula((string)$existente) === $normalizada) return true;
            }
            return false;
        } catch (Exception $e) { $this->logError("Error en existeCedula()", ['message' => $e->getMessage()]); return false; }
    }
    
    public function existeCedulaNormalizada(string $cedulaNormalizada, ?int $excluirId = null): bool {
        return $this->existeCedula($cedulaNormalizada, $excluirId);
    }
    
    private function normalizarCedula(string $cedula): string {
        $c = strtoupper(preg_replace('/[\s\-]/', '', trim($cedula)));
        if (preg_match('/^[0-9]+$/', $c)) $c = 'V' . $c;
        return $c;
    }
    
    private function logError(string $message, array $context = []): void {
        $log = "[Juez ERROR] $message";
        if (!empty($context)) $log .= " | " . json_encode($context);
        error_log($log);
    }
}