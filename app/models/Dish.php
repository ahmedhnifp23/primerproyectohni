<?php

class Dish
{


    private ?int $dish_id;
    private string $dish_name;
    private string $dish_description;
    private ?string $topic;
    private float $base_price;
    private array $images;//encode and decode
    private bool $available;
    private string $category;

    public function __construct(?int $dish_id = null, string $dish_name, string $dish_description, ?string $topic = null, float $base_price, array $images, bool $available, string $category)
    {
        $this->dish_id = $dish_id;
        $this->dish_name = $dish_name;
        $this->dish_description = $dish_description;
        $this->topic = $topic;
        $this->base_price = $base_price;
        $this->images = $images;
        $this->available = $available;
        $this->category = $category;
        
    }

    //Function that returns an array with public properties to use it in json_encode. Returns an associative array with public properties.
    public function jsonSerialize(): array{
        return [
            'dish_id' => $this->getDishId(),
            'dish_name' => $this->getDishName(),
            'dish_description' => $this->getDishDescription(),
            'topic' => $this->getTopic(),
            'base_price' => $this->getBasePrice(),
            'images' => $this->getImages(),
            'available' => $this->getAvailable(),
            'category' => $this->getCategory()
        ];
    }

    

    /**
     * Get the value of dish_id
     */ 
    public function getDishId()
    {
        return $this->dish_id;
    }

    /**
     * Set the value of dish_id
     *
     * @return  self
     */ 
    public function setDishId($dish_id)
    {
        $this->dish_id = $dish_id;

        return $this;
    }

    /**
     * Get the value of dish_name
     */ 
    public function getDishName()
    {
        return $this->dish_name;
    }

    /**
     * Set the value of dish_name
     *
     * @return  self
     */ 
    public function setDishName($dish_name)
    {
        $this->dish_name = $dish_name;

        return $this;
    }

    /**
     * Get the value of dish_description
     */ 
    public function getDishDescription()
    {
        return $this->dish_description;
    }

    /**
     * Set the value of dish_description
     *
     * @return  self
     */ 
    public function setDishDescription($dishDescription)
    {
        $this->dish_description = $dishDescription;

        return $this;
    }

    /**
     * Get the value of topic
     */ 
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set the value of topic
     *
     * @return  self
     */ 
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get the value of base_price
     */ 
    public function getBasePrice()
    {
        return $this->base_price;
    }

    /**
     * Set the value of base_price
     *
     * @return  self
     */ 
    public function setBasePrice($basePrice)
    {
        $this->base_price = $basePrice;

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
    public function setImages(array $images)
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
