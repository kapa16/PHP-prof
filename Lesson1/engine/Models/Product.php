<?php

namespace App\Models;

use App\Model;

class Product extends Model
{
    protected $name;
    protected $description;
    protected $price;
    protected $discount;

    public function __construct(string $name, string $description, float $price, int $discount)
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->discount = $discount;
    }

    /**
     * Выдает цену с учетом акций
     */
    public function getPrice()
    {
        return $this->price * $this->discount / 100;
    }
}