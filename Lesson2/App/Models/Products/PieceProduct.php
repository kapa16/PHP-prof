<?php

namespace App\Models\Products;

class PieceProduct extends Product
{
    protected static $salesRevenue = 0;

    /**
     * Return final coast of product
     * @param int $amount
     * @return float - final coast
     */
    public function finalCost(int $amount = 0): float
    {
        return $this->price * $amount;
    }
}