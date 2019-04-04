<?php


namespace App\Models\Repositories;


use App\App;
use App\Models\Order;

class OrderRepository extends Repository
{

    protected function getTableName(): string
    {
        return 'order';
    }

    protected function getEntityClass(): string
    {
        return Order::class;
    }

    public function getOrdersList(int $userId = 0): array
    {

        $filters = [];
        if ($userId) {
            $filters[] = [
                'col'   => 'user_id',
                'oper'  => '=',
                'value' => $userId,
            ];
        }
        $sortFields[] = [
            'col' => ' create_data',
            'direction' => 'DESC'
        ];

        $orders = $this
            ->setQueryParams(null, $filters, null, $sortFields)
            ->getAllArray();

        $statuses = App::getInstance()
            ->getRepository('OrderStatus')
            ->getAllArray();

        foreach ($orders as &$order) {
            $filters = [];
            $filters[] = [
                'col'   => 'order_id',
                'oper'  => '=',
                'value' => $order['id'],
            ];

            $indexStatus = array_search($order['status_id'], array_column($statuses, 'id'), false);
            $order['status'] = ucfirst($statuses[$indexStatus]['status']);

            $order['products'] = App::getInstance()
                ->getRepository('OrderProduct')
                ->setQueryParams(null, $filters)
                ->getAllArray();
            foreach ($order['products'] as &$orderProduct) {
                $filters = [];
                $filters[] = [
                    'col'   => 'id',
                    'oper'  => '=',
                    'value' => $orderProduct['product_id'],
                ];

                $product = App::getInstance()
                    ->getRepository('Product')
                    ->setQueryParams(null, $filters)
                    ->getOne();
                $orderProduct['name'] = $product->name;
                $orderProduct['price'] = $product->price;
                $orderProduct['sum'] = $orderProduct['price'] * $orderProduct['quantity'];
            }
            unset($orderProduct);
        }
        unset($order);
        return $orders;
    }


    public function getOrders()
    {
        $sql = "SELECT `o`.`id`,
                       `os`.`status`,
                       `o`.`status_id`,
                       `p`.`id` AS 'id',
                       `p`.`name`,
                       `op`.`quantity`,
                       `p`.`price`,
                       `op`.`id`       AS 'order_product_id',
                       `op`.`deleted`  AS 'deleted'
                FROM `order` `o`
                         LEFT JOIN `order_status` `os` ON `o`.`status_id` = `os`.`id`
                         RIGHT JOIN `order_product` `op` ON `op`.`order_id` = `o`.`id`
                         LEFT JOIN `products` `p` ON `p`.`id` = `op`.`product_id`
                ORDER BY `o`.`create_data`";
    }
}