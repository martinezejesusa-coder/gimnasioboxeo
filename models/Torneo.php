<?php
/**
 * Modelo Torneo
 * @version 4.0 - Corregido getLlaves() para usar vista_atletas y calcular nivel en PHP
 */
class Torneo extends Model {
    
    private ?int $id_torneo = null;
    private ?string $nombre_torneo = null;
    private ?string $fecha_inicio = null;
    private ?string $fecha_fin = null;
    private ?string $lugar = null;
    
    private bool $skipValidation = false;
    
    // ==================== GETTERS ====================
    public function getIdTorneo(): ?int { return $this->id_torneo; }
    public function getNombreTorneo(): ?string { return $this->nombre_torneo; }
    public function getFechaInicio(): ?string { return $this->fecha_inicio; }
    public function getFechaFin(): ?string { return $this->fecha_fin; }
    public function getLugar(): ?string { return $this->lugar; }
    
    // ==================== SETTERS ====================
    public function setIdTorneo(?int $id): self {
        if (!$this->skipValidation && $id !== null && $id <= 0) throw new InvalidArgumentException("ID de torneo inválido");
        $this->id_torneo = $id; return $this;
    }
    
    public function setNombreTorneo(?string $nombre): self {
        if (!$this->skipValidation && !empty($nombre)) {
            $nombre = trim($nombre);
            if (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 100) throw new InvalidArgumentException("El nombre del torneo debe tener entre 3 y 100 caracteres");
            if (!preg_match('/^[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.,&\(\)]+$/u', $nombre)) throw new InvalidArgumentException("El nombre solo puede contener letras, números, espacios y símbolos básicos");
        }
        $this->nombre_torneo = $nombre; return $this;
    }
    
    public function setFechaInicio(?string $fecha): self {
        if (!$this->skipValidation && !empty($fecha)) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) throw new InvalidArgumentException("Formato de fecha inválido. Use YYYY-MM-DD");
        }
        $this->fecha_inicio = $fecha; return $this;
    }
    
    public function setFechaFin(?string $fecha): self {
        if (!$this->skipValidation && !empty($fecha)) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) throw new InvalidArgumentException("Formato de fecha inválido. Use YYYY-MM-DD");
        }
        $this->fecha_fin = $fecha; return $this;
    }
    
    public function setLugar(?string $lugar): self {
        if (!$this->skipValidation && !empty($lugar)) {
            $lugar = trim($lugar);
            if (mb_strlen($lugar) > 255) throw new InvalidArgumentException("El lugar no puede exceder los 255 caracteres");
        }
        $this->lugar = $lugar; return $this;
    }
    
    private function validarRangoFechas(): void {
        if ($this->fecha_inicio && $this->fecha_fin) {
            if ($this->fecha_fin < $this->fecha_inicio) throw new InvalidArgumentException("La fecha de fin no puede ser anterior a la fecha de inicio");
        }
    }
    
    // ==================== CONTROL ====================
    public function disableValidation(): self { $this->skipValidation = true; return $this; }
    public function enableValidation(): self { $this->skipValidation = false; return $this; }
    
    // ==================== AUXILIARES ====================
    public function loadFromArray(array $data): self {
        $this->disableValidation();
        if (isset($data['id_torneo'])) $this->setIdTorneo((int)$data['id_torneo']);
        if (isset($data['nombre_torneo'])) $this->setNombreTorneo($data['nombre_torneo']);
        if (isset($data['fecha_inicio'])) $this->setFechaInicio($data['fecha_inicio']);
        if (isset($data['fecha_fin'])) $this->setFechaFin($data['fecha_fin']);
        if (isset($data['lugar'])) $this->setLugar($data['lugar']);
        $this->enableValidation();
        return $this;
    }
    
    public function toArray(): array {
        return [
            'id_torneo' => $this->getIdTorneo(), 'nombre_torneo' => $this->getNombreTorneo(),
            'fecha_inicio' => $this->getFechaInicio(), 'fecha_fin' => $this->getFechaFin(), 'lugar' => $this->getLugar()
        ];
    }
    
    // ==================== CRUD ====================
    public function todos(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM torneos ORDER BY fecha_inicio DESC");
            return $stmt->fetchAll();
        } catch (Exception $e) { $this->logError("Error en todos()", ['message' => $e->getMessage()]); return []; }
    }
    
    public function obtener(int $id): ?array {
        try {
            $this->disableValidation();
            $stmt = $this->db->prepare("SELECT * FROM torneos WHERE id_torneo = ?");
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
            $this->setNombreTorneo($d['nombre_torneo'] ?? null)
                 ->setFechaInicio($d['fecha_inicio'] ?? null)
                 ->setFechaFin($d['fecha_fin'] ?? null)
                 ->setLugar($d['lugar'] ?? null);
            $this->validarRangoFechas();
            
            $stmt = $this->db->prepare("INSERT INTO torneos (nombre_torneo, fecha_inicio, fecha_fin, lugar) VALUES (?,?,?,?)");
            $result = $stmt->execute([
                $this->getNombreTorneo(), $this->getFechaInicio(), $this->getFechaFin(), $this->getLugar()
            ]);
            $id = (int)$this->db->lastInsertId();
            $this->setIdTorneo($id);
            return $result ? $id : false;
        } catch (Exception $e) { $this->logError("Error en crear()", ['message' => $e->getMessage()]); throw $e; }
    }
    
    public function actualizar(int $id, array $d): bool {
        try {
            $this->enableValidation();
            $this->setIdTorneo($id);
            $this->setNombreTorneo($d['nombre_torneo'] ?? null)
                 ->setFechaInicio($d['fecha_inicio'] ?? null)
                 ->setFechaFin($d['fecha_fin'] ?? null)
                 ->setLugar($d['lugar'] ?? null);
            $this->validarRangoFechas();
            
            $stmt = $this->db->prepare("UPDATE torneos SET nombre_torneo = ?, fecha_inicio = ?, fecha_fin = ?, lugar = ? WHERE id_torneo = ?");
            return $stmt->execute([
                $this->getNombreTorneo(), $this->getFechaInicio(), $this->getFechaFin(), $this->getLugar(), $id
            ]);
        } catch (Exception $e) { $this->logError("Error en actualizar()", ['id' => $id, 'message' => $e->getMessage()]); throw $e; }
    }
    
    public function eliminar(int $id): bool {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM combates WHERE id_torneo = ?");
            $stmt->execute([$id]);
            if ((int)$stmt->fetchColumn() > 0) throw new Exception("No se puede eliminar el torneo porque tiene combates asociados.");
            return $this->db->prepare("DELETE FROM torneos WHERE id_torneo = ?")->execute([$id]);
        } catch (Exception $e) { $this->logError("Error en eliminar()", ['id' => $id, 'message' => $e->getMessage()]); throw $e; }
    }
    
    public function existeNombre(string $nombre, ?int $excluirId = null): bool {
        try {
            $sql = "SELECT COUNT(*) FROM torneos WHERE LOWER(nombre_torneo) = LOWER(?)";
            $params = [trim($nombre)];
            if ($excluirId !== null) { $sql .= " AND id_torneo != ?"; $params[] = $excluirId; }
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return (int)$stmt->fetchColumn() > 0;
        } catch (Exception $e) { $this->logError("Error en existeNombre()", ['message' => $e->getMessage()]); return false; }
    }
    
    public function contarCombates(int $id): array {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS total, SUM(CASE WHEN resultado != 'Pendiente' THEN 1 ELSE 0 END) AS con_resultado FROM combates WHERE id_torneo = ?");
            $stmt->execute([$id]);
            $r = $stmt->fetch();
            return ['total' => (int)($r['total'] ?? 0), 'con_resultado' => (int)($r['con_resultado'] ?? 0)];
        } catch (Exception $e) { return ['total' => 0, 'con_resultado' => 0]; }
    }
    
    // ==================== LLAVES (CORREGIDO) ====================
    public function getLlaves(int $idTorneo): array {
        try {
            // ⭐ Usamos vista_atletas para evitar el error de columna 'nivel'
            $sql = "SELECT cb.*, 
                    CONCAT(al.pnombre, ' ', al.papellido) AS local_nombre,
                    CONCAT(av.pnombre, ' ', av.papellido) AS visitante_nombre,
                    al.foto AS local_foto,
                    av.foto AS visitante_foto,
                    al.id_atleta AS local_id,
                    av.id_atleta AS visitante_id,
                    al.total_combates AS local_total_combates,
                    av.total_combates AS visitante_total_combates
                    FROM combates cb
                    LEFT JOIN vista_atletas al ON al.id_atleta = cb.id_atleta_local
                    LEFT JOIN vista_atletas av ON av.id_atleta = cb.id_atleta_visitante
                    WHERE cb.id_torneo = ?
                    ORDER BY cb.ronda ASC, cb.posicion_llave ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$idTorneo]);
            $combates = $stmt->fetchAll();
            
            if (empty($combates)) return [];
            
            // Calcular nivel para cada atleta
            foreach ($combates as &$c) {
                $c['local_nivel'] = Atleta::calcularNivel((int)$c['local_total_combates']);
                $c['visitante_nivel'] = Atleta::calcularNivel((int)$c['visitante_total_combates']);
            }
            unset($c);
            
            $llaves = [];
            $ordenRondas = ['Octavos', 'Cuartos', 'Semifinal', 'Final'];
            
            foreach ($combates as $combate) {
                $ronda = (int)($combate['ronda'] ?? 0);
                $nombreRonda = $this->getNombreRonda($ronda);
                if (!isset($llaves[$nombreRonda])) $llaves[$nombreRonda] = [];
                $llaves[$nombreRonda][] = $combate;
            }
            
            $llavesOrdenadas = [];
            foreach ($ordenRondas as $nombre) {
                if (isset($llaves[$nombre])) {
                    usort($llaves[$nombre], fn($a, $b) => ($a['posicion_llave'] ?? 0) - ($b['posicion_llave'] ?? 0));
                    $llavesOrdenadas[$nombre] = $llaves[$nombre];
                }
            }
            
            return $llavesOrdenadas;
        } catch (Exception $e) {
            $this->logError("Error en getLlaves()", ['message' => $e->getMessage()]);
            return [];
        }
    }
    
    private function getNombreRonda(int $ronda): string {
        $nombres = [1 => 'Octavos', 2 => 'Cuartos', 3 => 'Semifinal', 4 => 'Final'];
        return $nombres[$ronda] ?? 'Ronda ' . $ronda;
    }
    
    public function getGanador(array $combate): ?array {
        if ($combate['resultado'] === 'Local') return ['id' => $combate['local_id'], 'nombre' => $combate['local_nombre']];
        elseif ($combate['resultado'] === 'Visitante') return ['id' => $combate['visitante_id'], 'nombre' => $combate['visitante_nombre']];
        return null;
    }
    
    // ==================== LOGGING ====================
    private function logError(string $message, array $context = []): void {
        $log = "[Torneo ERROR] $message";
        if (!empty($context)) $log .= " | " . json_encode($context);
        error_log($log);
    }
}