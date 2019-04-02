<?php

namespace App\Controllers\Api;


use App\Models\Product;

class ProductController extends ApiController
{
    protected function actionGetProducts(): void
    {
        Product::$limitFrom = +$_GET['from'] ?? 0;
        Product::$limitCount = +$_GET['to'] ?? 0;
        $this->data = Product::getLimit();
    }

    protected function actionGetCountProducts(): void
    {
        $this->data = Product::getCountRows();
    }

    public function __invoke()
    {
        $action = $_GET['action'] ?? '';

        if ($action) {
            $action = 'action' . $action;
            $this->$action();
        } else {
            $this->errorMessage = 'Unknown action';
        }

        if ($this->data) {
            echo $this->success();
        } else {
            $this->errorMessage = 'No data';
            echo $this->error();
        }
    }
}