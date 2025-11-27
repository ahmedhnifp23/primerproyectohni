<?php

require_once __DIR__ . "/../core/DatabasePDO.php";
require_once __DIR__ . "/../models/Dish.php";
require_once __DIR__ . "/../core/JsonUtils.php";

class DishDAO
{
    private $db; //Variable where i save the instance of the PDO.
    private $conn; //Variable where i save the connection.
    private $table = 'dishes'; //Variable with the name of the table.
    private $dishes = []; //Variable where I will save the array of dishes.
    private $jsonUtils; //Instance of json utils.

    //Construct with a instance of dbPDO and model Dish.
    public function __construct()
    {
        $this->db = new DatabasePDO();
        $this->jsonUtils = new JsonUtils();
        
    }

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
            return JsonUtils::serializeArray($this->dishes);
        } catch (PDOException $e) {
            $this->db->disconnect();
            die('Error haciendo la consulta select: ' . $e->getMessage());
        }
    }

    public function findAvailable() {}

    public function findById(int $id) {
        //Obtain the connection.
        $this->conn = $this->db->getConnection();
        //Create the query string.
        $query = "SELECT * FROM " . $this->table . " WHERE dish_id = :id";
        //Prepare the statement.
        $stmt = $this->conn->prepare($query);
        //Bind the param.
        $stmt->bindParam(':id', $id);
        //Try-Catch to controll the exception during the consult.
        try{
            $stmt->execute();
            $dishData = $stmt->fetch();

            if($dishData){
                $dish = new Dish(
                    dish_id : $dishData['dish_id'],
                    dish_name : $dishData['dish_name'],
                    dish_description : $dishData['dish_description'],
                    topic : $dishData['topic'],
                    base_price : $dishData['base_price'],
                    images : json_decode($dishData['images'], true),
                    available : $dishData['available'],
                    category : $dishData['category']
                );

            }
            $this->db->disconnect();

            return JsonUtils::serialize($dish);
        } catch(PDOException $e){
            $this->db->disconnect();
            die("Error al intentar obtener el plato seleccionado: " . $e->getMessage());
        }

        
    }

    public function create(Dish $id) {
        $this->conn = $this->db->getConnection();

    }

    public function update(Dish $dish) {
        //ExampleQuery = "update dishes SET topic = 1 WHERE dish_id = '3'"
    }

    public function delete(int $id) {}


    public function findByCategory(string $category) {}

    public function findPopular() {}

    public function methodNotFound(string $action){
        echo "Method " . $action . " not found!!!";
    }
}
