<?php

require_once MODELS_PATH . "User.php";
require_once DAOS_PATH . "UserDAO.php";
require_once CORE_PATH . "SessionManager.php";


class UserController{
    //Variable will save the instance of the UserDAO.
    private UserDAO $userDAO;

    public function __construct()
    {
        $this->userDAO = new UserDAO();
    }


    public function showRegister(){

    }
    public function storeRegister(){

    }

    public function showLogin(){
        
    }

    public function storeLogin(){

    }

    public function logout(){

    }


}


?>