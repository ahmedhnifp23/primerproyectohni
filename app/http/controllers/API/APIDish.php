<?php


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
        require_once CORE_PATH . "JsonUtils.php";
        require_once MODELS_PATH . "Dish.php";

        $this->dishDAO = new DishDAO();
        $this->method = $_SERVER['REQUEST_METHOD'];

        //detect if the input is json or form-data
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        
        if (strpos($contentType, 'application/json') !== false) {
            //We save in a variable the input receibed to the file in a JSON format.
            $this->json = file_get_contents('php://input');
            //We transform this JSON into an associative array to treat it in php.
            $this->data = json_decode($this->json, true);
        } else{
            //Is a form-data request
            $this->data = $_POST;
        }

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
                //Check if we are creating or updating if we have the id param or not
                if(isset($this->data['dish_id']) && !empty($this->data['dish_id'])){
                    $this->handleUpdateRequest($this->data['dish_id']);
                } else{
                    $this->handleCreateRequest();
                }
                break;
            //I don't use put here because i'm sending the data with form-data format
            /*case 'PUT':
                $this->handlePutRequest();
                break;*/
            case 'DELETE':
                $this->handleDeleteRequest();
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    //Function to handle the get request
    private function handleGetRequest()
    {

        if (isset($_GET['dish_id'])) {
            $id = $_GET['dish_id'];
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

    //Function to handle the create request
    public function handleCreateRequest()
    {
        //Check if the data is set
        if (isset($this->data['dish_name']) && isset($this->data['dish_description']) && isset($this->data['base_price']) && isset($this->data['available']) && isset($this->data['category'])) {
            //Call the images upload processing function
            $imagesArray = $this->processImageUpload($this->data['category'], $this->data['dish_name']);


            //Create the dish object
            $dish = new Dish(
                dish_id: null,
                dish_name: $this->data['dish_name'],
                dish_description: $this->data['dish_description'],
                topic: $this->data['topic'] ?? null,
                base_price: $this->data['base_price'],
                images: $imagesArray,
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
                'data' => 'No data found or missing required fields.'
            ]);
        }
    }

    //Function to handle the update request
    public function handleUpdateRequest($id)
    {
        if ($id) {
            $this->exists = false;

            try {
                //Find the dish to update by the id received. If not found respond with 404 code
                $dish = $this->dishDAO->findById($id);
                if (!$dish) {
                    http_response_code(404);
                    echo json_encode([
                        'status' => 'Failed',
                        'data' => 'No dish found with the given id.'
                    ]);
                    return;
                }

                //If there are images to delete process them
                if(isset($this->data['deleted_images']) && !empty($this->data['deleted_images'])){
                    $deletedPaths = json_decode($this->data['deleted_images'], true);
                    $remainingImages = $this->processImageDeletion($dish->getImages(), $deletedPaths);
                }

                //If there are new images to upload process them with the new category if it's another
                $category = $this->data['category'] ?? $dish->getCategory();
                $dishName = $this->data['dish_name'] ?? $dish->getDishName();
                $newUploadedImages = $this->processImageUpload($category, $dishName);

                //Merge the remaining images with the new uploaded images
                $finalImagesArray = array_merge($remainingImages, $newUploadedImages);

                //Set the new values to the dish object
                $this->exists = true;
                $dish->setDishName($this->data['dish_name'] ?? $dish->getDishName());
                $dish->setDishDescription($this->data['dish_description'] ?? $dish->getDishDescription());
                $dish->setTopic($this->data['topic'] ?? $dish->getTopic());
                $dish->setBasePrice($this->data['base_price'] ?? $dish->getBasePrice());
                $dish->setImages($finalImagesArray);
                $dish->setAvailable(($this->data['available'] ?? $dish->getAvailable()));
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

    //Function to handle the delete request
    public function handleDeleteRequest()
    {
        if (!isset($_GET['dish_id'])) {
            http_response_code(400);
            echo json_encode([
                'status' => 'Failed',
                'data' => 'No dish id received.'
            ]);
            return;
        }

        $id = $_GET['dish_id'];

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
            if ($e->getCode() == '23000') {
                $error_message = 'Cannot delete dish because it appears in existing orders.';
                http_response_code(409);
            } else {
                $error_message = 'Internal error trying to delete the dish with id ' . $id . ': ' . $e->getMessage();
                http_response_code(500);
            }
            echo json_encode([
                'status' => 'Failed',
                'data' => $error_message
            ]);
            return;
        }
    }


    //Helper function to process the image upload creating the path and moving the file to the server folder
    private function processImageUpload($category, $dishName){
        $uploadedImages = [];

        //check if there are images uploaded
        if(isset($_FILES['dish_images']) && !empty($_FILES['dish_images']['name'][0])){
            //Transform category name to lowercase and replace spaces
            $cleanCategory = strtolower(trim($category));
            //define base upload path
            $baseUploadPath = __DIR__ . "/../../../../public/assets/images/platos/" . $cleanCategory . "/";
            //Path to save in the db
            $baseWebPath = "/assets/images/platos/" . $cleanCategory . "/";

            //Create category directory if not exists
            if(!file_exists($baseUploadPath)){
                mkdir($baseUploadPath, 0777, true);
            }

            //save files in a variable
            $files = $_FILES['dish_images'];
            $numFiles = count($files['name']);
            //Iterate all the files and move them to the server folder
            for($i = 0; $i < $numFiles; $i++){
                //If the is no error continue...
                if($files['error'][$i] === UPLOAD_ERR_OK){
                    //Take the temporary file path where is now located the file
                    $tmpFilePath = $files['tmp_name'][$i];
                    //Take the original name of the file
                    $originalfileName = basename($files['name'][$i]);
                    //Use tha pathinfo function to get the extension of the file
                    $ext = pathinfo($originalfileName, PATHINFO_EXTENSION);

                    //Generate a unique name
                    $fileName = uniqid() . '.' . $ext;
                    //Define the full upload path
                    $uploadFilePath = $baseUploadPath . $fileName;

                    //Move the file from the temporary path to the upload path
                    if(move_uploaded_file($tmpFilePath, $uploadFilePath)){
                        $uploadedImages[] = [
                            'path' => $baseWebPath . $fileName,
                            'alt' => 'Imagen de ' . $dishName
                        ];
                    }
                }
            }
        }
        return $uploadedImages;
    }

    //Helper function to delete images from server and take it out from the images array
    private function processImageDeletion($currentImages, $deletedPaths){
        if(empty($deletedPaths)) return $currentImages;

        //Use the filter function to remove the images with the paths in deletedPaths
        $remainingImages = array_filter($currentImages, function($img) use($deletedPaths){
            //check if the image path is on the deletedPaths array
            if(in_array($img['path'], $deletedPaths)){
                //Convert the web path to server path
                $serverPath = __DIR__ . "/../../../../public" . $img['path'];
                //Check if the file exists and delete it
                if(file_exists($serverPath)){
                    unlink($serverPath);
                }
                return false; //Return false to remove this image from array remainingImages
            }
            return true; //Keep this image in the array
        });
        return $remainingImages;
    }
}
