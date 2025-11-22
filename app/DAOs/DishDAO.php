<?php

require_once __DIR__ . "/../core/DatabasePDO.php";
require_once __DIR__ . "/../models/Dish.php";

class DishDAO
{
    private $db; //Variable where i save the instance of the PDO.
    private $conn; //Variable where i save the connection.
    private $table = 'dishes'; //Variable with the name of the table.
    private $dish; //Variable where I will save the object of the dish model.

    //Construct with a instance of dbPDO and model Dish.
    public function __construct()
    {
        $this->db = new DatabasePDO();
    }

    public function findAll()
    {
        $this->conn = $this->db->getConnection();
        $query = 'select * from ' . $this->table;
        $stmt = $this->conn->prepare($query);
        try {
            $stmt->execute();
            $dishesData = $stmt->fetchAll();
            $dishes = [];
            foreach ($dishesData as $d) {
                $dish = new Dish(
                    $d['dish_id'],
                    $d['dish_name'],
                    $d['dish_description'],
                    $d['base_price'],
                    $d['images'],
                    $d['available'],
                    $d['category']
                );
                array_push($dishes, $dish);
            }
            $this->db->disconnect();
            return $dishes;
        } catch (PDOException $e) {
            $this->db->disconnect();
            die('Error haciendo la consulta select: ' . $e->getMessage());
        }
    }

    public function findAvailable() {}

    public function findById(int $id) {}

    public function create(Dish $id) {}

    public function update(Dish $dish) {}

    public function delete(int $id) {}


    public function findByCategory(string $category) {}

    public function findPopular() {}
}
