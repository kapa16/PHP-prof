<?php

namespace engine\Models;

use engine\Model;

class Product extends Model
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
     * Выдает цену с учетом акций
     */
    public function getPrice()
    {
        return $this->price;
    }
}