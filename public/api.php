<?php

//Front controller of the API

//Define the raw HTTP headers for the clients, allowing any origin, the defined methods and content type.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Define the constant with the path of the API controllers.
define('API_CONTROLLERS_PATH', __DIR__ . "/../app/http/controllers/API/");

//Test url: http://primerproyectohni.com/?controller=api&endpoint=dish&id=3

$endpoint = '';
$method = $_SERVER['REQUEST_METHOD'];
$controllerInstance = null;




//Check if the endpoint is set, serialize it and save it in a variable and then process the request.
if (isset($_GET['endpoint'])) {
    $endpoint = $_GET['endpoint'];
    $className = 'API' . ucfirst($endpoint);
    $filePath = API_CONTROLLERS_PATH . $className . ".php";

    if (file_exists($filePath)) {
        require_once $filePath;
        $controllerInstance = new $className();
        $controllerInstance->handleRequest();
        
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => 'Failed',
            'data' => 'Endpoint not found'
        ]);
        exit();
    }
} else {
    http_response_code(404);
    echo json_encode([
        'status' => 'Failed',
        'data' => 'No endpoint specified'
    ]);
    exit();
}



