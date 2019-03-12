<?php

namespace engine\Models;

class Clothes extends Product
{
    public $size;
    public $brand;

    public function __construct(
        string $name,
        string $description,
        float $price,
        int $discount,
        string $size,
        string $brand)
    {
        parent::__construct($name, $description, $price, $discount);
        $this->size = $size;
        $this->brand = $brand;
    }
}