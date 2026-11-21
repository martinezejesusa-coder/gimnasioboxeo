<?php
/**
 * Modelo Combate
 * 
 * Encapsulamiento estricto: todos los métodos usan executeQuery().
 * Los métodos crear() y actualizar() NO reciben parámetros (usan getters).
 */
class Combate extends Model {
    
    private ?int $id_combate = null;
    private ?int $id_torneo = null;
    private ?int $id_atleta_local = null;
    private ?int $id_atleta_visitante = null;
    private ?string $fecha = null;
    private ?int $ronda = null;
    private ?int $rounds_pautados = 3;
    private ?int $round_finalizacion = null;
    private ?string $decision_victoria = null;
    private ?string $resultado = 'Pendiente';
    private ?int $posicion_llave = null;

    // ==================== GETTERS ====================
    public function getIdCombate(): ?int { return $this->id_combate; }
    public function getIdTorneo(): ?int { return $this->id_torneo; }
    public function getIdAtletaLocal(): ?int { return $this->id_atleta_local; }
    public function getIdAtletaVisitante(): ?int { return $this->id_atleta_visitante; }
    public function getFecha(): ?string { return $this->fecha; }
    public function getRonda(): ?int { return $this->ronda; }
    public function getRoundsPautados(): ?int { return $this->rounds_pautados; }
    public function getRoundFinalizacion(): ?int { return $this->round_finalizacion; }
    public function getDecisionVictoria(): ?string { return $this->decision_victoria; }
    public function getResultado(): ?string { return $this->resultado; }
    public function getPosicionLlave(): ?int { return $this->posicion_llave; }

    // ==================== SETTERS ====================
    public function setIdCombate(?int $id): void {
        if ($id !== null && $id <= 0) throw new InvalidArgumentException("ID de combate inválido");
        $this->id_combate = $id;
    }
    public function setIdTorneo(?int $id): void {
        if ($id !== null && $id <= 0) throw new InvalidArgumentException("ID de torneo inválido");
        $this->id_torneo = $id;
    }
    public function setIdAtletaLocal(?int $id): void {
        if ($id === null || $id <= 0) throw new InvalidArgumentException("El atleta local es requerido");
        $this->id_atleta_local = $id;
    }
    public function setIdAtletaVisitante(?int $id): void {
        if ($id === null || $id <= 0) throw new InvalidArgumentException("El atleta visitante es requerido");
        $this->id_atleta_visitante = $id;
    }
    public function setFecha(?string $fecha): void {
        if (empty($fecha) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new InvalidArgumentException("Formato de fecha inválido (YYYY-MM-DD)");
        }
        $this->fecha = $fecha;
    }
    public function setRonda(?int $ronda): void {
        if ($ronda === null || $ronda <= 0) throw new InvalidArgumentException("La ronda debe ser positiva");
        $this->ronda = $ronda;
    }
    public function setRoundsPautados(?int $r): void {
        if (!in_array($r, [3, 6, 8, 10, 12])) throw new InvalidArgumentException("Rounds inválidos");
        $this->rounds_pautados = $r;
    }
    public function setRoundFinalizacion(?int $r): void {
        if ($r !== null && $r <= 0) throw new InvalidArgumentException("Asalto inválido");
        $this->round_finalizacion = $r;
    }
    public function setDecisionVictoria(?string $d): void {
        $permitidas = ['KO','TKO','UD','SD','MD','RTD','DQ','NC','UED','SED','MED','TD','TED'];
        if ($d !== null && !empty($d) && !in_array($d, $permitidas)) throw new InvalidArgumentException("Decisión inválida");
        $this->decision_victoria = $d;
    }
    public function setResultado(?string $r): void {
        if (!in_array($r, ['Pendiente', 'Local', 'Visitante', 'Empate', 'No Contest'])) {
            throw new InvalidArgumentException("Resultado inválido");
        }
        $this->resultado = $r;
    }
    public function setPosicionLlave(?int $p): void {
        if ($p !== null && $p < 0) throw new InvalidArgumentException("Posición inválida");
        $this->posicion_llave = $p;
    }

    // ==================== LÓGICA DE NEGOCIO ====================
    private function validarAtletasDiferentes(): void {
        if ($this->id_atleta_local === $this->id_atleta_visitante) {
            throw new InvalidArgumentException("El atleta local y visitante no pueden ser el mismo");
        }
    }

    // ==================== PERSISTENCIA CON executeQuery ====================
    
    public function todos(): array {
        try {
            $sql = "SELECT cb.*, t.nombre_torneo,
                    CONCAT(al.pnombre, ' ', al.papellido) AS local_nombre,
                    CONCAT(av.pnombre, ' ', av.papellido) AS visitante_nombre
                    FROM combates cb
                    LEFT JOIN torneos t ON t.id_torneo = cb.id_torneo
                    LEFT JOIN atletas al ON al.id_atleta = cb.id_atleta_local
                    LEFT JOIN atletas av ON av.id_atleta = cb.id_atleta_visitante
                    ORDER BY cb.fecha DESC, cb.posicion_llave ASC";
            return $this->executeQuery($sql, [], 'all');
        } catch (Exception $e) {
            error_log("[Combate::todos] " . $e->getMessage());
            return [];
        }
    }
    
    public function obtener(int $id): ?array {
        try {
            $this->setIdCombate($id); // Encapsulamiento: valida el ID
            
            $sql = "SELECT * FROM combates WHERE id_combate = :id";
            $result = $this->executeQuery($sql, [':id' => $this->getIdCombate()], 'one');
            return $result ?: null;
        } catch (Exception $e) {
            error_log("[Combate::obtener] " . $e->getMessage());
            return null;
        }
    }
    
    public function torneos(): array {
        try {
            $sql = "SELECT * FROM torneos ORDER BY fecha_inicio DESC";
            return $this->executeQuery($sql, [], 'all');
        } catch (Exception $e) {
            error_log("[Combate::torneos] " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Crea un combate. NO recibe parámetros: usa los getters.
     */
    public function crear(): int {
        try {
            $this->validarAtletasDiferentes();
            
            $sql = "INSERT INTO combates (
                        id_torneo, id_atleta_local, id_atleta_visitante, 
                        fecha, ronda, rounds_pautados, round_finalizacion, 
                        decision_victoria, resultado, posicion_llave
                    ) VALUES (
                        :id_torneo, :id_atleta_local, :id_atleta_visitante,
                        :fecha, :ronda, :rounds_pautados, :round_finalizacion,
                        :decision_victoria, :resultado, :posicion_llave
                    )";
            
            $this->executeQuery($sql, [
                ':id_torneo'           => $this->getIdTorneo(),
                ':id_atleta_local'     => $this->getIdAtletaLocal(),
                ':id_atleta_visitante' => $this->getIdAtletaVisitante(),
                ':fecha'               => $this->getFecha(),
                ':ronda'               => $this->getRonda(),
                ':rounds_pautados'     => $this->getRoundsPautados(),
                ':round_finalizacion'  => $this->getRoundFinalizacion(),
                ':decision_victoria'   => $this->getDecisionVictoria(),
                ':resultado'           => $this->getResultado(),
                ':posicion_llave'      => $this->getPosicionLlave()
            ], 'stmt');
            
            return (int)$this->db->lastInsertId();
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Exception $e) {
            error_log("[Combate::crear] " . $e->getMessage());
            throw new Exception("Error al guardar el combate");
        }
    }
    
    /**
     * Actualiza un combate. NO recibe parámetros: usa los getters.
     */
    public function actualizar(): bool {
        try {
            $this->validarAtletasDiferentes();
            
            $sql = "UPDATE combates SET 
                        id_torneo = :id_torneo, 
                        id_atleta_local = :id_atleta_local, 
                        id_atleta_visitante = :id_atleta_visitante,
                        fecha = :fecha, 
                        ronda = :ronda, 
                        rounds_pautados = :rounds_pautados, 
                        round_finalizacion = :round_finalizacion,
                        decision_victoria = :decision_victoria, 
                        resultado = :resultado, 
                        posicion_llave = :posicion_llave
                    WHERE id_combate = :id_combate";
            
            $stmt = $this->executeQuery($sql, [
                ':id_torneo'           => $this->getIdTorneo(),
                ':id_atleta_local'     => $this->getIdAtletaLocal(),
                ':id_atleta_visitante' => $this->getIdAtletaVisitante(),
                ':fecha'               => $this->getFecha(),
                ':ronda'               => $this->getRonda(),
                ':rounds_pautados'     => $this->getRoundsPautados(),
                ':round_finalizacion'  => $this->getRoundFinalizacion(),
                ':decision_victoria'   => $this->getDecisionVictoria(),
                ':resultado'           => $this->getResultado(),
                ':posicion_llave'      => $this->getPosicionLlave(),
                ':id_combate'          => $this->getIdCombate()
            ], 'stmt');
            
            return $stmt->rowCount() > 0;
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Exception $e) {
            error_log("[Combate::actualizar] " . $e->getMessage());
            throw new Exception("Error al actualizar el combate");
        }
    }
    
    public function eliminar(int $id): bool {
        try {
            $this->setIdCombate($id);
            $stmt = $this->executeQuery(
                "DELETE FROM combates WHERE id_combate = :id",
                [':id' => $this->getIdCombate()],
                'stmt'
            );
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("[Combate::eliminar] " . $e->getMessage());
            return false;
        }
    }
    
    public function sugerir(int $idCategoria, int $rangoMin, int $rangoMax): array {
        try {
            $sql = "SELECT a.*, c.nombre_club 
                    FROM vista_atletas a
                    LEFT JOIN clubes c ON c.id_club = a.id_club
                    WHERE a.activo = 1 
                    AND a.id_categoria = :categoria 
                    AND a.total_combates BETWEEN :min AND :max
                    ORDER BY a.total_combates DESC";
            
            $atletas = $this->executeQuery($sql, [
                ':categoria' => $idCategoria,
                ':min'       => $rangoMin,
                ':max'       => $rangoMax
            ], 'all');
            
            foreach ($atletas as &$atleta) {
                $atleta['nivel_calculado'] = Atleta::calcularNivel((int)$atleta['total_combates']);
            }
            unset($atleta);
            
            $sugerencias = [];
            for ($i = 0; $i < count($atletas) - 1; $i += 2) {
                $sugerencias[] = ['local' => $atletas[$i], 'visitante' => $atletas[$i+1]];
            }
            return $sugerencias;
        } catch (Exception $e) {
            error_log("[Combate::sugerir] " . $e->getMessage());
            return [];
        }
    }
    
    public function listarServerSide(array $params): array {
        try {
            $draw = (int)($params['draw'] ?? 1);
            $start = (int)($params['start'] ?? 0);
            $length = (int)($params['length'] ?? 10);
            $searchValue = trim($params['search']['value'] ?? '');
            
            $columnasPermitidas = [
                0 => 'cb.fecha', 1 => 't.nombre_torneo', 2 => 'al.pnombre',
                3 => 'av.pnombre', 4 => 'cb.ronda', 5 => 'cb.decision_victoria', 6 => 'cb.resultado'
            ];
            
            $orderBy = 'ORDER BY cb.fecha DESC';
            if (isset($params['order'][0]['column'], $params['order'][0]['dir'])) {
                $colIndex = (int)$params['order'][0]['column'];
                $dir = strtoupper($params['order'][0]['dir']) === 'ASC' ? 'ASC' : 'DESC';
                if (isset($columnasPermitidas[$colIndex])) {
                    $orderBy = "ORDER BY {$columnasPermitidas[$colIndex]} {$dir}";
                }
            }
            
            $from = "FROM combates cb
                     LEFT JOIN torneos t ON t.id_torneo = cb.id_torneo
                     LEFT JOIN atletas al ON al.id_atleta = cb.id_atleta_local
                     LEFT JOIN atletas av ON av.id_atleta = cb.id_atleta_visitante";
            
            $where = '';
            $paramsQuery = [];
            if ($searchValue !== '') {
                $where = "WHERE (cb.fecha LIKE :search OR t.nombre_torneo LIKE :search 
                          OR al.pnombre LIKE :search OR al.papellido LIKE :search 
                          OR av.pnombre LIKE :search OR av.papellido LIKE :search 
                          OR cb.decision_victoria LIKE :search OR cb.resultado LIKE :search)";
                $paramsQuery[':search'] = "%{$searchValue}%";
            }
            
            $totalData = (int)$this->executeQuery("SELECT COUNT(*) {$from}", [], 'column');
            $totalFiltered = (int)$this->executeQuery("SELECT COUNT(*) {$from} {$where}", $paramsQuery, 'column');
            
            $sql = "SELECT cb.id_combate, cb.fecha, cb.ronda, cb.decision_victoria, cb.resultado,
                    t.nombre_torneo,
                    CONCAT(al.pnombre, ' ', al.papellido) AS local_nombre,
                    CONCAT(av.pnombre, ' ', av.papellido) AS visitante_nombre
                    {$from} {$where} {$orderBy}";
            
            if ($length != -1) $sql .= " LIMIT :length OFFSET :start";
            
            $stmt = $this->db->prepare($sql);
            foreach ($paramsQuery as $key => $val) $stmt->bindValue($key, $val, PDO::PARAM_STR);
            if ($length != -1) {
                $stmt->bindValue(':length', $length, PDO::PARAM_INT);
                $stmt->bindValue(':start', $start, PDO::PARAM_INT);
            }
            $stmt->execute();
            $data = $stmt->fetchAll();
            
            return [
                'draw' => $draw,
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalFiltered,
                'data' => $data
            ];
        } catch (Exception $e) {
            error_log("[Combate::listarServerSide] " . $e->getMessage());
            return ['draw' => 1, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []];
        }
    }
}
