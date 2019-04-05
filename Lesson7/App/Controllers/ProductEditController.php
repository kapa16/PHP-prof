<?php

namespace App\Controllers;

use App\App;
use App\Models\Product;
use App\Models\User;
use RuntimeException;

class ProductEditController extends Controller
{
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


    public function save(): void
    {
        $this->changeProduct($_POST);
        header('Location: /product');
    }

    protected function changeProduct(array $changedData = []): void
    {
        if (!User::adminRole()) {
            throw new RuntimeException('No access');
        }
        $product = $this->getProductById();
        foreach ($changedData as $key => $value) {
            if (!property_exists($product, $key)) {
                continue;
            }
            $product->$key = htmlspecialchars($value);
        }

        App::getInstance()
            ->getRepository('Product')
            ->save($product);
    }

    public function delete(): void
    {
        $this->changeProduct(['deleted' => 1]);
        header('Location: /product');
    }

    public function restore(): void
    {
        $this->changeProduct(['deleted' => 0]);
        header('Location: /product');
    }
}