<?php

require_once __DIR__ . "/../core/DatabasePDO.php";
require_once __DIR__ . "/../models/Discount.php";
require_once __DIR__ . "/../core/JsonUtils.php";

class DiscountDAO
{
    private $db; //Variable where i save the instance of the PDO.
    private $conn; //Variable where i save the connection.
    private $table = 'discounts'; //Variable with the name of the table.
    private ?array $discounts; //Variable where I will save the array of discounts.
    private $jsonUtils; //Instance of json utils.

    //Construct with a instance of dbPDO and model Discount.
    public function __construct()
    {
        $this->db = new DatabasePDO();
        $this->jsonUtils = new JsonUtils();
    }

    public function findAll()
    {
        $this->conn = $this->db->getConnection();
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $this->discounts = [];
        try {
            $stmt->execute();
            $discountsData = $stmt->fetchAll();
            foreach ($discountsData as $d) {
                $discount = new Discount(
                    discount_id: $d['discount_id'],
                    discount_code: $d['discount_code'],
                    percent: $d['percent'],
                    starts_at: $d['starts_at'],
                    ends_at: $d['ends_at'],
                    max_uses: $d['max_uses'],
                    is_active: $d['is_active']
                );
                array_push($this->discounts, $discount);
            }
            $this->db->disconnect();
            return $this->discounts;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function findActive()
    {
        $this->conn = $this->db->getConnection();
        $query = 'SELECT * FROM ' . $this->table . ' WHERE is_active = 1';
        $stmt = $this->conn->prepare($query);
        $this->discounts = [];
        try {
            $stmt->execute();
            $discountsData = $stmt->fetchAll();
            foreach ($discountsData as $d) {
                $discount = new Discount(
                    discount_id: $d['discount_id'],
                    discount_code: $d['discount_code'],
                    percent: $d['percent'],
                    starts_at: $d['starts_at'],
                    ends_at: $d['ends_at'],
                    max_uses: $d['max_uses'],
                    is_active: $d['is_active']
                );
                array_push($this->discounts, $discount);
            }
            $this->db->disconnect();
            return $this->discounts;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function findById(int $id)
    {
        //Obtain the connection.
        $this->conn = $this->db->getConnection();
        //Create the query string.
        $query = "SELECT * FROM " . $this->table . " WHERE discount_id = :id";
        //Prepare the statement.
        $stmt = $this->conn->prepare($query);
        //Bind the param.
        $stmt->bindParam(':id', $id);
        //Try-Catch to controll the exception during the consult.
        try {
            $stmt->execute();
            $discountData = $stmt->fetch();

            if ($discountData) {
                $discount = new Discount(
                    discount_id: $discountData['discount_id'],
                    discount_code: $discountData['discount_code'],
                    percent: $discountData['percent'],
                    starts_at: $discountData['starts_at'],
                    ends_at: $discountData['ends_at'],
                    max_uses: $discountData['max_uses'],
                    is_active: $discountData['is_active']
                );
            }
            $this->db->disconnect();

            return $discount;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function findByCode(string $code)
    {
        //Obtain the connection.
        $this->conn = $this->db->getConnection();
        //Create the query string.
        $query = "SELECT * FROM " . $this->table . " WHERE discount_code = :code";
        //Prepare the statement.
        $stmt = $this->conn->prepare($query);
        //Bind the param.
        $stmt->bindParam(':code', $code);
        //Try-Catch to controll the exception during the consult.
        try {
            $stmt->execute();
            $discountData = $stmt->fetch();

            if ($discountData) {
                $discount = new Discount(
                    discount_id: $discountData['discount_id'],
                    discount_code: $discountData['discount_code'],
                    percent: $discountData['percent'],
                    starts_at: $discountData['starts_at'],
                    ends_at: $discountData['ends_at'],
                    max_uses: $discountData['max_uses'],
                    is_active: $discountData['is_active']
                );
            }
            $this->db->disconnect();

            return $discount ?? null;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function create(Discount $discount)
    {
        $this->conn = $this->db->getConnection();
        $query = "INSERT INTO " . $this->table . "(discount_code, percent, starts_at, ends_at, max_uses, is_active) VALUES(:discount_code, :percent, :starts_at, :ends_at, :max_uses, :is_active)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':discount_code', $discount->getDiscountCode());
        $stmt->bindValue(':percent', $discount->getPercent());
        $stmt->bindValue(':starts_at', $discount->getStartsAt());
        $stmt->bindValue(':ends_at', $discount->getEndsAt());
        $stmt->bindValue(':max_uses', $discount->getMaxUses());
        $stmt->bindValue(':is_active', $discount->getIsActive());

        try {
            $stmt->execute();
            $this->db->disconnect();
            return $this->conn->lastInsertId(); //Return the id of the inserted discount.
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function update(Discount $discount)
    {
        $this->conn = $this->db->getConnection();
        $query = "UPDATE " . $this->table . " SET discount_code = :discount_code, percent = :percent, starts_at = :starts_at, ends_at = :ends_at, max_uses = :max_uses, is_active = :is_active WHERE discount_id = :discount_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':discount_id', $discount->getDiscountId());
        $stmt->bindValue(':discount_code', $discount->getDiscountCode());
        $stmt->bindValue(':percent', $discount->getPercent());
        $stmt->bindValue(':starts_at', $discount->getStartsAt());
        $stmt->bindValue(':ends_at', $discount->getEndsAt());
        $stmt->bindValue(':max_uses', $discount->getMaxUses());
        $stmt->bindValue(':is_active', (int)$discount->getIsActive(), PDO::PARAM_INT);

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
        $query = "DELETE FROM " . $this->table . " WHERE discount_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);
        try {
            $stmt->execute();
            $this->db->disconnect();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function validateDiscount(string $code, int $userId)
    {
        $this->conn = $this->db->getConnection();
        $query = "SELECT * FROM " . $this->table . " WHERE discount_code = :code AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);

        try {
            $stmt->execute();
            $discountData = $stmt->fetch();

            if ($discountData) {
                // Verificar si el descuento está dentro del rango de fechas
                $now = date('Y-m-d H:i:s');
                $startsAt = $discountData['starts_at'];
                $endsAt = $discountData['ends_at'];

                // Si starts_at no es null y la fecha actual es anterior, el descuento no es válido
                if ($startsAt !== null && $now < $startsAt) {
                    $this->db->disconnect();
                    return null;
                }

                // Si ends_at no es null y la fecha actual es posterior, el descuento no es válido
                if ($endsAt !== null && $now > $endsAt) {
                    $this->db->disconnect();
                    return null;
                }

                // Check if user has already used this discount
                require_once __DIR__ . '/OrderDAO.php';
                $orderDAO = new OrderDAO();
                if ($orderDAO->hasUserUsedDiscount($userId, $discountData['discount_id'])) {
                    $this->db->disconnect();
                    return null;
                }

                $discount = new Discount(
                    discount_id: $discountData['discount_id'],
                    discount_code: $discountData['discount_code'],
                    percent: $discountData['percent'],
                    starts_at: $discountData['starts_at'],
                    ends_at: $discountData['ends_at'],
                    max_uses: $discountData['max_uses'],
                    is_active: $discountData['is_active']
                );
                $this->db->disconnect();
                return $discount;
            }

            $this->db->disconnect();
            return null;
        } catch (PDOException $e) {
            $this->db->disconnect();
            throw $e;
        }
    }

    public function methodNotFound(string $action)
    {
        echo "Method " . $action . " not found!!!";
    }
}
