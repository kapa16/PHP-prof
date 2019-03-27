<?php

namespace App\Models\Products;

use App\Models\Model;

class Product extends Model
{

    public const TABLE = 'products';
    protected static $salesRevenue;

    public $name;
    public $description;
    public $price;
    public $img_src;
    public $rating;

    public function __construct(
        string $name = '',
        string $description = '',
        float $price = 0,
        string $imgSrc = '',
        int $rating = 0
    )
    {
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->img_src = $imgSrc;
        $this->rating = $rating;
    }

    protected static function getTableName(): string
    {
        return 'products';
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