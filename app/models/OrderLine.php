<?php

class OrderLine {

    private int $order_line_id;
    private int $order_id;
    private int $dish_id;
    private float $unit_price;
    private ?string $notes;

    public function __construct(int $order_line_id, int $order_id, int $dish_id, float $unit_price, ?string $notes = null) {
        $this->order_line_id = $order_line_id;
        $this->order_id = $order_id;
        $this->dish_id = $dish_id;
        $this->unit_price = $unit_price;
        $this->notes = $notes;
    }

    
    public function getOrderLineId(): int {
        return $this->order_line_id;
    }
    public function getOrderId(): int {
        return $this->order_id;
    }
    public function getDishId(): int {
        return $this->dish_id;
    }
    public function getUnitPrice(): float {
        return $this->unit_price;
    }
    public function getNotes(): ?string {
        return $this->notes;
    }

    public function setOrderLineId(int $order_line_id): void {
        $this->order_line_id = $order_line_id;
    }

    public function setOrderId(int $order_id): void {
        $this->order_id = $order_id;
    }

    public function setDishId(int $dish_id): void {
        $this->dish_id = $dish_id;
    }

    public function setUnitPrice(float $unit_price): void {
        $this->unit_price = $unit_price;
    }

    public function setNotes(?string $notes): void {
        $this->notes = $notes;
    }
    
}





?>