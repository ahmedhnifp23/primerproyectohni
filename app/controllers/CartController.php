<?php
require_once __DIR__ . "/../DAOs/DishDAO.php";

class CartController {
    private $dishDAO;

    public function __construct() {
        $this->dishDAO = new DishDAO();
    }

    /**
     * Recibe el JSON del JS, reconstruye los objetos y carga la vista.
     */
public function render() {

        // 2. Leer el JSON del cuerpo de la petición (porque es un POST con JSON)
        $jsonInput = file_get_contents('php://input');
        $inputData = json_decode($jsonInput, true);
        $receivedLines = $inputData['order_lines'] ?? [];

        $cartLinesToView = [];
        $totalAmount = 0;

        // 3. Procesar datos (igual que antes)
        foreach ($receivedLines as $index => $line) {
            $dishId = $line['dish_id'];
            $dish = $this->dishDAO->findById($dishId);

            if ($dish) {
                $cartLinesToView[] = [
                    'index' => $index,
                    'dish'  => $dish,
                    'price' => $line['unit_price'],
                    'notes' => $line['notes'] ?? ''
                ];
                $totalAmount += $line['unit_price'];
            }
        }

        // 4. Cargar la vista
        // Usamos la constante que definiste en tu Front Controller
        require VIEWS_PATH . '/partials/cart-body.php';
    }



}
?>