<?php

require_once CORE_PATH . "DatabasePDO.php";
require_once MODELS_PATH . "Order.php";
require_once CORE_PATH . "SessionManager.php";

class OrderDAO
{
    private $db;
    private $conn;
    private $table = 'orders';
    private ?array $orders;

    public function __construct()
    {
        $this->db = new DatabasePDO();
    }

    private function setSessionUserId()
    {
        SessionManager::start();
        $userId = SessionManager::get('user_id');
        if ($userId) {
            $sql = "SET @current_user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $userId);
            $stmt->execute();
        }
    }

    public function findAll()
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $this->orders = [];
        try {
            $stmt->execute();
            $ordersData = $stmt->fetchAll();
            foreach ($ordersData as $o) {
                $order = new Order(
                    order_id: $o['order_id'],
                    user_id: $o['user_id'],
                    discount_id: $o['discount_id'],
                    delivery_addr: $o['delivery_addr'],
                    ordered_at: $o['ordered_at'],
                    total_amount: $o['total_amount'],
                    order_status: $o['order_status'],
                    rating: $o['rating'],
                    notes: $o['notes']
                );
                array_push($this->orders, $order);
            }
            $this->db->disconnect();
            return $this->orders;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function findById(int $id)
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE order_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        try {
            $stmt->execute();
            $o = $stmt->fetch();
            $order = null;
            if ($o) {
                $order = new Order(
                    order_id: $o['order_id'],
                    user_id: $o['user_id'],
                    discount_id: $o['discount_id'],
                    delivery_addr: $o['delivery_addr'],
                    ordered_at: $o['ordered_at'],
                    total_amount: $o['total_amount'],
                    order_status: $o['order_status'],
                    rating: $o['rating'],
                    notes: $o['notes']
                );
            }
            $this->db->disconnect();
            return $order;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function findByUserId(int $userId)
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $this->orders = [];
        try {
            $stmt->execute();
            $ordersData = $stmt->fetchAll();
            foreach ($ordersData as $o) {
                $order = new Order(
                    order_id: $o['order_id'],
                    user_id: $o['user_id'],
                    discount_id: $o['discount_id'],
                    delivery_addr: $o['delivery_addr'],
                    ordered_at: $o['ordered_at'],
                    total_amount: $o['total_amount'],
                    order_status: $o['order_status'],
                    rating: $o['rating'],
                    notes: $o['notes']
                );
                array_push($this->orders, $order);
            }
            $this->db->disconnect();
            return $this->orders;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function create(Order $order)
    {
        $this->conn = $this->db->getConnection();
        $this->setSessionUserId(); // Set user_id for triggers
        $query = "INSERT INTO " . $this->table . " (user_id, discount_id, delivery_addr, ordered_at, total_amount, order_status, rating, notes) VALUES (:user_id, :discount_id, :delivery_addr, :ordered_at, :total_amount, :order_status, :rating, :notes)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':user_id', $order->getUserId());
        $stmt->bindValue(':discount_id', $order->getDiscountId());
        $stmt->bindValue(':delivery_addr', $order->getDeliveryAddr());
        $stmt->bindValue(':ordered_at', $order->getOrderedAt());
        $stmt->bindValue(':total_amount', $order->getTotalAmount());
        $stmt->bindValue(':order_status', $order->getOrderStatus());
        $stmt->bindValue(':rating', $order->getRating());
        $stmt->bindValue(':notes', $order->getNotes());

        try {
            $stmt->execute();
            $this->db->disconnect();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function update(Order $order)
    {
        $this->conn = $this->db->getConnection();
        $this->setSessionUserId(); // Set user_id for triggers
        $query = "UPDATE " . $this->table . " SET user_id = :user_id, discount_id = :discount_id, delivery_addr = :delivery_addr, ordered_at = :ordered_at, total_amount = :total_amount, order_status = :order_status, rating = :rating, notes = :notes WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':order_id', $order->getOrderId());
        $stmt->bindValue(':user_id', $order->getUserId());
        $stmt->bindValue(':discount_id', $order->getDiscountId());
        $stmt->bindValue(':delivery_addr', $order->getDeliveryAddr());
        $stmt->bindValue(':ordered_at', $order->getOrderedAt());
        $stmt->bindValue(':total_amount', $order->getTotalAmount());
        $stmt->bindValue(':order_status', $order->getOrderStatus());
        $stmt->bindValue(':rating', $order->getRating());
        $stmt->bindValue(':notes', $order->getNotes());

        try {
            $stmt->execute();
            $this->db->disconnect();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function destroy(int $id)
    {
        $this->conn = $this->db->getConnection();
        $this->setSessionUserId(); // Set user_id for triggers
        
        try {
            $this->conn->beginTransaction();

            // First delete associated order lines (Cascade Delete simulation)
            $queryLines = "DELETE FROM order_lines WHERE order_id = :order_id";
            $stmtLines = $this->conn->prepare($queryLines);
            $stmtLines->bindParam(':order_id', $id);
            $stmtLines->execute();

            // Then delete the order
            $query = "DELETE FROM " . $this->table . " WHERE order_id = :order_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':order_id', $id);
            $stmt->execute();
            
            $count = $stmt->rowCount();
            $this->conn->commit();
            
            $this->db->disconnect();
            return $count;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function hasUserUsedDiscount(int $userId, int $discountId): bool
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE user_id = :user_id AND discount_id = :discount_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':discount_id', $discountId);
        try {
            $stmt->execute();
            $count = $stmt->fetchColumn();
            $this->db->disconnect();
            return $count > 0;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }


}
