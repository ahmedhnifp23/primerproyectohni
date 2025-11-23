<?php

require_once __DIR__ . "/../DAOs/DishDAO.php";

class DishController {

    //Variable where will save the instance of the DishDAO.
    private DishDAO $dishDAO;

    public function __construct() {
        $this->dishDAO = new DishDAO();
    }

//Method to list all the dishes.
public function index(){
    $dishes = $this->dishDAO->findAll();
    require_once __DIR__ . "/../views/dishes/listDishes.php" ;
}


}