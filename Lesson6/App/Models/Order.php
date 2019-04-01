<?php

namespace App\Models;

use RuntimeException;

class Order extends Model
{
    public $status_id;
    public $user_id;
    public $deleted;
    public $create_data;
    public $change_data;

    protected static function getTableName(): string
    {
        return 'order';
    }

    public static function create(int $userId = null, array $orderProducts = []): bool
    {
        $orderData = [
            'status_id' => 1,
            'user_id' => $userId
        ];

        $order = new self($orderData);

        if (!$order->save()) {
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
            if (!$product->save()) {
                throw new RuntimeException('Error order product create');
            }
        }

        return true;
    }

    public function getFullOrders()
    {
//        $sql = "SELECT * FROM (SELECT `order`.`id`, os.`status`, `status_id`, `create_data` FROM `order`
//LEFT JOIN `order_status` os ON `order`.`status_id` = os.`id`) AS ord
//LEFT JOIN
//(SELECT `products`.`id` AS 'id',
//        `order_id`,
//       `name`,
//       `quantity`,
//       `price`,
//       `order_product`.`id` AS 'order_product_id',
//       `order_product`.`deleted` AS 'deleted'
//FROM `order_product`
//         LEFT JOIN `products` ON `products`.`id` = `order_product`.`product_id`) as prod
//ON ord.id = prod.order_id
//ORDER BY `create_data`";
    }
}