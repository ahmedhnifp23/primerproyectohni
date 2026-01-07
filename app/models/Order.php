<?php

class Order
{

    private int $order_id;
    private int $user_id;
    private ?int $discount_id;
    private string $delivery_addr;
    private string $ordered_at;
    private float $total_amount;
    private int $order_status;
    private ?int $rating;
    private ?string $notes;
    public function __construct(int $order_id, int $user_id, ?int $discount_id = null, string $delivery_addr, string $ordered_at, float $total_amount, int $order_status, ?int $rating = null, ?string $notes = null)
    {
        $this->order_id = $order_id;
        $this->user_id = $user_id;
        $this->discount_id = $discount_id;
        $this->delivery_addr = $delivery_addr;
        $this->ordered_at = $ordered_at;
        $this->total_amount = $total_amount;
        $this->order_status = $order_status;
        $this->rating = $rating;
        $this->notes = $notes;
    }
    /**
     * Get the value of order_id
     */
    public function getOrderId()
    {
        return $this->order_id;
    }
    /**
     * Set the value of order_id
     *
     * @return  self
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;

        return $this;
    }
    /**
     * Get the value of user_id
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    /**
     * Set the value of user_id
     *
     * @return  self
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
    /**
     * Get the value of discount_id
     */
    public function getDiscountId(): ?int
    {
        return $this->discount_id;
    }
    /**
     * Set the value of discount_id
     *
     * @return  self
     */
    public function setDiscountId(?int $discount_id)
    {
        $this->discount_id = $discount_id;

        return $this;
    }
    /**
     * Get the value of delivery_addr
     */
    public function getDeliveryAddr()
    {
        return $this->delivery_addr;
    }
    /**
     * Set the value of delivery_addr
     *
     * @return  self
     */
    public function setDeliveryAddr($delivery_addr)
    {
        $this->delivery_addr = $delivery_addr;

        return $this;
    }
    /**
     * Get the value of ordered_at
     */
    public function getOrderedAt()
    {
        return $this->ordered_at;
    }
    /**
     * Set the value of ordered_at
     *
     * @return  self
     */
    public function setOrderedAt($ordered_at)
    {
        $this->ordered_at = $ordered_at;

        return $this;
    }
    /**
     * Get the value of total_amount
     */
    public function getTotalAmount()
    {
        return $this->total_amount;
    }
    /**
     * Set the value of total_amount
     *
     * @return  self
     */
    public function setTotalAmount($total_amount)
    {
        $this->total_amount = $total_amount;

        return $this;
    }
    /**
     * Get the value of order_status
     */
    public function getOrderStatus()
    {
        return $this->order_status;
    }
    /**
     * Set the value of order_status
     *
     * @return  self
     */
    public function setOrderStatus($order_status)
    {
        $this->order_status = $order_status;

        return $this;
    }
    /**
     * Get the value of rating
     */
    public function getRating()
    {
        return $this->rating;
    }
    /**
     * Set the value of rating
     *
     * @return  self
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }
    /**
     * Get the value of notes
     */
    public function getNotes()
    {
        return $this->notes;
    }
    /**
     * Set the value of notes
     *
     * @return  self
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'discount_id' => $this->discount_id,
            'delivery_addr' => $this->delivery_addr,
            'ordered_at' => $this->ordered_at,
            'total_amount' => $this->total_amount,
            'order_status' => $this->order_status,
            'rating' => $this->rating,
            'notes' => $this->notes
        ];
    }

}
