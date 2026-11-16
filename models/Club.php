<?php
/**
 * Modelo Club
 * @version 5.0 - Ubicación normalizada en Base de Datos (Estados, Municipios, Parroquias)
 */
class Club extends Model {
    
    private ?int $id_club = null;
    private ?string $nombre_club = null;
    private ?string $ubicacion = null;
    private ?int $id_parroquia = null;
    private ?string $telefono = null;
    private ?string $correo = null;
    private ?string $logo = null;
    
    private bool $skipValidation = false;
    
    // ==================== GETTERS ====================
    public function getIdClub(): ?int { return $this->id_club; }
    public function getNombreClub(): ?string { return $this->nombre_club; }
    public function getUbicacion(): ?string { return $this->ubicacion; }
    public function getIdParroquia(): ?int { return $this->id_parroquia; }
    public function getTelefono(): ?string { return $this->telefono; }
    public function getCorreo(): ?string { return $this->correo; }
    public function getLogo(): ?string { return $this->logo; }
    
    // ==================== SETTERS ====================
    public function setIdClub(?int $id): self {
        if (!$this->skipValidation && $id !== null && $id <= 0) throw new InvalidArgumentException("ID de club inválido");
        $this->id_club = $id; return $this;
    }
    public function setNombreClub(?string $nombre): self {
        if (!$this->skipValidation && !empty($nombre)) {
            $nombre = trim($nombre);
            if (!preg_match('/^[a-zA-Z0-9áéíóúñÑÁÉÍÓÚ\s\-\.,&]{3,100}$/u', $nombre)) {
                throw new InvalidArgumentException("El nombre del club debe tener entre 3 y 100 caracteres válidos");
            }
        }
        $this->nombre_club = $nombre; return $this;
    }
    public function setUbicacion(?string $ubicacion): self {
        if (!$this->skipValidation && !empty($ubicacion)) {
            $ubicacion = trim($ubicacion);
            if (mb_strlen($ubicacion) > 255) throw new InvalidArgumentException("La ubicación no puede exceder los 255 caracteres");
        }
        $this->ubicacion = $ubicacion; return $this;
    }
    public function setIdParroquia(?int $id_parroquia): self {
        if (!$this->skipValidation && $id_parroquia !== null && $id_parroquia <= 0) {
            throw new InvalidArgumentException("Parroquia inválida");
        }
        $this->id_parroquia = $id_parroquia; return $this;
    }
    public function setTelefono(?string $telefono): self {
        if (!$this->skipValidation && !empty($telefono)) {
            $telefono = trim($telefono);
            if (!preg_match('/^\+?[\d\s\-\(\)\.]+$/', $telefono)) throw new InvalidArgumentException("Teléfono inválido");
            $soloDigitos = preg_replace('/\D/', '', $telefono);
            if (strlen($soloDigitos) < 7 || strlen($soloDigitos) > 20) throw new InvalidArgumentException("El teléfono debe tener entre 7 y 20 dígitos");
        }
        $this->telefono = $telefono; return $this;
    }
    public function setCorreo(?string $correo): self {
        if (!$this->skipValidation && !empty($correo)) {
            $correo = trim($correo);
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) throw new InvalidArgumentException("Correo electrónico inválido");
        }
        $this->correo = $correo; return $this;
    }
    public function setLogo(?string $logo): self { $this->logo = $logo; return $this; }
    
    public function disableValidation(): self { $this->skipValidation = true; return $this; }
    public function enableValidation(): self { $this->skipValidation = false; return $this; }
    
    public function loadFromArray(array $data): self {
        $this->disableValidation();
        if (isset($data['id_club'])) $this->setIdClub((int)$data['id_club']);
        if (isset($data['nombre_club'])) $this->setNombreClub($data['nombre_club']);
        if (isset($data['ubicacion'])) $this->setUbicacion($data['ubicacion']);
        if (isset($data['id_parroquia'])) $this->setIdParroquia((int)$data['id_parroquia']);
        if (isset($data['telefono'])) $this->setTelefono($data['telefono']);
        if (isset($data['correo'])) $this->setCorreo($data['correo']);
        if (isset($data['logo'])) $this->setLogo($data['logo']);
        $this->enableValidation();
        return $this;
    }
    
    public function toArray(): array {
        return [
            'id_club' => $this->getIdClub(), 'nombre_club' => $this->getNombreClub(),
            'ubicacion' => $this->getUbicacion(), 'id_parroquia' => $this->getIdParroquia(),
            'telefono' => $this->getTelefono(), 'correo' => $this->getCorreo(), 'logo' => $this->getLogo()
        ];
    }

    // ==================== CONSULTAS DE UBICACIÓN ====================
    public function getEstados(): array {
        return $this->db->query("SELECT * FROM estados ORDER BY nombre_estado")->fetchAll();
    }
    public function getMunicipios(int $id_estado): array {
        $stmt = $this->db->prepare("SELECT * FROM municipios WHERE id_estado = ? ORDER BY nombre_municipio");
        $stmt->execute([$id_estado]); return $stmt->fetchAll();
    }
    public function getParroquias(int $id_municipio): array {
        $stmt = $this->db->prepare("SELECT * FROM parroquias WHERE id_municipio = ? ORDER BY nombre_parroquia");
        $stmt->execute([$id_municipio]); return $stmt->fetchAll();
    }
    
    // ==================== CRUD ====================
    public function todos(): array {
        try {
            $sql = "SELECT c.*, 
                    (SELECT CONCAT(e.pnombre_entrenador, ' ', e.apellido_entrenador) 
                     FROM entrenador_club ec JOIN entrenadores e ON ec.id_entrenador = e.id_entrenador 
                     WHERE ec.id_club = c.id_club LIMIT 1) AS entrenador,
                    p.nombre_parroquia, m.nombre_municipio, est.nombre_estado
                    FROM clubes c
                    LEFT JOIN parroquias p ON c.id_parroquia = p.id_parroquia
                    LEFT JOIN municipios m ON p.id_municipio = m.id_municipio
                    LEFT JOIN estados est ON m.id_estado = est.id_estado
                    ORDER BY c.id_club DESC";
            return $this->db->query($sql)->fetchAll();
        } catch (Exception $e) { error_log($e->getMessage()); return []; }
    }
    
    public function obtener(int $id): ?array {
        try {
            $this->disableValidation();
            $sql = "SELECT c.*, p.nombre_parroquia, m.nombre_municipio, m.id_municipio, est.nombre_estado, est.id_estado 
                    FROM clubes c
                    LEFT JOIN parroquias p ON c.id_parroquia = p.id_parroquia
                    LEFT JOIN municipios m ON p.id_municipio = m.id_municipio
                    LEFT JOIN estados est ON m.id_estado = est.id_estado
                    WHERE c.id_club = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            if ($result) $this->loadFromArray($result);
            $this->enableValidation();
            return $result ?: null;
        } catch (Exception $e) { error_log($e->getMessage()); return null; }
    }
    
        public function atletasDe(int $id): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM vista_atletas WHERE id_club = ? ORDER BY pnombre");
            $stmt->execute([$id]);
            $atletas = $stmt->fetchAll();
            
            // Calcular nivel para cada atleta
            foreach ($atletas as &$a) {
                $a['nivel_calculado'] = Atleta::calcularNivel((int)$a['total_combates']);
            }
            
            return $atletas;
        } catch (Exception $e) { 
            error_log("Error en Club::atletasDe: " . $e->getMessage());
            return []; 
        }
    }
    
    public function entrenadoresDe(int $id): array {
        try {
            $sql = "SELECT e.* FROM entrenadores e
                    INNER JOIN entrenador_club ec ON ec.id_entrenador = e.id_entrenador
                    WHERE ec.id_club = ? ORDER BY e.pnombre_entrenador";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchAll();
        } catch (Exception $e) { return []; }
    }
    
    public function obtenerEntrenadorResponsable(int $idClub): ?int {
        try {
            $stmt = $this->db->prepare("SELECT id_entrenador FROM entrenador_club WHERE id_club = ? LIMIT 1");
            $stmt->execute([$idClub]);
            $result = $stmt->fetch();
            return $result ? (int)$result['id_entrenador'] : null;
        } catch (Exception $e) { return null; }
    }
    
    private function asignarEntrenadorResponsable(int $idClub, int $idEntrenador): void {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM entrenador_club WHERE id_club = ?");
            $stmt->execute([$idClub]);
            if ($stmt->fetchColumn() > 0) {
                $stmt = $this->db->prepare("UPDATE entrenador_club SET id_entrenador = ? WHERE id_club = ?");
                $stmt->execute([$idEntrenador, $idClub]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO entrenador_club (id_entrenador, id_club) VALUES (?, ?)");
                $stmt->execute([$idEntrenador, $idClub]);
            }
        } catch (Exception $e) { error_log($e->getMessage()); }
    }

    public function crear(array $d): int|false {
        try {
            $this->enableValidation();
            $this->setNombreClub($d['nombre_club'] ?? null);
            if (!empty($d['ubicacion'])) $this->setUbicacion($d['ubicacion']);
            if (!empty($d['id_parroquia'])) $this->setIdParroquia((int)$d['id_parroquia']);
            if (!empty($d['telefono'])) $this->setTelefono($d['telefono']);
            if (!empty($d['correo'])) $this->setCorreo($d['correo']);
            if (!empty($d['logo'])) $this->setLogo($d['logo']);
            
            $sql = "INSERT INTO clubes (nombre_club, ubicacion, id_parroquia, telefono, correo, logo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                $this->getNombreClub(), $this->getUbicacion(), $this->getIdParroquia(),
                $this->getTelefono(), $this->getCorreo(), $this->getLogo()
            ]);
            $id = (int)$this->db->lastInsertId();
            if (!empty($d['id_entrenador'])) $this->asignarEntrenadorResponsable($id, (int)$d['id_entrenador']);
            return $result ? $id : false;
        } catch (Exception $e) { throw $e; }
    }
    
    public function actualizar(int $id, array $d): bool {
        try {
            $this->enableValidation();
            $this->setIdClub($id);
            $this->setNombreClub($d['nombre_club'] ?? null);
            if (!empty($d['ubicacion'])) $this->setUbicacion($d['ubicacion']);
            if (!empty($d['id_parroquia'])) $this->setIdParroquia((int)$d['id_parroquia']);
            if (!empty($d['telefono'])) $this->setTelefono($d['telefono']);
            if (!empty($d['correo'])) $this->setCorreo($d['correo']);
            if (!empty($d['logo'])) $this->setLogo($d['logo']);
            
            $sql = "UPDATE clubes SET nombre_club=?, ubicacion=?, id_parroquia=?, telefono=?, correo=?" . (!empty($d['logo']) ? ", logo=?" : "") . " WHERE id_club=?";
            $params = [$this->getNombreClub(), $this->getUbicacion(), $this->getIdParroquia(), $this->getTelefono(), $this->getCorreo()];
            if (!empty($d['logo'])) $params[] = $this->getLogo();
            $params[] = $id;
            
            $result = $this->db->prepare($sql)->execute($params);
            if (!empty($d['id_entrenador'])) $this->asignarEntrenadorResponsable($id, (int)$d['id_entrenador']);
            return $result;
        } catch (Exception $e) { throw $e; }
    }
    
    public function eliminar(int $id): bool {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM atletas WHERE id_club = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() > 0) throw new Exception("No se puede eliminar el club porque tiene atletas asociados.");
            
            $this->db->prepare("DELETE FROM entrenador_club WHERE id_club = ?")->execute([$id]);
            return $this->db->prepare("DELETE FROM clubes WHERE id_club = ?")->execute([$id]);
        } catch (Exception $e) { throw $e; }
    }
    
    public function existeNombre(string $nombre, ?int $excluirId = null): bool {
        try {
            $sql = "SELECT COUNT(*) FROM clubes WHERE LOWER(nombre_club) = LOWER(?)";
            $params = [trim($nombre)];
            if ($excluirId !== null) { $sql .= " AND id_club != ?"; $params[] = $excluirId; }
            $stmt = $this->db->prepare($sql); $stmt->execute($params);
            return (int)$stmt->fetchColumn() > 0;
        } catch (Exception $e) { return false; }
    }
    
    public function existeCorreo(string $correo, ?int $excluirId = null): bool {
        try {
            $sql = "SELECT COUNT(*) FROM clubes WHERE LOWER(correo) = LOWER(?)";
            $params = [trim($correo)];
            if ($excluirId !== null) { $sql .= " AND id_club != ?"; $params[] = $excluirId; }
            $stmt = $this->db->prepare($sql); $stmt->execute($params);
            return (int)$stmt->fetchColumn() > 0;
        } catch (Exception $e) { return false; }
    }

    public static function getPaises(): array {
        $archivo = __DIR__ . '/../data/paises.php';
        return file_exists($archivo) ? require $archivo : [];
    }
}