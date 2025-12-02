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









}
