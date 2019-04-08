<?php

namespace App\Controllers\Api;


use App\App;

class ProductController extends ApiController
{
    protected function actionGetProducts(): void
    {
        $limitFrom = (int)($_GET['from'] ?? '');
        $limitCount = (int)($_GET['to'] ?? '');

        $this->data = App::call()
            ->getRepository('Product')
            ->setQueryParams(null, null, null, null, $limitFrom, $limitCount)
            ->getAll();
    }

    protected function actionGetCountProducts(): void
    {
        $selectFields[] = ['COUNT(*)' => 'count'];
        $this->data = App::call()
            ->getRepository('Product')
            ->setQueryParams($selectFields)
            ->getCountRows();
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