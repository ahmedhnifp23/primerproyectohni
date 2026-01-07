<?php

class Discount {

    private ?int $discount_id;
    private string $discount_code;
    private float $percent;
    private ?string $starts_at;
    private ?string $ends_at;
    private int $max_uses;
    private int $is_active;
    public function __construct(?int $discount_id = null, string $discount_code, float $percent, ?string $starts_at = null, ?string $ends_at = null, int $max_uses, int $is_active) {
        $this->discount_id = $discount_id;
        $this->discount_code = $discount_code;
        $this->percent = $percent;
        $this->starts_at = $starts_at;
        $this->ends_at = $ends_at;
        $this->max_uses = $max_uses;
        $this->is_active = $is_active;
    }
    /**
     * Get the value of discount_id
     */
    public function getDiscountId()
    {
        return $this->discount_id;
    }
    /**
     * Set the value of discount_id
     *
     * @return  self
     */
    public function setDiscountId($discount_id)
    {
        $this->discount_id = $discount_id;

        return $this;
    }
    
    /**
     * Get the value of discount_code
     */
    public function getDiscountCode()
    {
        return $this->discount_code;
    }
    /**
     * Set the value of discount_code
     *
     * @return  self
     */
    public function setDiscountCode($discount_code)
    {
        $this->discount_code = $discount_code;

        return $this;
    }
    /**
     * Get the value of percent
     */
    public function getPercent()
    {
        return $this->percent;
    }
    /**
     * Set the value of percent
     *
     * @return  self
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }
    /**
     * Get the value of starts_at
     */
    public function getStartsAt()
    {
        return $this->starts_at;
    }
    /**
     * Set the value of starts_at
     *
     * @return  self
     */
    public function setStartsAt($starts_at)
    {
        $this->starts_at = $starts_at;

        return $this;
    }
    /**
     * Get the value of ends_at
     */
    public function getEndsAt()
    {
        return $this->ends_at;
    }
    /**
     * Set the value of ends_at
     *
     * @return  self
     */
    public function setEndsAt($ends_at)
    {
        $this->ends_at = $ends_at;

        return $this;
    }
    /**
     * Get the value of max_uses
     */
    public function getMaxUses()
    {
        return $this->max_uses;
    }
    /**
     * Set the value of max_uses
     *
     * @return  self
     */
    public function setMaxUses($max_uses)
    {
        $this->max_uses = $max_uses;

        return $this;
    }
    /**
     * Get the value of is_active
     */
    public function getIsActive()
    {
        return $this->is_active;
    }
    /**
     * Set the value of is_active
     *
     * @return  self
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;

        return $this;
    }
    









}



?>