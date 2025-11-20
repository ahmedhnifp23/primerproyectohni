<?php

require_once __DIR__ . "/../core/DatabasePDO.php";
require_once __DIR__ . "/../models/Dish.php"

class DishDAO{
    private PDO $db;//Objeto donde almaceno el PDO.
    private $conn;


    public function __construct()
    {
        $this->db = new DatabasePDO();

    }

    public static function findAll(){
        $this->db ->connect(); 

        $query = "SELECT * FROM dishes";

        $this->db->disconnect();
    }

    public static function findAvailable(){

    }

    public static function findById(int $id){

    }

    public static function create(Dish $id){

    }

    public static function update(Dish $dish){

    }

    public static function delete(int $id){

    }


    public static function findByCategory(string $category){

    }

    public static function findPopular(){

    }



}