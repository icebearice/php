<?php

class Car {
    // Car 牌子 价格
    private $brand;
    private $price;

    /**
     * Car constructor.
     * @param $brand
     * @param $price
     */
    public function __construct($brand, $price)
    {
        $this->brand = $brand;
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

}