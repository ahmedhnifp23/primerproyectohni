<?php

class Dish
{


    private int $dish_id;
    private string $dish_name;
    private string $dish_description;
    private float $base_price;
    private string $images;//encode and decode
    private bool $available;
    private $category;

    public function __construct(){
        
    }

    

    /**
     * Get the value of dish_id
     */ 
    public function getDish_id()
    {
        return $this->dish_id;
    }

    /**
     * Set the value of dish_id
     *
     * @return  self
     */ 
    public function setDish_id($dish_id)
    {
        $this->dish_id = $dish_id;

        return $this;
    }

    /**
     * Get the value of dish_name
     */ 
    public function getDish_name()
    {
        return $this->dish_name;
    }

    /**
     * Set the value of dish_name
     *
     * @return  self
     */ 
    public function setDish_name($dish_name)
    {
        $this->dish_name = $dish_name;

        return $this;
    }

    /**
     * Get the value of dish_description
     */ 
    public function getDish_description()
    {
        return $this->dish_description;
    }

    /**
     * Set the value of dish_description
     *
     * @return  self
     */ 
    public function setDish_description($dish_description)
    {
        $this->dish_description = $dish_description;

        return $this;
    }

    /**
     * Get the value of base_price
     */ 
    public function getBase_price()
    {
        return $this->base_price;
    }

    /**
     * Set the value of base_price
     *
     * @return  self
     */ 
    public function setBase_price($base_price)
    {
        $this->base_price = $base_price;

        return $this;
    }

    /**
     * Get the value of images
     */ 
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set the value of images
     *
     * @return  self
     */ 
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }

    /**
     * Get the value of available
     */ 
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set the value of available
     *
     * @return  self
     */ 
    public function setAvailable($available)
    {
        $this->available = $available;

        return $this;
    }

    /**
     * Get the value of category
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
}
