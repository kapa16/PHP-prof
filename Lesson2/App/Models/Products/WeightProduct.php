<?php

namespace App\Models\Products;

class WeightProduct extends Product
{
    public function FinalCost($amount = 1): float
    {
        return $this->price * $amount;
    }
}