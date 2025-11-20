<?php

//We create the class of the database PHP Data Object that will use the DAO models to obtain the data. That cannot me instantied and will be singleton.
class DatabasePDO
{
    //Variables that will use to create the Database Source Name
    private static string $host = 'localhost';
    private static string $dbname = 'thalassa_db';
    private static string $user = 'root';
    private static string $password = 'Asdqwe!23';


    private static $conn;



    //Function that will realize the connection to the DB with the values defined on tha variables.
    public static function connect()
    {
        if (self::$conn == null) {
            $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$dbname . ';charset=utf8mb4';

            try {
                self::$conn = new PDO($dsn, self::$user, self::$password);
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                var_dump('Conexión con la base de datos ' . self::$dbname . ' realizada correctamente');
            } catch (PDOException $e) {
                die('Conexión fallida: ' . $e->getMessage());
            }
        }

        return self::$conn;
    }

    public static function disconnect(){
        self::$conn = null;
    }
}
