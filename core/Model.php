<?php
/**
 * Modelo base. Provee acceso a la conexión PDO.
 */
abstract class Model {
    protected PDO $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Ejecuta una consulta preparada con parámetros nombrados
     * 
     * @param string $sql Consulta SQL con placeholders nombrados (:param)
     * @param array $params Parámetros para la consulta
     * @param string $fetchType Tipo de fetch: 'all', 'one', 'column', 'stmt'
     * @return mixed Resultado de la consulta
     */
    protected function executeQuery(string $sql, array $params = [], string $fetchType = 'all'): mixed {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return match($fetchType) {
            'all' => $stmt->fetchAll(),
            'one' => $stmt->fetch(),
            'column' => $stmt->fetchColumn(),
            'stmt' => $stmt,
            default => $stmt->fetchAll()
        };
    }
    public function getDb(): PDO {
        return $this->db;
    }
}