<?php

namespace App\Controllers;

use App\App;
use App\Models\Product;
use App\Models\User;
use RuntimeException;

class ProductController extends Controller
{
    protected $template = 'products.twig';

    public function index(): string
    {
        $limitFrom = (int) ($_GET['from'] ?? '');
        $limitCount = (int) ($_GET['to'] ?? '');

        $filter = [];
        if (!User::adminRole()) {
            $filter[] = [
                'col'   => 'deleted',
                'oper'  => '=',
                'value' => 0,
            ];
        }

        $products = App::getInstance()
            ->getRepository('Product')
            ->setQueryParams(null, $filter, null, null, $limitFrom, $limitCount)
            ->getAll();

        if (!count($products)) {
            Product::fillTestProduct();
            header('Location: /product');
            exit;
        }

        $params = [
            'header' => 'Products catalog',
            'type' => 'catalog',
            'admin' => User::adminRole(),
            'products' => $products
        ];
        return $this->render($params);
    }

    protected function getProductById()
    {
        if (empty($_GET['product-id'])) {
            return new Product();
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
            'admin' => User::adminRole(),
            'product' => $product
        ];
        return $this->render($params);
    }

    public function create(): string
    {
        if (!User::adminRole()) {
            throw new RuntimeException('No access');
        }

        $params = [
            'header' => 'Product creating',
            'type' => 'edit',
            'buttonTitle' => 'Create',
            'admin' => User::adminRole(),
        ];
        return $this->render($params);
    }

    public function edit(): string
    {
        if (!User::adminRole()) {
            throw new RuntimeException('No access');
        }
        $product = $this->getProductById();

        $params = [
            'header' => 'Product editing',
            'type' => 'edit',
            'buttonTitle' => 'Update',
            'admin' => User::adminRole(),
            'product' => $product
        ];
        return $this->render($params);
    }

}