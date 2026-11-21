<?php
class TarjetaPuntuacion extends Model {
    
    public function guardarTarjetas(int $idCombate, array $tarjetas): void {
        try {
            $this->db->beginTransaction();
            
            $this->executeQuery(
                "DELETE FROM tarjetas_puntuacion WHERE id_combate = :id",
                [':id' => $idCombate],
                'stmt'
            );
            
            if (!empty($tarjetas)) {
                $sql = "INSERT INTO tarjetas_puntuacion (id_combate, id_juez, numero_round, puntos_local, puntos_visitante) 
                        VALUES (:id_combate, :id_juez, :numero_round, :puntos_local, :puntos_visitante)
                        ON DUPLICATE KEY UPDATE 
                            puntos_local = VALUES(puntos_local), 
                            puntos_visitante = VALUES(puntos_visitante)";
                
                foreach ($tarjetas as $t) {
                    $this->executeQuery($sql, [
                        ':id_combate'       => $idCombate,
                        ':id_juez'          => $t['id_juez'],
                        ':numero_round'     => $t['numero_round'],
                        ':puntos_local'     => $t['puntos_local'],
                        ':puntos_visitante' => $t['puntos_visitante']
                    ], 'stmt');
                }
            }
            
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("[TarjetaPuntuacion] guardarTarjetas(): " . $e->getMessage());
            throw $e;
        }
    }
    
    public function obtenerPorCombate(int $idCombate): array {
        try {
            $sql = "SELECT tp.*, CONCAT(j.pnombre, ' ', j.papellido) AS nombre_juez
                    FROM tarjetas_puntuacion tp
                    LEFT JOIN jueces j ON tp.id_juez = j.id_juez
                    WHERE tp.id_combate = :id
                    ORDER BY tp.id_juez, tp.numero_round";
            return $this->executeQuery($sql, [':id' => $idCombate], 'all');
        } catch (Exception $e) {
            error_log("[TarjetaPuntuacion] obtenerPorCombate(): " . $e->getMessage());
            return [];
        }
    }
    
    public function resumenPorJuez(int $idCombate): array {
        try {
            $sql = "SELECT tp.id_juez, CONCAT(j.pnombre, ' ', j.papellido) AS nombre_juez,
                    COUNT(tp.numero_round) AS rounds,
                    SUM(tp.puntos_local) AS total_local,
                    SUM(tp.puntos_visitante) AS total_visitante
                    FROM tarjetas_puntuacion tp
                    LEFT JOIN jueces j ON tp.id_juez = j.id_juez
                    WHERE tp.id_combate = :id
                    GROUP BY tp.id_juez";
            return $this->executeQuery($sql, [':id' => $idCombate], 'all');
        } catch (Exception $e) {
            error_log("[TarjetaPuntuacion] resumenPorJuez(): " . $e->getMessage());
            return [];
        }
    }
}
