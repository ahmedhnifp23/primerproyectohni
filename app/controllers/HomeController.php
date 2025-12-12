<?php
require_once __DIR__ . "/../controllers/DishController.php";

class HomeController {

    private DishController $dishController;

    public function __construct()
    {
        $this->dishController = new DishController();
    }
    

    public function index() {
        //Carrousell of dishes by topic.
        $dishesByTopic = $this->dishController->getDishesByTopic('Mar');
        $view = VIEWS_PATH . "/home/home.php";
        require_once __DIR__ . "/../views/main.php";
    }

}







?>