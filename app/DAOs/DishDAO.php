<?php

require_once CORE_PATH. "DatabasePDO.php";
require_once MODELS_PATH . "Dish.php";
require_once CORE_PATH . "JsonUtils.php";
require_once CORE_PATH . "SessionManager.php";

class DishDAO
{
    private $db; //Variable where i save the instance of the PDO.
    private $conn; //Variable where i save the connection.
    private $table = 'dishes'; //Variable with the name of the table.
    private ?array $dishes; //Variable where I will save the array of dishes.
    private $jsonUtils; //Instance of json utils.

    //Construct with a instance of dbPDO and model Dish.
    public function __construct()
    {
        $this->db = new DatabasePDO();
        $this->jsonUtils = new JsonUtils();
    }

    //Function to set the current_user_id session variable in the database. That will be used by the triggers.
    private function setSessionUserId()
    {
        SessionManager::start();
        $userId = SessionManager::get('user_id');
        if ($userId) {
            $sql = "SET @current_user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $userId);
            $stmt->execute();
        }
    }

    //Function to find all dishes
    public function findAll()
    {
        $this->conn = $this->db->getConnection();
        $query = 'select * from ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $this->dishes = [];
        try {
            $stmt->execute();
            $dishesData = $stmt->fetchAll();
            foreach ($dishesData as $d) {
                $dish = new Dish(
                    dish_id: $d['dish_id'],
                    dish_name: $d['dish_name'],
                    dish_description: $d['dish_description'],
                    topic: $d['topic'],
                    base_price: $d['base_price'],
                    images: json_decode($d['images'], true),
                    available: $d['available'],
                    category: $d['category']
                );
                array_push($this->dishes, $dish);
            }
            $this->db->disconnect();
            return $this->dishes;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function findAvailable() {}

    public function findById(int $id)
    {
        //Obtain the connection.
        $this->conn = $this->db->getConnection();
        //Create the query string.
        $query = "SELECT * FROM " . $this->table . " WHERE dish_id = :id";
        //Prepare the statement.
        $stmt = $this->conn->prepare($query);
        //Bind the param.
        $stmt->bindParam(':id', $id);
        //Try-Catch to controll the exception during the consult.
        try {
            $stmt->execute();
            $dishData = $stmt->fetch();

            if ($dishData) {
                $dish = new Dish(
                    dish_id: $dishData['dish_id'],
                    dish_name: $dishData['dish_name'],
                    dish_description: $dishData['dish_description'],
                    topic: $dishData['topic'],
                    base_price: $dishData['base_price'],
                    images: json_decode($dishData['images'], true),
                    available: $dishData['available'],
                    category: $dishData['category']
                );
            }
            $this->db->disconnect();

            return $dish;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    //Function that creates a new dish
    public function create(Dish $dish)
    {
        $this->conn = $this->db->getConnection();
        $this->setSessionUserId(); 
        $query = "INSERT INTO " . $this->table . "(dish_name, dish_description, topic, base_price, images, available, category) VALUES(:dish_name, :dish_description, :topic, :base_price, :images, :available, :category)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':dish_name', $dish->getDishName());
        $stmt->bindValue(':dish_description', $dish->getDishDescription());
        $stmt->bindValue(':topic', $dish->getTopic());
        $stmt->bindValue(':base_price', $dish->getBasePrice());
        $stmt->bindValue(':images', json_encode($dish->getImages()));
        $stmt->bindValue(':available', $dish->getAvailable());
        $stmt->bindValue(':category', $dish->getCategory());

        try {
            $stmt->execute();
            $this->db->disconnect();
            return $this->conn->lastInsertId(); //Return the id of the inserted dish.
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    //Function that updates a dish
    public function update(Dish $dish)
    {
        $this->conn = $this->db->getConnection();
        $this->setSessionUserId(); 
        $query = "UPDATE " . $this->table . " SET dish_name = :dish_name, dish_description = :dish_description, topic = :topic, base_price = :base_price, images = :images, available = :available, category = :category WHERE dish_id = :dish_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':dish_id', $dish->getDishId());
        $stmt->bindValue(':dish_name', $dish->getDishName());
        $stmt->bindValue(':dish_description', $dish->getDishDescription());
        $stmt->bindValue(':topic', $dish->getTopic());
        $stmt->bindValue(':base_price', $dish->getBasePrice());
        $stmt->bindValue(':images', json_encode($dish->getImages()));
        $stmt->bindValue(':available', (int)$dish->getAvailable(), PDO::PARAM_INT);
        $stmt->bindValue(':category', $dish->getCategory());

        try {
            $stmt->execute();
            $this->db->disconnect();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function destroy(int $id) {
        $this->conn = $this->db->getConnection();
        $this->setSessionUserId(); 
        $query = "DELETE FROM " . $this->table . " WHERE dish_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        try{
            $stmt->execute();
            $this->db->disconnect();
            return $stmt->rowCount();
        } catch(PDOException $e){
            throw $e;
        }
    }

    //Function to find dishes by category
    public function findByCategory(string $category) {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE category = :category and available = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':category', $category);
        $dishesByCategory = [];
        try {
            $stmt->execute();
            $dishesData = $stmt->fetchAll();
            foreach ($dishesData as $d) {
                $dish = new Dish(
                    dish_id: $d['dish_id'],
                    dish_name: $d['dish_name'],
                    dish_description: $d['dish_description'],
                    topic: $d['topic'],
                    base_price: $d['base_price'],
                    images: json_decode($d['images'], true),
                    available: $d['available'],
                    category: $d['category']
                );
                array_push($dishesByCategory, $dish);
            }
            $this->db->disconnect();
            return $dishesByCategory;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }
    //Function to find dishes by topic
    public function findByTopic(string $topic) {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE topic = :topic and available = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':topic', $topic);
        $dishesByTopic = [];
        try {
            $stmt->execute();
            $dishesData = $stmt->fetchAll();
            foreach ($dishesData as $d) {
                $dish = new Dish(
                    dish_id: $d['dish_id'],
                    dish_name: $d['dish_name'],
                    dish_description: $d['dish_description'],
                    topic: $d['topic'],
                    base_price: $d['base_price'],
                    images: json_decode($d['images'], true),
                    available: $d['available'],
                    category: $d['category']
                );
                array_push($dishesByTopic, $dish);
            }
            $this->db->disconnect();
            return $dishesByTopic;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }


    //To be implemented...
    public function findPopular() {}

    public function methodNotFound(string $action)
    {
        echo "Method " . $action . " not found!!!";
    }
}
