<?php

namespace App\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    protected const TEMPLATE_NAME = 'products.twig';

    public function index(): void
    {
        $products = Product::getAll();
        if (!count($products)) {
            Product::fillTestProduct();
            header('Location: /product');
            exit;
        }

        $params = [
            'header' => 'Products catalog',
            'products' => $products
        ];
        echo $this->render($params);
    }
}