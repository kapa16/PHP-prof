<?php

namespace App\Controllers\Api;

use App\App;

class OrderController extends ApiController
{

    protected function getOrderProduct(int $id = 0)
    {
        $filters[] = [
            'col'   => 'id',
            'oper'  => '=',
            'value' => $id,
        ];

        return App::getInstance()
            ->getRepository('OrderProduct')
            ->setQueryParams(null, $filters)
            ->getOne();
    }

    protected function saveOrderProduct($orderProduct): void
    {
        App::getInstance()
            ->getRepository('OrderProduct')
            ->save($orderProduct);
    }

    public function removeProduct(): void
    {
        $id = $_POST['postData']['id'] ?? '';

        $orderProduct = $this->getOrderProduct($id);
        $orderProduct->deleted = 1;
        $this->saveOrderProduct($orderProduct);
    }

    public function retrieveProduct(): void
    {
        $id = $_POST['postData']['id'] ?? '';
        $orderProduct = $this->getOrderProduct($id);
        $orderProduct->deleted = 0;
        $this->saveOrderProduct($orderProduct);
    }
}