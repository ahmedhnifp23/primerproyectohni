<?php
require_once __DIR__ . '/../core/SessionManager.php';
require_once __DIR__ . '/../DAOs/DishDAO.php';
require_once __DIR__ . '/../DAOs/DiscountDAO.php';
require_once __DIR__ . '/../DAOs/OrderDAO.php';
require_once __DIR__ . '/../DAOs/OrderLineDAO.php';
require_once __DIR__ . '/../DAOs/UserDAO.php'; // Necesario para rellenar el form
require_once __DIR__ . '/../models/OrderLine.php';

class OrderController
{

    //Constructor ensures that the user is logged
    public function __construct()
    {
        SessionManager::requireLogin();
    }

    //Loads the checkout view with user address data prefilled
    public function confirm()
    {
        $userId = SessionManager::get('user_id');

        $userDAO = new UserDAO();
        $user = $userDAO->findById($userId);

        $addrData = $user->getAddresses() ?? [];

        $street = $addrData[0]['street'] ?? '';
        $apartment = $addrData[0]['apartment'] ?? '';
        $city = $addrData[0]['city'] ?? '';
        $province = $addrData[0]['province'] ?? '';
        $postalCode = $addrData[0]['zip'] ?? '';
        $country = $addrData[0]['country'] ?? '';
        
        $page_id = "checkout";
        $page_title = "Confirmar Pedido";

        $view = VIEWS_PATH . '/checkout_view.php';

        require_once VIEWS_PATH . "/main.php";
    }

    //Receives cart in JSON, validates discount code if is set, and renders the checkout summary partial.
    public function previewCart()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $rawLines = $input['lines'] ?? [];

        $discountCode = $input['discount_code'] ?? '';

        $dishDAO = new DishDAO();
        $discountDAO = new DiscountDAO();
        $orderLinesObjects = []; 
        $subtotal = 0;

        foreach ($rawLines as $raw) {
            $orderLine = new OrderLine(
                order_line_id: 0,
                order_id: 0,
                dish_id: (int)$raw['dish_id'],
                unit_price: (float)$raw['unit_price'],
                notes: $raw['notes'] ?? ''
            );

            $dish = $dishDAO->findById($orderLine->getDishId());

            if ($dish) {
                $orderLinesObjects[] = [
                    'lineObj' => $orderLine, 
                    'dishObj' => $dish       
                ];
                $subtotal += $orderLine->getUnitPrice();
            }
        }

        $userId = SessionManager::get('user_id');
        $finalTotal = $subtotal;
        $discountError = null;
        $appliedDiscount = null;
        $discountAmount = 0;

        if (!empty($discountCode)) {
            $discount = $discountDAO->validateDiscount($discountCode, $userId);

            if ($discount) {
                $appliedDiscount = $discount;
                $percent = $discount->getPercent();
                $discountAmount = $subtotal * ($percent / 100);
                $finalTotal = $subtotal - $discountAmount;
            } else {
                $discountError = "El código no es válido o ya lo has utilizado.";
            }
        }

        require VIEWS_PATH . '/partials/checkout_summary.php';
    }

    //Process the order submit, recalculates prices with the saved in db, applies discounts, creates order and orderline records, and redirects.
    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=Order&action=confirm');
            exit;
        }

        try {
            $orderDAO = new OrderDAO();
            $orderLineDAO = new OrderLineDAO();
            $discountDAO = new DiscountDAO();
            $dishDAO = new DishDAO();

            $userId = SessionManager::get('user_id');

            $cartJson = $_POST['cart_json'] ?? '[]';
            $cartLines = json_decode($cartJson, true);

            if (empty($cartLines)) {
                header('Location: index.php?controller=Dish&action=index');
                exit;
            }

            $addressObj = [
                "zip" => $_POST['zip'] ?? '',
                "city" => $_POST['city'] ?? '',
                "street" => $_POST['street'] ?? '', 
                "country" => $_POST['country'] ?? 'España',
                "province" => $_POST['province'] ?? '',
                "apartment" => $_POST['apartment'] ?? ''
            ];

            $deliveryAddrJson = json_encode([$addressObj], JSON_UNESCAPED_UNICODE);


            $subtotal = 0;
            $orderNotesString = "";

            foreach ($cartLines as $line) {
                $dish = $dishDAO->findById($line['dish_id']);

                if ($dish) {
                    $realPrice = $dish->getBasePrice();
                    $subtotal += $realPrice;

                    if (!empty($line['notes'])) {
                        $orderNotesString .= $dish->getDishName() . ": " . $line['notes'] . ". , ";
                    }
                }
            }

            $discountId = !empty($_POST['applied_discount_id']) ? intval($_POST['applied_discount_id']) : null;
            $totalAmount = $subtotal;

            if ($discountId) {
                $discount = $discountDAO->findById($discountId);

                if ($discount && $discountDAO->validateDiscount($discount->getDiscountCode(), $userId)) {
                    $percent = $discount->getPercent();
                    $totalAmount = $subtotal - ($subtotal * ($percent / 100));
                } else {
                    $discountId = null; 
                }
            }

            $newOrder = new Order(
                0,                          
                (int)$userId,               
                $discountId,           
                $deliveryAddrJson,          
                date('Y-m-d H:i:s'),        
                (float)$totalAmount,        
                1,                          
                null,                       
                trim($orderNotesString)     
            );

            $orderId = $orderDAO->create($newOrder);

            foreach ($cartLines as $line) {
                $dish = $dishDAO->findById($line['dish_id']);
                $price = $dish ? $dish->getBasePrice() : 0;

                $orderLineObj = new OrderLine(
                    0,                      
                    (int)$orderId,          
                    (int)$line['dish_id'],  
                    (float)$price,          
                    $line['notes'] ?? null  
                );

                $orderLineDAO->create($orderLineObj);
            }
            //Redirect to home with the message as param in the url
            header("Location: index.php?controller=home&action=index&order_success=" . $orderId);
            exit;
        } catch (Exception $e) {
            $errorMsg = urlencode("Error al procesar el pedido: " . $e->getMessage());
            header("Location: index.php?controller=order&action=confirm&error=" . $errorMsg);
            exit;
        }
    }


}


