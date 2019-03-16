<?php

namespace App\Models\Products;

class DigitalProduct extends Product
{
    public function FinalCost($amount = 1): float
    {
        return $this->price;
    }
}