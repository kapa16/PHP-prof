<?php

namespace App\Models\Products;

class PieceProduct extends Product
{
    public function FinalCost($amount = 1): float
    {
        return $this->price * $amount;
    }
}