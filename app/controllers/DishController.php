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
    public function index(string $category)
    {
        $page_id = "carta";
        $page_title = $category;
        $dishes = $this->dishDAO->findByCategory($category);
        $view = __DIR__ . "/../views/menu/index.php";
        require_once __DIR__ . "/../views/main.php";
    }

    //Method to obtain a dish by the topic and send it to the HomeController to render it on the home view.
    public function getDishesByTopic(string $topic)
    {
        $dishesByTopic = $this->dishDAO->findByTopic($topic);
        return $dishesByTopic;
    }
}
