<?php

namespace App\Models;

class Accessories extends Product
{
    public $kind;

    public function __construct(
        string $name,
        string $description,
        float $price,
        int $discount,
        string $kind)
    {
        parent::__construct($name, $description, $price, $discount);
        $this->kind = $kind;
    }
}