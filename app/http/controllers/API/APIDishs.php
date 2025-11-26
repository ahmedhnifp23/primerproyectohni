<?php

//Front controller of the API

//Define the raw HTTP headers for the clients, allowing any origin, the defined methods and content type.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


class APIDish {

    
 public function __construct()
{
    require_once DAOS_PATH . "DishDAO.php";
    $dishDAO = new DishDAO();
}

//We save in a variable the requested method.
$method = ?$_SERVER['REQUEST_METHOD'];
//Constant with the path of the DAOs.
define('DAOS_PATH', __DIR__ . "/../../../DAOs/");
//Variable where i will save the instance of the DAO.
$dishDAO = null;


//We respond accoding to the method
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $exists = false;

            $dish = $dishDAO->findById($id);


            if ($dish != null) {
                echo json_encode([
                    'status' => 'Success',
                    'data' => $dish
                ]);
                $exists = true;
                break;
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'Failed',
                    'data' => 'No data found with the given parameter'
                ]);
            }
        } else {
            


            echo json_encode([
                'status' => 'Failed',
                'data' => 'No data found with the given parameter'
            ]);
        }

        break;
    case 'POST':
        echo json_encode(["message" => "Method POST called"]);
        break;
    case 'PUT':
        echo json_encode(["message" => "Method PUT called"]);
        break;
    case 'DELETE':
        echo json_encode(["message" => "Method DELETE called"]);
        break;
    default:
        //Method not allowed
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}

}

?>
    
