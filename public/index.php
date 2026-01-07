<?php
// Front controller of thalassa website that implements the dinamic instantiation

//Base path for the diferent folders.
define('CONTROLLERS_PATH', __DIR__ . "/../app/controllers/");
define('MODELS_PATH', __DIR__ . "/../app/models/");
define('DAOS_PATH', __DIR__ . "/../app/DAOs/");
define('VIEWS_PATH', __DIR__ . "/../app/views/");
define('CORE_PATH', __DIR__ . "/../app/core/");

//Import the Session utils and start the session.
require_once CORE_PATH . "SessionManager.php";
SessionManager::start();

//Variable with the instance of controller.
$controllerInstance = null;

//Variables with the controller and action obtained with method GET. By default it will show the home page.
$controller = $_GET['controller'] ?? 'Home';
$action = $_GET['action'] ?? 'index';

if ($controller === 'api') {
    require_once __DIR__ . "/api.php";
} else {
    $className = ucfirst($controller) . 'Controller';

    $filePath = CONTROLLERS_PATH . $className . ".php";

    //If the controller file does not exist we load the ErrorController.
    if (!file_exists($filePath)) {
        $className = 'ErrorController';
        $filePath = CONTROLLERS_PATH . $className . ".php";
        $action = 'error404';

    }
    //Include the controller file path
    require_once $filePath;
    
    //Creathe the instance of the controller class dinamically.
    if (class_exists($className)) {
        $controllerInstance = new $className();
    } else {
        die("Error critico del sistema.");
    }

    //Check if the method exists in the controller class
    if (method_exists($controllerInstance, $action)) {
        //We use call_user_func to call the method dinamically.
        //If there are parameters passed by GET we send them to the method in an array.
        if (!empty($_GET)) {
            $params = $_GET;
            unset($params['controller']);
            unset($params['action']);
            unset($params['order_success']);
            unset($params['error']);
            call_user_func_array([$controllerInstance, $action], $params);
        } else {
            $controllerInstance->$action();
        }
        
    } else {
        //If action or method doesnt exist, redirect to error page
        require_once CONTROLLERS_PATH . "ErrorController.php";
        $errorController = new ErrorController();
        $errorController->error404();
    }
}
