<?php

require_once CORE_PATH . "DatabasePDO.php";
require_once MODELS_PATH . "User.php";
require_once CORE_PATH . "JsonUtils.php";


class UserDAO
{
    private $db; //Variable where i save the instance of Database class.
    private $conn; //Variable where i save the PDO.
    private $table = 'users'; //Variable with the name of the table.
    private ?array $users; //Variable where I will save the array of users.
    private $jsonUtils; //Instance of json utils.
    private ?User $user; //Variable to save the user.

    //Construct with a instance of dbPDO and model User.
    public function __construct()
    {
        $this->db = new DatabasePDO();
        $this->jsonUtils = new JsonUtils();
    }


    public function findAll()
    {
        $this->conn = $this->db->getConnection();
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $this->users = [];
        try {
            $stmt->execute();
            $usersData = $stmt->fetchAll();
            foreach ($usersData as $u) {
                $user = new User(
                    user_id: $u['user_id'],
                    first_name: $u['first_name'],
                    email: $u['email'],
                    username: $u['username'],
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
        } catch (PDOException $e) {
            throw $e;
        }
    }

    //Function to find an user by his user_id.
    public function findById(int $id)
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":id", $id);

        try {
            $stmt->execute();
            $usersData = $stmt->fetch();
            if ($usersData) {
                $this->user = new User(
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
            } else{
                $this->user = null;
            }
            $this->db->disconnect();
            return $this->user;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    //Function to find an user by his email. This will be used in the login.
    public function findByEmail(string $email)
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':email', $email);

        try {
            $stmt->execute();
            $userData = $stmt->fetch();

            if ($userData) {
                $this->user = new User(
                    user_id: $userData['user_id'],
                    first_name: $userData['first_name'],
                    last_name: $userData['last_name'],
                    email: $userData['email'],
                    username: $userData['username'],
                    password_hash: $userData['password_hash'],
                    phone: $userData['phone'],
                    addresses: json_decode($userData['addresses']),
                    birth_date: $userData['birth_date'],
                    registered_at: $userData['registered_at'],
                    is_admin: $userData['is_admin']
                );
            } else{
                $this->user = null;
            }
            $this->db->disconnect();
            return $this->user;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

        public function findByUsername(string $username)
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':username', $username);

        try {
            $stmt->execute();
            $userData = $stmt->fetch();

            if ($userData) {
                $this->user = new User(
                    user_id: $userData['user_id'],
                    first_name: $userData['first_name'],
                    last_name: $userData['last_name'],
                    email: $userData['email'],
                    username: $userData['username'],
                    password_hash: $userData['password_hash'],
                    phone: $userData['phone'],
                    addresses: json_decode($userData['addresses']),
                    birth_date: $userData['birth_date'],
                    registered_at: $userData['registered_at'],
                    is_admin: $userData['is_admin']
                );
            } else{
                $this->user = null;
            }
            $this->db->disconnect();
            return $this->user;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    //Function to create a new user.
    public function create(User $user){
        $this->conn = $this->db->getConnection();
        $query = "INSERT INTO " . $this->table . " (first_name, last_name, email, username, password_hash, phone, addresses, birth_date) VALUES(:first_name, :last_name, :email, :username, :password_hash, :phone, :addresses, :birth_date)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':first_name', $user->getFirstName());
        $stmt->bindValue(':last_name', $user->getLastName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':password_hash', $user->getPasswordHash());
        $stmt->bindValue(':phone', $user->getPhone());
        $stmt->bindValue(':addresses', json_encode($user->getAddresses()));
        $stmt->bindValue(':birth_date', $user->getBirthDate());

        try{
            $stmt->execute();
            $this->db->disconnect();
            return $this->conn->lastInsertId();
        } catch(PDOException $e){
            $this->db->disconnect();
            throw $e;
        }
    }

    public function update(User $user)
    {
        $this->conn = $this->db->getConnection();
        $query = "UPDATE " . $this->table . " SET first_name = :first_name, last_name = :last_name, email = :email, username = :username, password_hash = :password_hash, phone = :phone, addresses = :addresses, birth_date = :birth_date WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $user->getUserId());
        $stmt->bindValue(':first_name', $user->getFirstName());
        $stmt->bindValue(':last_name', $user->getLastName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':password_hash', $user->getPasswordHash());
        $stmt->bindValue(':phone', $user->getPhone());
        $stmt->bindValue(':addresses', json_encode($user->getAddresses()));
        $stmt->bindValue(':birth_date', $user->getBirthDate());

        try {
            $stmt->execute();
            $this->db->disconnect();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

}
