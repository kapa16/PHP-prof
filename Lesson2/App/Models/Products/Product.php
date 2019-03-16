<?php

namespace App\Models\Products;

use App\Model;

abstract class Product extends Model
{

    public const TABLE = 'products';

    protected $name;
    protected $description;
    protected $price;
    protected $category_id;

    public function __construct(
        string $name = '',
        string $description = '',
        float $price = 0,
        int $category_id = 1
    )

    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->category_id = $category_id;
    }

    /**
     * Return final coast of product
     * @param int $amount
     * @return float - final coast
     */
    abstract public function FinalCost($amount = 1): float;
}