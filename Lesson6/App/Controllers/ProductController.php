<?php

namespace App\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    protected $template = 'products.twig';

    public function index(): string
    {
        Product::$limitFrom = +$_GET['from'] ?? 0;
        Product::$limitCount = +$_GET['to'] ?? 0;
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
        return $this->render($params);
    }
}