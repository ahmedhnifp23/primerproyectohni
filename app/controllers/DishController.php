<?php

require_once __DIR__ . "/../DAOs/DishDAO.php";

class DishController
{

    //Variable where will save the instance of the DishDAO.
    private DishDAO $dishDAO;

    public function __construct()
    {
        $this->dishDAO = new DishDAO();
    }

    //Method to list all the dishes and show them on a view.
    public function index()
    {
        $dishes = $this->dishDAO->findAll();
        $view = __DIR__ . "/../views/dishes/listDishes.php";
        require_once __DIR__ . "/../views/main.php";
    }

    //Method to obtain a dish by the topic and send it to the HomeController to render it on the home view.
    public function getDishesByTopic($topic)
    {
        $dishesByTopic = $this->dishDAO->findByTopic($topic);
        return $dishesByTopic;
    }
}
