<?php 

//Front controller of the API

//Define the raw HTTP headers for the clients, allowing any origin, the defined methods and content type.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Define the constant with the path of this API.
//define(API_CONTROLLER_PATH, __DIR__ . "./../public/api.php");

//We save in a variable the requested method.
$method = $_SERVER['REQUEST_METHOD'];

//We respond accoding to the method
switch ($method) {
    case 'GET':
        if(isset($_GET['id'])){
            $exits = false;
            
            


        } else{
            http_response_code(404);
            echo json_encode([
                'status' => 'Failed',
                'data' => 'No data found'
            ]);
        }

    case 'POST':

        break;
    case 'PUT':

        break;
    case 'DELETE':

        break;
    default:
        //Method not allowed
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}

