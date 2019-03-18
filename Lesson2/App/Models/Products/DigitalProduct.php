<?php

namespace App\Models\Products;

class DigitalProduct extends Product
{
    protected static $salesRevenue = 0;

    /**
     * Return final coast of product
     * @return float - final coast
     */
    public function finalCost(): float
    {
        return $this->price;
    }
}