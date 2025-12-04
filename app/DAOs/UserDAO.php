<?php

require_once CORE_PATH . "DatabasePDO.php";
require_once MODELS_PATH . "User.php";
require_once CORE_PATH . "JsonUtils.php";


class UserDAO
{
    private $db; //Variable where i save the instance of the PDO.
    private $conn; //Variable where i save the connection.
    private $table = 'users'; //Variable with the name of the table.
    private ?array $users; //Variable where I will save the array of users.
    private $jsonUtils; //Instance of json utils.

    //Construct with a instance of dbPDO and model User.
    public function __construct()
    {
        $this->db = new DatabasePDO();
        $this->jsonUtils = new JsonUtils();
    }


    public function findAll(){
        $this->conn = $this->db->getConnection();
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $this->users = [];
        try{
            $stmt->execute();
            $usersData = $stmt->fetchAll();
            foreach($usersData as $u){
                $user = new User(
                    user_id : $u['user_id'],
                    first_name : $u['first_name'],
                    email : $u['email'],
                    username : $u['username'],
                    password_hash: $u['password_hash'],
                    phone: $u['phone'],
                    addresses: $u['addresses'],
                    birth_date: $u['birth_date'],
                    registered_at: $u['registered_at'],
                    is_admin: $u['is_admin']
                );
                array_push($this->users, $user);
            }
            $this->db->disconnect();
            return $this->users;
        } catch(PDOException $e){
            throw $e;
        }

    }

    public function findById(int $id){
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id" , $id);

        try{
            $stmt->execute();
            $usersData = $stmt->fetch();
            if($usersData){
                $user = new User(
                    user_id: $usersData['user_id'],
                    first_name: $usersData['first_name'],
                    last_name: $usersData['last_name'],
                    email: $usersData['email'],
                    username: $usersData['username'],
                    password_hash: $usersData['password_hash'],
                    phone: $usersData['phone'],
                    addresses: json_decode($usersData['addresses'], true),
                    birth_date: $usersData['birth_date'],
                    registered_at: $usersData['registered_at'],
                    is_admin: $usersData['is_admin']
                );
            }
            $this->db->disconnect();
            return $user;
        } catch(PDOException $e){
            $this->db->disconnect();
            throw $e;
        }
    }









}
