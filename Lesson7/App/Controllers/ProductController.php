<?php

namespace App\Controllers;

use App\App;
use App\Models\Product;
use RuntimeException;

class ProductController extends Controller
{
    protected $template = 'products.twig';

    public function index(): string
    {
        $limitFrom = (int) ($_GET['from'] ?? '');
        $limitCount = (int) ($_GET['to'] ?? '');

        $products = App::getInstance()
            ->getRepository('Product')
            ->setQueryParams(null, null, null, null, $limitFrom, $limitCount)
            ->getAll();

        if (!count($products)) {
            Product::fillTestProduct();
            header('Location: /product');
            exit;
        }

        $params = [
            'header' => 'Products catalog',
            'type' => 'catalog',
            'admin' => true, // TODO get user role
            'products' => $products
        ];
        return $this->render($params);
    }

    protected function getProductById()
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

        return App::getInstance()
            ->getRepository('Product')
            ->setQueryParams(null, $filters)
            ->getOne();
    }

    public function card(): string
    {
        $product = $this->getProductById();

        $params = [
            'header' => 'Product card',
            'type' => 'card',
            'admin' => true, // TODO get user role
            'product' => $product
        ];
        return $this->render($params);
    }

    public function edit(): string
    {
        $product = $this->getProductById();

        $params = [
            'header' => 'Product editing',
            'type' => 'edit',
            'buttonTitle' => 'Update',
            'admin' => true, // TODO get user role
            'product' => $product
        ];
        return $this->render($params);
    }

    public function delete()
    {
        $product = $this->getProductById();


    }
}