<?php

class Dashboard extends Model{

    
    public function stats(){
        
      return [
             // Atletas
            'atletas' => $this->db->query("SELECT COUNT(*) FROM atletas WHERE activo=1")->fetchColumn(),
            'atletas_inactivos' => $this->db->query("SELECT COUNT(*) FROM atletas WHERE activo=0")->fetchColumn(),
                    
            // Combates
            'combates' => $this->db->query("SELECT COUNT(*) FROM combates")->fetchColumn(),
            'combates_pendientes' =>  $this->db->query("SELECT COUNT(*) FROM combates WHERE resultado='Pendiente'")->fetchColumn(),
            'combates_finalizados' =>  $this->db->query("SELECT COUNT(*) FROM combates WHERE resultado!='Pendiente'")->fetchColumn(),
                    
            // Entrenadores y Clubes
            'entrenadores' =>  $this->db->query("SELECT COUNT(*) FROM entrenadores")->fetchColumn(),
            'clubes' =>  $this->db->query("SELECT COUNT(*) FROM clubes")->fetchColumn(),
                    
            // Torneos (activos en curso)
            'torneos_activos' =>  $this->db->query("SELECT COUNT(*) FROM torneos WHERE fecha_inicio <= CURDATE() AND (fecha_fin IS NULL OR fecha_fin >= CURDATE())")->fetchColumn(),
            'torneos_proximos' =>  $this->db->query("SELECT COUNT(*) FROM torneos WHERE fecha_inicio > CURDATE()")->fetchColumn(),
        ];
    }
            
    public function ultimos(){
        // ========== ÚLTIMOS COMBATES ==========
        return $this->db->query("SELECT cb.*, 
            CONCAT(al.pnombre,' ',al.papellido) local_nombre,
            CONCAT(av.pnombre,' ',av.papellido) visitante_nombre
            FROM combates cb
            LEFT JOIN atletas al ON al.id_atleta=cb.id_atleta_local
            LEFT JOIN atletas av ON av.id_atleta=cb.id_atleta_visitante
            ORDER BY cb.fecha DESC LIMIT 5")->fetchAll();
        
    }

    public function niveles(){
        // ========== ATLETAS POR NIVEL (CORREGIDO) ==========
            // Usamos vista_atletas (que calcula total_combates) y agrupamos por rangos en SQL
           return $this->db->query("
                SELECT 
                    SUM(CASE WHEN total_combates <= 5 THEN 1 ELSE 0 END) AS Novato,
                    SUM(CASE WHEN total_combates BETWEEN 6 AND 20 THEN 1 ELSE 0 END) AS Intermedio,
                    SUM(CASE WHEN total_combates BETWEEN 21 AND 40 THEN 1 ELSE 0 END) AS Avanzado,
                    SUM(CASE WHEN total_combates >= 41 THEN 1 ELSE 0 END) AS Experto
                FROM vista_atletas 
                WHERE activo = 1
            ")->fetch();
    }
    public function combatesMes(){
        // ========== COMBATES DEL MES ==========
              return $this->db->query("SELECT COUNT(*) FROM combates WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())")->fetchColumn();
    }
    
    public function torneosDetalle(){
        // ========== TORNEOS ACTIVOS (DETALLE) ==========
     return $this->db->query("SELECT nombre_torneo, fecha_inicio, fecha_fin FROM torneos WHERE fecha_inicio <= CURDATE() AND (fecha_fin IS NULL OR fecha_fin >= CURDATE()) LIMIT 3")->fetchAll();
    }
            
}
?>
