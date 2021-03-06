<?php

namespace App\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    protected $template = 'products.twig';

    public function index(): string
    {
        $limitFrom = +$_GET['from'] ?? 0;
        $limitCount = +$_GET['to'] ?? 0;
        Product::setQueryParams([], [], 'AND', [], $limitFrom, $limitCount);

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