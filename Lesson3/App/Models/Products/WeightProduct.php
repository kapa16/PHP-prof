<?php

namespace App\Models\Products;

class WeightProduct extends Product
{
    protected static $salesRevenue = 0;

    /**
     * Return final coast of product
     * @param float $amount
     * @return float - final coast
     */
    public function finalCost(float $amount = 0): float
    {
        return $this->price * $amount;
    }
}