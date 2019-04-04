<?php

namespace App\Models;

use App\App;
use RuntimeException;

class Order extends DataEntity
{
    public $status_id;
    public $user_id;
    public $deleted;
    public $create_data;
    public $change_data;

    public static function create(int $userId = null, array $orderProducts = []): bool
    {
        $orderData = [
            'status_id' => 1,
            'user_id' => $userId
        ];

        $order = new self($orderData);

        if (!App::getInstance()->getRepository('Order')->save($order)) {
            throw new RuntimeException('Error order create');
        }

        foreach ($orderProducts as $orderProduct) {
            $data = [
                'order_id' => $order->id,
                'product_id' => $orderProduct['id'],
                'quantity' => $orderProduct['quantity'],
                'fixed_price' => 0
            ];
            $product = new OrderProduct($data);
            if (!App::getInstance()->getRepository('OrderProduct')->save($product)) {
                throw new RuntimeException('Error order product create');
            }
        }

        return true;
    }
}