<?php

namespace App\Controllers;

use App\Models\Product;
use RuntimeException;

class ProductController extends Controller
{
    protected $template = 'products.twig';

    public function index(): string
    {
        $limitFrom = $_GET['from'] ?? 0;
        $limitCount = $_GET['to'] ?? 0;

        $queryParams = [
            'limitFrom' => $limitFrom,
            'limitCount' => $limitCount,
        ];

        $products = Product::getAll($queryParams);
        if (!count($products)) {
            Product::fillTestProduct();
            header('Location: /product');
            exit;
        }

        $params = [
            'header' => 'Products catalog',
            'catalog' => true,
            'admin' => true, // TODO get user role
            'products' => $products
        ];
        return $this->render($params);
    }

    public function card(): string
    {
        if (empty($_GET['product-id'])) {
            throw new RuntimeException('No product id');
        }
        $productId = (int) $_GET['product-id'];
        $filters[] = [
            'col'   => 'id',
            'oper'  => '=',
            'value' => $productId,
        ];
        $queryParams = [
            'filters' => $filters
        ];
        $product = Product::getOne($queryParams);

        $params = [
            'header' => 'Product card',
            'catalog' => false,
            'admin' => true, // TODO get user role
            'product' => $product
        ];
        return $this->render($params);
    }
}