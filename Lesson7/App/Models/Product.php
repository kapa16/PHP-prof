<?php

namespace App\Models;

use App\App;
use App\Models\Repositories\ProductRepository;

class Product extends DataEntity
{
    public $name;
    public $description;
    public $price;
    public $image;
    public $category_id;
    public $deleted;

    public static function fillTestProduct(): void
    {
        $productData = [];
        for ($i = 1; $i <= 20; $i++) {
            $productData['name'] = 'Товар ' . $i;
            $productData['description'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos distinctio dolorum facilis fugiat molestiae nihil quos reiciendis rem vel? Iusto.';
            $productData['price'] = 100 * $i;
            $productData['image'] = 'http://via.placeholder.com/200';
            $productData['category_id'] = 1;
            $product = new self($productData);
            App::getInstance()
                ->getRepository('product')
                ->insert($product);
        }
    }
}