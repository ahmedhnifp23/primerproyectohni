<?php


class APIOrder
{

    //We save in a variable the requested method.
    private $method = '';

    //Variable where i will save the instance of the DAO.
    private $orderDAO = null;

    //Variable to manage if the order exists.
    private $exists = false;


    private $json = null;
    private $data = null;

    public function __construct()
    {
        require_once DAOS_PATH . "OrderDAO.php";
        require_once CORE_PATH . "JsonUtils.php";
        require_once MODELS_PATH . "Order.php";

        $this->orderDAO = new OrderDAO();
        $this->method = $_SERVER['REQUEST_METHOD'];
        
        //detect if the input is json or form-data
        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if (strpos($contentType, 'application/json') !== false) {
            //We save in a variable the input receibed to the file in a JSON format.
            $this->json = file_get_contents('php://input');
            //We transform this JSON into an associative array to treat it in php.
            $this->data = json_decode($this->json, true);
        } else {
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
                if (isset($this->data['order_id']) && !empty($this->data['order_id'])) {
                    $this->handleUpdateRequest($this->data['order_id']);
                } else {
                    $this->handleCreateRequest();
                }
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

        if (isset($_GET['order_id'])) {
            $id = $_GET['order_id'];
            $this->exists = false;

            $order = JsonUtils::serialize($this->orderDAO->findById($id));

            if ($order != null) {
                echo json_encode([
                    'status' => 'Success',
                    'data' => $order
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
            $orders = $this->orderDAO->findAll();
            $orders = JsonUtils::serializeArray($orders);

            if (count($orders) > 0) {
                echo json_encode([
                    'status' => 'Success',
                    'data' => $orders
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


    public function handleCreateRequest()
    {
        //Check if the data is set. We don't check for discount_id, rating or notes because they are optional.
        if (isset($this->data['user_id']) && isset($this->data['delivery_addr']) && isset($this->data['ordered_at']) && isset($this->data['total_amount']) && isset($this->data['order_status'])) {

            //Create the order object
            $order = new Order(
                order_id: isset($this->data['order_id']) ? (int)$this->data['order_id'] : 0, // 0 for auto-increment in DB
                user_id: (int)$this->data['user_id'],
                discount_id: !empty($this->data['discount_id']) ? (int)$this->data['discount_id'] : null,
                delivery_addr: $this->data['delivery_addr'],
                ordered_at: $this->data['ordered_at'],
                total_amount: (float)$this->data['total_amount'],
                order_status: (int)$this->data['order_status'],
                rating: !empty($this->data['rating']) ? (int)$this->data['rating'] : null,
                notes: !empty($this->data['notes']) ? $this->data['notes'] : null
            );

            try {
                $createOrder = $this->orderDAO->create($order);
                $successMessage = "Created successfully the order with id \"" . $createOrder . "\".";
                http_response_code(201);
                echo json_encode([
                    'status' => 'Success',
                    'data' => $successMessage
                ]);
            } catch (Exception $e) {
                $error_message = "Error creating the order: " . $e->getMessage();
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


    public function handleUpdateRequest($id)
    {
        if ($id) {
            $this->exists = false;

            try {
                //Find the order to update by the id received. If not found respond with 404 code
                $order = $this->orderDAO->findById($id);
                if (!$order) {
                    http_response_code(404);
                    echo json_encode([
                        'status' => 'Failed',
                        'data' => 'No order found with the given id.'
                    ]);
                    return;
                }

                //Set the new values to the order object. If the value is not in the request, we keep the old one.
                $this->exists = true;
                
                $order->setUserId($this->data['user_id'] ?? $order->getUserId());
                // For nullable fields, we check if key exists to allow setting to null explicitly if needed, 
                // but since form-data usually sends empty string for empty inputs, we handle that.
                if (array_key_exists('discount_id', $this->data)) {
                    $order->setDiscountId(!empty($this->data['discount_id']) ? (int)$this->data['discount_id'] : null);
                }
                
                $order->setDeliveryAddr($this->data['delivery_addr'] ?? $order->getDeliveryAddr());
                $order->setOrderedAt($this->data['ordered_at'] ?? $order->getOrderedAt());
                $order->setTotalAmount($this->data['total_amount'] ?? $order->getTotalAmount());
                $order->setOrderStatus($this->data['order_status'] ?? $order->getOrderStatus());
                
                if (array_key_exists('rating', $this->data)) {
                    $order->setRating(!empty($this->data['rating']) ? (int)$this->data['rating'] : null);
                }
                
                if (array_key_exists('notes', $this->data)) {
                    $order->setNotes(!empty($this->data['notes']) ? $this->data['notes'] : null);
                }

                try {
                    $this->orderDAO->update($order);
                    $success_message = "Order with id " . $id . " successfully updated.";
                    http_response_code(200);
                    echo json_encode([
                        'status' => 'Success',
                        'data' => $success_message
                    ]);
                } catch (Exception $e) {
                    $error_message = "Error triying to update the order with id " . $id . " : " . $e->getMessage();
                    http_response_code(500);
                    echo json_encode([
                        'status' => 'Failed',
                        'data' => $error_message
                    ]);
                }
            } catch (Exception $e) {
                $error_message = "Error obtaining the order to update: " . $e->getMessage();
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
                'data' => 'No order id received.'
            ]);
            return;
        }
    }


    public function handleDeleteRequest()
    {
        if (!isset($_GET['order_id'])) {
            http_response_code(400);
            echo json_encode([
                'status' => 'Failed',
                'data' => 'No order id received.'
            ]);
            return;
        }

        $id = $_GET['order_id'];

        try {
            $destroyed = $this->orderDAO->destroy($id);
            if ($destroyed > 0) {
                $status = 'Success';
                $message = 'Order with id ' . $id . ' deleted successfully';
                $responseCode = 200;
            } else {
                $status = 'Failed';
                $message = 'No order deleted with the id ' . $id . '. Try it again please.';
                $responseCode = 404;
            }
            http_response_code($responseCode);
            echo json_encode([
                'status' => $status,
                'data' => $message
            ]);
        } catch (Exception $e) {
            if ($e->getCode() == '23000') {
               $error_message = 'Cannot delete order because of integrity constraints (e.g. related records exist).';
               http_response_code(409);
            } else {
               $error_message = 'Internal error trying to delete the order with id ' . $id . ': ' . $e->getMessage();
               http_response_code(500);
            }
            echo json_encode([
                'status' => 'Failed',
                'data' => $error_message
            ]);
            return;
        }
    }
}
