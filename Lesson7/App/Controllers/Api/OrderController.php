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

        return App::call()
            ->getRepository('OrderProduct')
            ->setQueryParams(null, $filters)
            ->getOne();
    }

    protected function saveOrderProduct($orderProduct): void
    {
        App::call()
            ->getRepository('OrderProduct')
            ->save($orderProduct);
    }

    public function removeProduct(): void
    {
        $id = App::call()->request->post('postData')['id'] ?? '';

        $orderProduct = $this->getOrderProduct($id);
        $orderProduct->deleted = 1;
        $this->saveOrderProduct($orderProduct);
    }

    public function retrieveProduct(): void
    {
        $id = App::call()->request->post('postData')['id'] ?? '';
        $orderProduct = $this->getOrderProduct($id);
        $orderProduct->deleted = 0;
        $this->saveOrderProduct($orderProduct);
    }

    public function changeOrderStatus(): array
    {
        $id = (int) ($_REQUEST['postData']['id'] ?? '');
        $orderStatus = (int) ($_REQUEST['postData']['status'] ?? '');

        $orderRepository = App::call()->getRepository('Order');
        $filters[] = [
            'col'   => 'id',
            'oper'  => '=',
            'value' => $id,
        ];
        $order = $orderRepository
            ->setQueryParams(null, $filters)
            ->getOne();

        $order->status_id = $orderStatus;
        $result = $orderRepository->save($order);

        if (!$result) {
            return $this->error();
        }
        return $this->success();
    }
}