<?php


namespace App\Controllers\Api;


class Cart extends ApiController
{
    public function add()
    {
        $product_id = $_POST['product_id'] ?? '';
        $product = \App\Models\Product::getOne('id', $product_id);
        // TODO add to cart
    }
}