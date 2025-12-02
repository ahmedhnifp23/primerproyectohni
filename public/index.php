<?php
// Front controller of thalassa website that implements the dinamic instantiation

//Base path for the diferent folders.
define('CONTROLLERS_PATH', __DIR__ . "/../app/controllers/");
define('MODELS_PATH', __DIR__ . "/../app/models/");
define('DAOS_PATH', __DIR__ . "/../app/DAOs/");
define('VIEWS_PATH', __DIR__ . "/../app/views/");
define('CORE_PATH', __DIR__ . "/../app/core/");


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


    if (!file_exists($filePath)) {
        $className = 'ErrorController';
        $filePath = CONTROLLERS_PATH . $className . ".php";
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
