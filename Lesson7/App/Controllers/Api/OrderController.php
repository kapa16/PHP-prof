<?php


namespace App\Controllers\Api;


use App\Models\OrderProduct;

class OrderController extends ApiController
{

    public function removeProduct(): void
    {
        $id = $_POST['postData']['id'] ?? '';
        $orderProduct = Order(new ProductRepository())->getOne('id', $id);
        $orderProduct->deleted = 1;
        $orderProduct->save();
    }

    public function retrieveProduct(): void
    {
        $id = $_POST['postData']['id'] ?? '';
        $orderProduct = OrderProduct::getOne('id', $id);
        $orderProduct->deleted = 0;
        $orderProduct->save();
    }
}