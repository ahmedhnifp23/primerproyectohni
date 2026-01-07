<?php

require_once CORE_PATH . "DatabasePDO.php";
require_once MODELS_PATH . "Logs.php";

class LogsDAO
{
    private $db;
    private $conn;
    private $table = 'logs';
    private ?array $logs;

    public function __construct()
    {
        $this->db = new DatabasePDO();
    }

    //We only need this method to find all logs and not create update or delete.
    public function findAll()
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " ORDER BY done_at DESC";
        $stmt = $this->conn->prepare($query);
        $this->logs = [];
        try {
            $stmt->execute();
            $logsData = $stmt->fetchAll();
            foreach ($logsData as $l) {
                $log = new Logs(
                    log_id: $l['log_id'],
                    user_id: $l['user_id'],
                    action: $l['action'],
                    table_name: $l['table_name'],
                    done_at: $l['done_at']
                );
                array_push($this->logs, $log);
            }
            $this->db->disconnect();
            return $this->logs;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }
}
