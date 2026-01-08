<?php

class DatabasePDO
{
    //Variables that will use to create the Database Source Name
    private string $host = 'localhost';
    private string $dbname = 'thalassa_db';
    private string $user = 'root';
    private string $password = 'Asdqwe!23';

    //Variable where will save the PDO.
    private $conn = null;

    //Constructor of the class that creates the connection when an instance is created.
    public function __construct()
    {
        // Prioritize environment variables (Docker), fallback to class properties (Local/XAMPP)
        $host = getenv('DB_HOST') ?: $this->host;
        $dbname = getenv('DB_NAME') ?: $this->dbname;
        $user = getenv('DB_USER') ?: $this->user;
        $password = getenv('DB_PASSWORD') ?: $this->password;

        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8mb4';

        try {
            $this->conn = new PDO($dsn, $user, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Conexión fallida: ' . $e->getMessage());
        }
    }

    public function reConnect()
    {
        $this->__construct();
        return $this->conn;
    }

    //Function that returns the connection.
    public function getConnection(): PDO
    {
        if ($this->conn == null) {
            $this->reConnect();
        }
        return $this->conn;
    }
    //Function that closes the connection.
    public function disconnect(): void
    {
        $this->conn = null;
    }
}
