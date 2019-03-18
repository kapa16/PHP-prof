<?php

namespace App\Models\Products;

use App\Model;

abstract class Product extends Model
{

    public const TABLE = 'products';
    protected static $salesRevenue;

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
     * fixed sale for calc revenue
     * @param $amount - amount of sale product
     * @return float - final cost for sale product
     */
    public function sale($amount = 1): float
    {
        $finalCost = $this->finalCost($amount);
        static::$salesRevenue += $finalCost;
        return $finalCost;
    }

    /**
     * Get sum of revenue from sale kind of product
     * @return float - sum of revenue
     */
    public static function getRevenue(): float
    {
        return static::$salesRevenue;
    }
}