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
            'admin' => User::adminRole(),
            'product' => $product
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

    public function save()
    {
        if (!User::adminRole()) {
            throw new RuntimeException('No access');
        }
        $product = $this->getProductById();
        foreach ($_POST as $key => $value) {
            if ((!property_exists($product, $key)) || (!$value)) {
                continue;
            }
            $product->$key = htmlspecialchars($value);
        }
        App::getInstance()
            ->getRepository('Product')
            ->save($product);
        header('Location: /product');
    }

    protected function changeDeleted(int $deleted = 0): void
    {
        if (!User::adminRole()) {
            throw new RuntimeException('No access');
        }
        $product = $this->getProductById();
        $product->deleted = $deleted;
        App::getInstance()
            ->getRepository('Product')
            ->save($product);
    }

    public function delete(): void
    {
        $this->changeDeleted(1);
        header('Location: /product');
    }

    public function restore(): void
    {
        $this->changeDeleted();
        header('Location: /product');
    }
}