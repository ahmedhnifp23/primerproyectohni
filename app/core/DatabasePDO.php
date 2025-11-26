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
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';

        try {
            $this->conn = new PDO($dsn, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Conexión fallida: ' . $e->getMessage());
        }
    }



    //Function that returns the connection.
    public function getConnection(): PDO
    {
        return $this->conn;
    }
    //Function that closes the connection.
    public function disconnect(): void
    {
        $this->conn = null;
    }
}
