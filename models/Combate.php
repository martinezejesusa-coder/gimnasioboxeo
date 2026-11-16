<?php
/**
 * Modelo Combate
 * 

 */
class Combate extends Model {
    
    // ==================== PROPIEDADES PRIVADAS ====================
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

    // ==================== SETTERS CON VALIDACIÓN ====================
    
    public function setIdCombate(?int $id): void {
        if ($id !== null && $id <= 0) {
            throw new InvalidArgumentException("ID de combate inválido");
        }
        $this->id_combate = $id;
    }
    
    public function setIdTorneo(?int $id): void {
        if ($id !== null && $id <= 0) {
            throw new InvalidArgumentException("ID de torneo inválido");
        }
        $this->id_torneo = $id;
    }
    
    public function setIdAtletaLocal(?int $id): void {
        if ($id === null || $id <= 0) {
            throw new InvalidArgumentException("El atleta local es requerido");
        }
        $this->id_atleta_local = $id;
    }
    
    public function setIdAtletaVisitante(?int $id): void {
        if ($id === null || $id <= 0) {
            throw new InvalidArgumentException("El atleta visitante es requerido");
        }
        $this->id_atleta_visitante = $id;
    }
    
    public function setFecha(?string $fecha): void {
        if (empty($fecha) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            throw new InvalidArgumentException("Formato de fecha inválido (YYYY-MM-DD)");
        }
        $this->fecha = $fecha;
    }
    
    public function setRonda(?int $ronda): void {
        if ($ronda === null || $ronda <= 0) {
            throw new InvalidArgumentException("La ronda debe ser un número positivo");
        }
        $this->ronda = $ronda;
    }
    
    public function setRoundsPautados(?int $rounds): void {
        if (!in_array($rounds, [3, 6, 8, 10, 12])) {
            throw new InvalidArgumentException("Número de asaltos inválido");
        }
        $this->rounds_pautados = $rounds;
    }
    
    public function setRoundFinalizacion(?int $round): void {
        if ($round !== null && $round <= 0) {
            throw new InvalidArgumentException("El asalto de finalización debe ser mayor a 0");
        }
        $this->round_finalizacion = $round;
    }
    
    public function setDecisionVictoria(?string $decision): void {
        $permitidas = ['KO','TKO','UD','SD','MD','RTD','DQ','NC','UED','SED','MED','TD','TED'];
        if ($decision !== null && !empty($decision) && !in_array($decision, $permitidas)) {
            throw new InvalidArgumentException("Decisión inválida");
        }
        $this->decision_victoria = $decision;
    }
    
    public function setResultado(?string $resultado): void {
        if (!in_array($resultado, ['Pendiente', 'Local', 'Visitante', 'Empate', 'No Contest'])) {
            throw new InvalidArgumentException("Resultado inválido");
        }
        $this->resultado = $resultado;
    }
    
    public function setPosicionLlave(?int $posicion): void {
        if ($posicion !== null && $posicion < 0) {
            throw new InvalidArgumentException("La posición no puede ser negativa");
        }
        $this->posicion_llave = $posicion;
    }

    // ==================== LÓGICA DE NEGOCIO ====================
    
    /**
     * Valida que los atletas involucrados sean diferentes.
     */
    private function validarAtletasDiferentes(): void {
        if ($this->id_atleta_local === $this->id_atleta_visitante) {
            throw new InvalidArgumentException("El atleta local y visitante no pueden ser el mismo");
        }
    }

    // ==================== PERSISTENCIA ====================
    
    /**
     * Lista todos los combates con información relacionada.
     */
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
            return $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            error_log("[Combate::todos] " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene un combate por su ID.
     */
    public function obtener(int $id): ?array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM combates WHERE id_combate = ?");
            $stmt->execute([$id]);
            return $stmt->fetch() ?: null;
        } catch (Exception $e) {
            error_log("[Combate::obtener] " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lista los torneos disponibles para el selector.
     */
    public function torneos(): array {
        try {
            return $this->db->query("SELECT * FROM torneos ORDER BY fecha_inicio DESC")->fetchAll();
        } catch (Exception $e) {
            error_log("[Combate::torneos] " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Registra un nuevo combate en la base de datos.
     */
    public function crear(array $d): int {
        try {
            // Asignación mediante Setters (Encapsulamiento)
            $this->setIdTorneo(!empty($d['id_torneo']) ? (int)$d['id_torneo'] : null);
            $this->setIdAtletaLocal((int)($d['id_atleta_local'] ?? 0));
            $this->setIdAtletaVisitante((int)($d['id_atleta_visitante'] ?? 0));
            $this->setFecha($d['fecha'] ?? '');
            $this->setRonda((int)($d['ronda'] ?? 1));
            $this->setRoundsPautados((int)($d['rounds_pautados'] ?? 3));
            $this->setRoundFinalizacion(!empty($d['round_finalizacion']) ? (int)$d['round_finalizacion'] : null);
            $this->setDecisionVictoria($d['decision_victoria'] ?? null);
            $this->setResultado($d['resultado'] ?? 'Pendiente');
            $this->setPosicionLlave(!empty($d['posicion_llave']) ? (int)$d['posicion_llave'] : null);
            
            // Verificación de reglas de negocio
            $this->validarAtletasDiferentes();
            
            // SQL usando Getters
            $sql = "INSERT INTO combates (
                        id_torneo, id_atleta_local, id_atleta_visitante, 
                        fecha, ronda, rounds_pautados, round_finalizacion, 
                        decision_victoria, resultado, posicion_llave
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $this->getIdTorneo(),
                $this->getIdAtletaLocal(),
                $this->getIdAtletaVisitante(),
                $this->getFecha(),
                $this->getRonda(),
                $this->getRoundsPautados(),
                $this->getRoundFinalizacion(),
                $this->getDecisionVictoria(),
                $this->getResultado(),
                $this->getPosicionLlave()
            ]);
            
            return (int)$this->db->lastInsertId();
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Exception $e) {
            error_log("[Combate::crear] " . $e->getMessage());
            throw new Exception("Error interno al guardar el combate");
        }
    }
    
    /**
     * Actualiza un combate existente.
     */
    public function actualizar(int $id, array $d): bool {
        try {
            $this->setIdCombate($id);
            $this->setIdTorneo(!empty($d['id_torneo']) ? (int)$d['id_torneo'] : null);
            $this->setIdAtletaLocal((int)($d['id_atleta_local'] ?? 0));
            $this->setIdAtletaVisitante((int)($d['id_atleta_visitante'] ?? 0));
            $this->setFecha($d['fecha'] ?? '');
            $this->setRonda((int)($d['ronda'] ?? 1));
            $this->setRoundsPautados((int)($d['rounds_pautados'] ?? 3));
            $this->setRoundFinalizacion(!empty($d['round_finalizacion']) ? (int)$d['round_finalizacion'] : null);
            $this->setDecisionVictoria($d['decision_victoria'] ?? null);
            $this->setResultado($d['resultado'] ?? 'Pendiente');
            $this->setPosicionLlave(!empty($d['posicion_llave']) ? (int)$d['posicion_llave'] : null);
            
            $this->validarAtletasDiferentes();
            
            $sql = "UPDATE combates SET 
                        id_torneo = ?, id_atleta_local = ?, id_atleta_visitante = ?,
                        fecha = ?, ronda = ?, rounds_pautados = ?, round_finalizacion = ?,
                        decision_victoria = ?, resultado = ?, posicion_llave = ?
                    WHERE id_combate = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                $this->getIdTorneo(),
                $this->getIdAtletaLocal(),
                $this->getIdAtletaVisitante(),
                $this->getFecha(),
                $this->getRonda(),
                $this->getRoundsPautados(),
                $this->getRoundFinalizacion(),
                $this->getDecisionVictoria(),
                $this->getResultado(),
                $this->getPosicionLlave(),
                $id
            ]);
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (Exception $e) {
            error_log("[Combate::actualizar] " . $e->getMessage());
            throw new Exception("Error interno al actualizar el combate");
        }
    }
    
    /**
     * Elimina un combate.
     */
    public function eliminar(int $id): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM combates WHERE id_combate = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("[Combate::eliminar] " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Sugiere emparejamientos basados en categoría y rango de combates.
     */
    public function sugerir(int $idCategoria, int $rangoMin, int $rangoMax): array {
        try {
            $stmt = $this->db->prepare("SELECT a.*, c.nombre_club 
                FROM vista_atletas a
                LEFT JOIN clubes c ON c.id_club = a.id_club
                WHERE a.activo = 1 
                AND a.id_categoria = ? 
                AND a.total_combates BETWEEN ? AND ?
                ORDER BY a.total_combates DESC");
            $stmt->execute([$idCategoria, $rangoMin, $rangoMax]);
            $atletas = $stmt->fetchAll();
            
            // Calcular nivel para cada atleta
            foreach ($atletas as &$atleta) {
                $atleta['nivel_calculado'] = Atleta::calcularNivel((int)$atleta['total_combates']);
            }
            unset($atleta);
            
            // Emparejar atletas de 2 en 2
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
                0 => 'cb.fecha',
                1 => 't.nombre_torneo',
                2 => 'al.pnombre',
                3 => 'av.pnombre',
                4 => 'cb.ronda',
                5 => 'cb.decision_victoria',
                6 => 'cb.resultado'
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
                $where = "WHERE (
                    cb.fecha LIKE :search OR
                    t.nombre_torneo LIKE :search OR
                    al.pnombre LIKE :search OR al.papellido LIKE :search OR
                    av.pnombre LIKE :search OR av.papellido LIKE :search OR
                    cb.decision_victoria LIKE :search OR
                    cb.resultado LIKE :search
                )";
                $paramsQuery[':search'] = "%{$searchValue}%";
            }
            
            $totalData = (int)$this->db->query("SELECT COUNT(*) {$from}")->fetchColumn();
            
        
            $stmtFiltered = $this->db->prepare("SELECT COUNT(*) {$from} {$where}");
            $stmtFiltered->execute($paramsQuery);
            $totalFiltered = (int)$stmtFiltered->fetchColumn();
            
            // Datos paginados
            $sql = "SELECT cb.id_combate, cb.fecha, cb.ronda, cb.decision_victoria, cb.resultado,
                    t.nombre_torneo,
                    CONCAT(al.pnombre, ' ', al.papellido) AS local_nombre,
                    CONCAT(av.pnombre, ' ', av.papellido) AS visitante_nombre
                    {$from} {$where} {$orderBy}";
            
            if ($length != -1) {
                $sql .= " LIMIT :length OFFSET :start";
            }
            
            $stmt = $this->db->prepare($sql);
            foreach ($paramsQuery as $key => $val) {
                $stmt->bindValue($key, $val, PDO::PARAM_STR);
            }
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
            return [
                'draw' => 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ];
        }
    }
}