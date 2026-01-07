<?php

require_once CORE_PATH . "DatabasePDO.php";
require_once MODELS_PATH . "OrderLine.php";

class OrderLineDAO
{
    private $db;
    private $conn;
    private $table = 'order_lines';
    private ?array $orderLines;

    public function __construct()
    {
        $this->db = new DatabasePDO();
    }

    public function findAll()
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $this->orderLines = [];
        try {
            $stmt->execute();
            $orderLinesData = $stmt->fetchAll();
            foreach ($orderLinesData as $ol) {
                $orderLine = new OrderLine(
                    order_line_id: $ol['order_line_id'],
                    order_id: $ol['order_id'],
                    dish_id: $ol['dish_id'],
                    unit_price: $ol['unit_price'],
                    notes: $ol['notes']
                );
                array_push($this->orderLines, $orderLine);
            }
            $this->db->disconnect();
            return $this->orderLines;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function findById(int $id)
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE order_line_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        try {
            $stmt->execute();
            $ol = $stmt->fetch();
            $orderLine = null;
            if ($ol) {
                $orderLine = new OrderLine(
                    order_line_id: $ol['order_line_id'],
                    order_id: $ol['order_id'],
                    dish_id: $ol['dish_id'],
                    unit_price: $ol['unit_price'],
                    notes: $ol['notes']
                );
            }
            $this->db->disconnect();
            return $orderLine;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function findByOrderId(int $orderId)
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $orderId);
        $this->orderLines = [];
        try {
            $stmt->execute();
            $orderLinesData = $stmt->fetchAll();
            foreach ($orderLinesData as $ol) {
                $orderLine = new OrderLine(
                    order_line_id: $ol['order_line_id'],
                    order_id: $ol['order_id'],
                    dish_id: $ol['dish_id'],
                    unit_price: $ol['unit_price'],
                    notes: $ol['notes']
                );
                array_push($this->orderLines, $orderLine);
            }
            $this->db->disconnect();
            return $this->orderLines;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function create(OrderLine $orderLine)
    {
        $this->conn = $this->db->getConnection();
        $query = "INSERT INTO " . $this->table . " (order_id, dish_id, unit_price, notes) VALUES (:order_id, :dish_id, :unit_price, :notes)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':order_id', $orderLine->getOrderId());
        $stmt->bindValue(':dish_id', $orderLine->getDishId());
        $stmt->bindValue(':unit_price', $orderLine->getUnitPrice());
        $stmt->bindValue(':notes', $orderLine->getNotes());

        try {
            $stmt->execute();
            $this->db->disconnect();
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function update(OrderLine $orderLine)
    {
        $this->conn = $this->db->getConnection();
        $query = "UPDATE " . $this->table . " SET order_id = :order_id, dish_id = :dish_id, unit_price = :unit_price, notes = :notes WHERE order_line_id = :order_line_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':order_line_id', $orderLine->getOrderLineId());
        $stmt->bindValue(':order_id', $orderLine->getOrderId());
        $stmt->bindValue(':dish_id', $orderLine->getDishId());
        $stmt->bindValue(':unit_price', $orderLine->getUnitPrice());
        $stmt->bindValue(':notes', $orderLine->getNotes());

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
        $query = "DELETE FROM " . $this->table . " WHERE order_line_id = :order_line_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_line_id', $id);
        try {
            $stmt->execute();
            $this->db->disconnect();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }
}
