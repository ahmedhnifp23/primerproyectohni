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


    private $json = null;
    private $data = null;

    public function __construct()
    {
        require_once DAOS_PATH . "DishDAO.php";
        require_once __DIR__ . "/../../../core/JsonUtils.php";
        require_once __DIR__ . "/../../../models/Dish.php";
        $this->dishDAO = new DishDAO();
        $this->method = $_SERVER['REQUEST_METHOD'];
        //We save in a variable the input receibed to the file in a JSON format.
        $this->json = file_get_contents('php://input');
        //We transform this JSON into an associative array to treat it in php.
        $this->data = json_decode($this->json, true);
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
                $this->handlePostRequest();
                break;
            case 'PUT':
                $this->handlePutRequest();
                break;
            case 'DELETE':
                $this->handleDeleteRequest();
                break;
            default:
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

            $dish = JsonUtils::serialize($this->dishDAO->findById($id));

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
            $dishes = JsonUtils::serializeArray($dishes);

            if (count($dishes) > 0) {
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


    public function handlePostRequest()
    {

        if (isset($this->data['dish_name']) && isset($this->data['dish_description']) && isset($this->data['base_price']) && isset($this->data['images']) && isset($this->data['available']) && isset($this->data['category'])) {
            $dish = new Dish(
                dish_id: null,
                dish_name: $this->data['dish_name'],
                dish_description: $this->data['dish_description'],
                topic: $this->data['topic'] ?? null,
                base_price: $this->data['base_price'],
                images: $this->data['images'],
                available: $this->data['available'],
                category: $this->data['category']
            );
            try {
                $createDish = $this->dishDAO->create($dish);
                $successMessage = "Created successfully the dish with id \"" . $createDish . "\".";
                http_response_code(201);
                echo json_encode([
                    'status' => 'Success',
                    'data' => $successMessage
                ]);
            } catch (Exception $e) {
                $error_message = "Error creating the dish: " . $e->getMessage();
                http_response_code(400);
                echo json_encode([
                    'status' => 'Failed',
                    'data' => $error_message
                ]);
                return;
            }
        } else {
            http_response_code(400);
            echo json_encode([
                'status' => 'Failed',
                'data' => 'No data found.'
            ]);
        }
    }


    public function handlePutRequest()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->exists = false;

            try {
                $dish = $this->dishDAO->findById($id);
                if (!$dish) {
                    http_response_code(404);
                    echo json_encode([
                        'status' => 'Failed',
                        'data' => 'No dish found with the given id.'
                    ]);
                    return;
                }
                $this->exists = true;
                $dish->setDishName($this->data['dish_name'] ?? $dish->getDishName());
                $dish->setDishDescription($this->data['dish_description'] ?? $dish->getDishDescription());
                $dish->setTopic($this->data['topic'] ?? $dish->getTopic());
                $dish->setBasePrice($this->data['base_price'] ?? $dish->getBasePrice());
                $dish->setImages($this->data['images'] ?? $dish->getImages());
                $dish->setAvailable($this->data['available'] ?? $dish->getAvailable());
                $dish->setCategory($this->data['category'] ?? $dish->getCategory());

                try {
                    $this->dishDAO->update($dish);
                    $success_message = "Dish with id " . $id . " successfully updated.";
                    http_response_code(200);
                    echo json_encode([
                        'status' => 'Success',
                        'data' => $success_message
                    ]);
                } catch (Exception $e) {
                    $error_message = "Error triying to update the dish with id " . $id . " : " . $e->getMessage();
                    http_response_code(500);
                    echo json_encode([
                        'status' => 'Failed',
                        'data' => $error_message
                    ]);
                }
            } catch (Exception $e) {
                $error_message = "Error obtaining the dish to update: " . $e->getMessage();
                http_response_code(404);
                echo json_encode([
                    'status' => 'Failed',
                    'data' => $error_message
                ]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                'status' => 'Failed',
                'data' => 'No dish id received.'
            ]);
            return;
        }
    }


    public function handleDeleteRequest()
    {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode([
                'status' => 'Failed',
                'data' => 'No dish id received.'
            ]);
            return;
        }

        $id = $_GET['id'];

        try {
            $destroyed = $this->dishDAO->destroy($id);
            if ($destroyed > 0) {
                $status = 'Success';
                $message = 'Dish with id ' . $id . ' deleted successfully';
                $responseCode = 200;
            } else {
                $status = 'Failed';
                $message = 'No dish deleted with the id ' . $id . '. Try it again please.';
                $responseCode = 404;
            }
            http_response_code($responseCode);
            echo json_encode([
                'status' => $status,
                'data' => $message
            ]);
        } catch (Exception $e) {
            $error_message = 'Internal error trying to delte the dish with id ' . $id . ': ' . $e->getMessage();
            http_response_code(500);
            echo json_encode([
                'status' => 'Failed',
                'data' => $error_message
            ]);
            return;
        }
    }
}
