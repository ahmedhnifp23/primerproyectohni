<?php
//Front controller of the API

//Define the raw HTTP headers for the clients, allowing any origin, the defined methods and content type.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Constant with the path of the DAOs.
define('DAOS_PATH', __DIR__ . "/../../../DAOs/");

class APIDish
{

    //We save in a variable the requested method.
    private $method = '';

    //Variable where i will save the instance of the DAO.
    private $dishDAO = null;

    //Variable to manage if the dish exists.
    private $exists = false;

    public function __construct()
    {
        require_once DAOS_PATH . "DishDAO.php";
        $this->dishDAO = new DishDAO();
        $this->method = $_SERVER['REQUEST_METHOD'];
    }


    //We respond accoding to the method
    public function handleRequest()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        switch ($this->method) {
            case 'GET':
                $this->handleGetRequest();
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


    private function handleGetRequest()
    {

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->exists = false;

            $dish = $this->dishDAO->findById($id);


            if ($dish != null) {
                echo json_encode([
                    'status' => 'Success',
                    'data' => $dish
                ]);
                $this->exists = true;
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'Failed',
                    'data' => 'No data found with the given parameter'
                ]);
            }
        } else {
            $dishes = $this->dishDAO->findAll();

            if(count($dishes) > 0){
                echo json_encode([
                    'status' => 'Success',
                    'data' => $dishes
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                'status' => 'Failed',
                'data' => 'No data found.'
                ]);
            }
            
        }
    }


public function handlePostRequest(){
    
}



}
