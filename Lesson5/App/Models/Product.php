<?php

namespace App\Models;

class Product extends Model
{
    public const TABLE = 'products';

    public $name;
    public $description;
    public $price;
    public $image;
    public $category_id;

    protected static function getTableName(): string
    {
        return 'products';
    }

    public static function fillTestProduct()
    {
        $productData = [];
        for ($i = 1; $i <= 20; $i++) {
            $productData['name'] = 'Товар ' . $i;
            $productData['description'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.';
            $productData['price'] = 100 * $i;
            $productData['image'] = 'http://via.placeholder.com/200';
            $productData['category_id'] = 1;
            $product = new self($productData);
            $product->insert();
        }
    }
}