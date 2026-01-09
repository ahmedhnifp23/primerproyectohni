<?php
require_once __DIR__ . "/../controllers/DishController.php";

class HomeController {

    private DishController $dishController;

    //Constructor instantiates the dishcontroller
    public function __construct()
    {
        $this->dishController = new DishController();
    }
    
    //Loads the home page view. Obtain dishes by topic 'Mar' and 'Montaña', configure chunk, and sends the view to the main.
    public function index() {
        $dishesByTopicMar = $this->dishController->getDishesByTopic('Mar');
        $dishesByTopicMontaña = $this->dishController->getDishesByTopic('2');
        
        //Chunk the dishes into groups of 4
        $chunksMar = array_chunk($dishesByTopicMar, 4);
        $chunksMontaña = array_chunk($dishesByTopicMontaña, 4);

        $page_id = "home";
        $page_title = "Inicio";
        
        $view = VIEWS_PATH . "/home/home.php";
        require_once __DIR__ . "/../views/main.php";
    }

}







?>