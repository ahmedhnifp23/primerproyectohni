<?php 

require_once __DIR__ . "/../core/SessionManager.php";

class AdminController{

    
    //Function to show the admin dashboard
    public function index(){
        SessionManager::requireAdmin();
        $path = __DIR__ . "/../../public/admin/index.html";

        //Load the admin dashboard view
        readfile($path);
    }


}







?>