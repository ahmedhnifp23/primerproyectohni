<?php
// Front controller of thalassa website that implements the dinamic instantiation

//Base path
define('BASE_PATH', __DIR__ . "/../app/controllers/");


//Variable with the instance of controller.
$controllerInstance = null;

//Variables with the controller and action obtained with method GET. By default it will show the home page.
$controller = $_GET['controller'] ?? 'Home';
$action = $_GET['action'] ?? 'index';

if ($controller === 'api') {
    require_once __DIR__ . "/api.php";
    
} else {
    $className = ucfirst($controller) . 'Controller';

    $filePath = BASE_PATH . $className . ".php";


    if (!file_exists($filePath)) {
        $className = 'ErrorController';
        $filePath = BASE_PATH . $className . ".php";
        $action = 'error404';
        require_once $filePath;
        $controllerInstance = new $className();
        $controllerInstance->$action();
    } else {
        require_once $filePath;
        $controllerInstance = new $className();
    }

    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        $controllerInstance->methodNotFound($action);
    }
}
