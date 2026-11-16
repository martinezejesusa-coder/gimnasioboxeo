<?php
/**
 * Modelo Atleta - Gestiona la información de los atletas
 * @version 4.1 - calcularNivel público estático
 */
class Atleta extends Model {
    
    public const NIVELES = ['Novato', 'Intermedio', 'Avanzado', 'Experto'];
    
    private ?int $id_atleta = null;
    private ?string $pnombre = null;
    private ?string $snombre = null;
    private ?string $papellido = null;
    private ?string $sapellido = null;
    private ?string $cedula = null;
    private ?string $fecha_nacimiento = null;
    private ?int $id_club = null;
    private ?int $id_categoria = null;
    private ?string $foto = null;
    private int $activo = 1;
    
    // ==================== GETTERS ====================
    public function getIdAtleta(): ?int { return $this->id_atleta; }
    public function getPnombre(): ?string { return $this->pnombre; }
    public function getSnombre(): ?string { return $this->snombre; }
    public function getPapellido(): ?string { return $this->papellido; }
    public function getSapellido(): ?string { return $this->sapellido; }
    public function getCedula(): ?string { return $this->cedula; }
    public function getFechaNacimiento(): ?string { return $this->fecha_nacimiento; }
    public function getIdClub(): ?int { return $this->id_club; }
    public function getIdCategoria(): ?int { return $this->id_categoria; }
    public function getFoto(): ?string { return $this->foto; }
    public function getActivo(): int { return $this->activo; }
    
    // ==================== SETTERS ====================
    public function setIdAtleta(?int $id): self {
        if ($id !== null && $id <= 0) throw new InvalidArgumentException("ID de atleta inválido");
        $this->id_atleta = $id; return $this;
    }
    public function setPnombre(?string $nombre): self {
        if ($nombre !== null && !empty($nombre)) {
            $nombre = trim($nombre);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $nombre)) {
                throw new InvalidArgumentException("El primer nombre solo puede contener letras (2-50 caracteres)");
            }
        }
        $this->pnombre = $nombre ?: null; return $this;
    }
    public function setSnombre(?string $nombre): self {
        if ($nombre !== null && !empty($nombre)) {
            $nombre = trim($nombre);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $nombre)) {
                throw new InvalidArgumentException("El segundo nombre solo puede contener letras (máx 50 caracteres)");
            }
        }
        $this->snombre = $nombre ?: null; return $this;
    }
    public function setPapellido(?string $apellido): self {
        if ($apellido !== null && !empty($apellido)) {
            $apellido = trim($apellido);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $apellido)) {
                throw new InvalidArgumentException("El primer apellido solo puede contener letras (2-50 caracteres)");
            }
        }
        $this->papellido = $apellido ?: null; return $this;
    }
    public function setSapellido(?string $apellido): self {
        if ($apellido !== null && !empty($apellido)) {
            $apellido = trim($apellido);
            if (!preg_match('/^[a-zA-ZáéíóúñÑÁÉÍÓÚ\s]{2,50}$/u', $apellido)) {
                throw new InvalidArgumentException("El segundo apellido solo puede contener letras (máx 50 caracteres)");
            }
        }
        $this->sapellido = $apellido ?: null; return $this;
    }
    public function setCedula(?string $cedula): self {
        if ($cedula !== null && !empty($cedula)) {
            $cedula = strtoupper(preg_replace('/\s+/', '', trim($cedula)));
            if (!preg_match('/^[VEJP]?-?\d{6,9}$/u', $cedula)) {
                throw new InvalidArgumentException("La cédula debe tener entre 6 y 9 dígitos. Ejemplos: V-12345678, E-12345678, J-12345678");
            }
            if (preg_match('/^([VEJP]?)-?(\d{6,9})$/u', $cedula, $m)) {
                $letra = $m[1] !== '' ? $m[1] : 'V';
                $cedula = $letra . '-' . $m[2];
            }
        }
        $this->cedula = $cedula ?: null; return $this;
    }
    public function setFechaNacimiento(?string $fecha): self {
        if ($fecha !== null && !empty($fecha)) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                throw new InvalidArgumentException("Formato de fecha inválido. Use YYYY-MM-DD");
            }
            $fechaObj = new DateTime($fecha);
            $hoy = new DateTime();
            $edad = $hoy->diff($fechaObj)->y;
            if ($edad < 5) throw new InvalidArgumentException("El atleta debe tener al menos 5 años");
            if ($edad > 100) throw new InvalidArgumentException("Edad fuera de rango válido (máx 100 años)");
        }
        $this->fecha_nacimiento = $fecha ?: null; return $this;
    }
    public function setIdClub(?int $id): self {
        if ($id !== null && $id <= 0) throw new InvalidArgumentException("ID de club inválido");
        $this->id_club = $id; return $this;
    }
    public function setIdCategoria(?int $id): self {
        if ($id !== null && $id <= 0) throw new InvalidArgumentException("ID de categoría inválido");
        $this->id_categoria = $id; return $this;
    }
    public function setFoto(?string $foto): self { $this->foto = $foto; return $this; }
    public function setActivo(int $activo): self { $this->activo = $activo; return $this; }
    
    // ==================== MÉTODOS AUXILIARES ====================
    
    public static function calcularNivel(int $totalCombates): string {
        if ($totalCombates >= 41) return 'Experto';
        if ($totalCombates >= 21) return 'Avanzado';
        if ($totalCombates >= 6) return 'Intermedio';
        return 'Novato';
    }
    
    public function loadFromArray(array $data): self {
        if (isset($data['id_atleta'])) $this->setIdAtleta((int)$data['id_atleta']);
        if (isset($data['pnombre'])) $this->setPnombre($data['pnombre']);
        if (isset($data['snombre'])) $this->setSnombre($data['snombre']);
        if (isset($data['papellido'])) $this->setPapellido($data['papellido']);
        if (isset($data['sapellido'])) $this->setSapellido($data['sapellido']);
        if (isset($data['cedula'])) $this->setCedula($data['cedula']);
        if (isset($data['fecha_nacimiento'])) $this->setFechaNacimiento($data['fecha_nacimiento']);
        if (isset($data['id_club'])) $this->setIdClub($data['id_club'] ? (int)$data['id_club'] : null);
        if (isset($data['id_categoria'])) $this->setIdCategoria($data['id_categoria'] ? (int)$data['id_categoria'] : null);
        if (isset($data['foto'])) $this->setFoto($data['foto']);
        if (isset($data['activo'])) $this->setActivo((int)$data['activo']);
        return $this;
    }
    
    public function toArray(): array {
        return [
            'id_atleta' => $this->getIdAtleta(), 'pnombre' => $this->getPnombre(),
            'snombre' => $this->getSnombre(), 'papellido' => $this->getPapellido(),
            'sapellido' => $this->getSapellido(), 'cedula' => $this->getCedula(),
            'fecha_nacimiento' => $this->getFechaNacimiento(), 'id_club' => $this->getIdClub(),
            'id_categoria' => $this->getIdCategoria(), 'foto' => $this->getFoto(), 'activo' => $this->getActivo()
        ];
    }
    
    // ==================== CRUD ====================
    
    public function todos(array $filtros = []): array {
        try {
            $sql = "SELECT v.*, c.nombre_club, cat.nombre_categoria
                    FROM vista_atletas v
                    LEFT JOIN clubes c ON c.id_club = v.id_club
                    LEFT JOIN categorias cat ON cat.id_categoria = v.id_categoria
                    WHERE 1=1";
            $params = [];
            if (!empty($filtros['club'])) { $sql .= " AND v.id_club = :club"; $params[':club'] = $filtros['club']; }
            if (!empty($filtros['categoria'])) { $sql .= " AND v.id_categoria = :categoria"; $params[':categoria'] = $filtros['categoria']; }
            if (isset($filtros['activo']) && $filtros['activo'] !== '') { $sql .= " AND v.activo = :activo"; $params[':activo'] = $filtros['activo']; }
            $sql .= " ORDER BY v.id_atleta DESC";
            $resultados = $this->executeQuery($sql, $params, 'all');
            foreach ($resultados as &$row) {
                $row['nivel_calculado'] = self::calcularNivel((int)$row['total_combates']);
            }
            return $resultados;
        } catch (Exception $e) { $this->logError("Error en todos()", ['message' => $e->getMessage()]); return []; }
    }
    
    public function obtener(int $id): ?array {
        try {
            $sql = "SELECT v.*, c.nombre_club, cat.nombre_categoria 
                    FROM vista_atletas v
                    LEFT JOIN clubes c ON c.id_club = v.id_club
                    LEFT JOIN categorias cat ON cat.id_categoria = v.id_categoria
                    WHERE v.id_atleta = :id";
            $result = $this->executeQuery($sql, [':id' => $id], 'one');
            if ($result) {
                $result['nivel_calculado'] = self::calcularNivel((int)$result['total_combates']);
                $this->loadFromArray($result);
            }
            return $result;
        } catch (Exception $e) { $this->logError("Error en obtener()", ['id' => $id, 'message' => $e->getMessage()]); return null; }
    }
    
    public function documentos(int $id): array {
        try {
            $sql = "SELECT * FROM atleta_documentos WHERE id_atleta = :id ORDER BY fecha_subida DESC";
            return $this->executeQuery($sql, [':id' => $id], 'all');
        } catch (Exception $e) { $this->logError("Error en documentos()", ['id' => $id, 'message' => $e->getMessage()]); return []; }
    }
    
    public function historialCombates(int $id): array {
        try {
            $sql = "SELECT cb.*, t.nombre_torneo,
                    CONCAT(al.pnombre, ' ', al.papellido) AS local_nombre,
                    CONCAT(av.pnombre, ' ', av.papellido) AS visitante_nombre
                    FROM combates cb
                    LEFT JOIN torneos t ON t.id_torneo = cb.id_torneo
                    LEFT JOIN atletas al ON al.id_atleta = cb.id_atleta_local
                    LEFT JOIN atletas av ON av.id_atleta = cb.id_atleta_visitante
                    WHERE cb.id_atleta_local = :id OR cb.id_atleta_visitante = :id
                    ORDER BY cb.fecha DESC";
            return $this->executeQuery($sql, [':id' => $id], 'all');
        } catch (Exception $e) { $this->logError("Error en historialCombates()", ['id' => $id, 'message' => $e->getMessage()]); return []; }
    }
    
    public function crear(array $d): int|false {
        try {
            $this->setPnombre($d['pnombre'] ?? null)
                 ->setSnombre($d['snombre'] ?? null)
                 ->setPapellido($d['papellido'] ?? null)
                 ->setSapellido($d['sapellido'] ?? null)
                 ->setCedula($d['cedula'] ?? null)
                 ->setFechaNacimiento($d['fecha_nacimiento'] ?? null)
                 ->setIdClub(!empty($d['id_club']) ? (int)$d['id_club'] : null)
                 ->setIdCategoria(!empty($d['id_categoria']) ? (int)$d['id_categoria'] : null)
                 ->setFoto($d['foto'] ?? null);
            $sql = "INSERT INTO atletas (
                        pnombre, snombre, papellido, sapellido, cedula,
                        fecha_nacimiento, id_club, id_categoria, foto
                    ) VALUES (
                        :pnombre, :snombre, :papellido, :sapellido, :cedula,
                        :fecha_nacimiento, :id_club, :id_categoria, :foto
                    )";
            $params = [
                ':pnombre' => $this->getPnombre(), ':snombre' => $this->getSnombre(),
                ':papellido' => $this->getPapellido(), ':sapellido' => $this->getSapellido(),
                ':cedula' => $this->getCedula(), ':fecha_nacimiento' => $this->getFechaNacimiento(),
                ':id_club' => $this->getIdClub(), ':id_categoria' => $this->getIdCategoria(),
                ':foto' => $this->getFoto()
            ];
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $id = (int)$this->db->lastInsertId();
            $this->setIdAtleta($id);
            return $id;
        } catch (InvalidArgumentException $e) { throw $e; }
        catch (Exception $e) { $this->logError("Error en crear()", ['message' => $e->getMessage()]); throw new Exception("Error al crear el atleta: " . $e->getMessage()); }
    }
    
    public function actualizar(int $id, array $d): bool {
        try {
            if ($id <= 0) throw new InvalidArgumentException("ID de atleta inválido");
            $this->setPnombre($d['pnombre'] ?? null)
                 ->setSnombre($d['snombre'] ?? null)
                 ->setPapellido($d['papellido'] ?? null)
                 ->setSapellido($d['sapellido'] ?? null)
                 ->setCedula($d['cedula'] ?? null)
                 ->setFechaNacimiento($d['fecha_nacimiento'] ?? null)
                 ->setIdClub(!empty($d['id_club']) ? (int)$d['id_club'] : null)
                 ->setIdCategoria(!empty($d['id_categoria']) ? (int)$d['id_categoria'] : null);
            $sql = "UPDATE atletas SET 
                        pnombre = :pnombre, snombre = :snombre, papellido = :papellido,
                        sapellido = :sapellido, cedula = :cedula, fecha_nacimiento = :fecha_nacimiento,
                        id_club = :id_club, id_categoria = :id_categoria";
            $params = [
                ':pnombre' => $this->getPnombre(), ':snombre' => $this->getSnombre(),
                ':papellido' => $this->getPapellido(), ':sapellido' => $this->getSapellido(),
                ':cedula' => $this->getCedula(), ':fecha_nacimiento' => $this->getFechaNacimiento(),
                ':id_club' => $this->getIdClub(), ':id_categoria' => $this->getIdCategoria(),
                ':id_atleta' => $id
            ];
            if (!empty($d['foto'])) {
                $sql .= ", foto = :foto";
                $params[':foto'] = $d['foto'];
                $this->setFoto($d['foto']);
            }
            $sql .= " WHERE id_atleta = :id_atleta";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (InvalidArgumentException $e) { throw $e; }
        catch (Exception $e) { $this->logError("Error en actualizar()", ['id' => $id, 'message' => $e->getMessage()]); throw new Exception("Error al actualizar el atleta: " . $e->getMessage()); }
    }
    
    public function eliminar(int $id): bool {
        try {
            if ($id <= 0) throw new InvalidArgumentException("ID de atleta inválido");
            $sql = "UPDATE atletas SET activo = 0 WHERE id_atleta = :id";
            $result = $this->executeQuery($sql, [':id' => $id], 'stmt');
            return $result->rowCount() > 0;
        } catch (Exception $e) { $this->logError("Error en eliminar()", ['id' => $id, 'message' => $e->getMessage()]); return false; }
    }
    
    public function agregarDocumento(int $id, string $nombre, string $ruta): void {
        try {
            $sql = "INSERT INTO atleta_documentos (id_atleta, nombre_documento, ruta_archivo) 
                    VALUES (:id, :nombre, :ruta)";
            $this->executeQuery($sql, [':id' => $id, ':nombre' => $nombre, ':ruta' => $ruta], 'stmt');
        } catch (Exception $e) { $this->logError("Error en agregarDocumento()", ['id' => $id, 'message' => $e->getMessage()]); }
    }
    
    public function ranking(): array {
        try {
            $sql = "SELECT v.*, c.nombre_club 
                    FROM vista_atletas v 
                    LEFT JOIN clubes c ON c.id_club = v.id_club 
                    WHERE v.activo = 1 
                    ORDER BY v.victorias DESC, v.total_combates DESC 
                    LIMIT 50";
            $resultados = $this->executeQuery($sql, [], 'all');
            foreach ($resultados as &$row) {
                $row['nivel_calculado'] = self::calcularNivel((int)$row['total_combates']);
            }
            return $resultados;
        } catch (Exception $e) { $this->logError("Error en ranking()", ['message' => $e->getMessage()]); return []; }
    }
    
    // ==================== MÉTODOS UTILITARIOS ====================
    public function getNombreCompleto(): string {
        return trim($this->getPnombre() . ' ' . ($this->getSnombre() ?? '') . ' ' . $this->getPapellido() . ' ' . ($this->getSapellido() ?? ''));
    }
    public function getEdad(): ?int {
        if (!$this->getFechaNacimiento()) return null;
        $fecha = new DateTime($this->getFechaNacimiento());
        $hoy = new DateTime();
        return $hoy->diff($fecha)->y;
    }
    public function getWinRate(int $victorias, int $total): float {
        if ($total === 0) return 0;
        return round(($victorias / $total) * 100, 2);
    }
    public function existeCedula(string $cedula, ?int $excluirId = null): bool {
        try {
            $normalizada = $this->normalizarCedula($cedula);
            $sql = "SELECT cedula FROM atletas";
            $params = [];
            if ($excluirId !== null) { $sql .= " WHERE id_atleta != :id"; $params[':id'] = $excluirId; }
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
        if (preg_match('/^\d+$/', $c)) $c = 'V' . $c;
        return $c;
    }
    protected function logError(string $message, array $context = []): void {
        $log = "[Atleta ERROR] $message";
        if (!empty($context)) $log .= " | " . json_encode($context);
        error_log($log);
    }
}