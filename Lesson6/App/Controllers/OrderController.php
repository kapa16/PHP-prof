<?php


namespace App\Controllers;


use App\Models\Order;
use App\Models\OrderProduct;

class OrderController extends Controller
{
    public function get(int $userId = 0)
    {
        $filter = [];
        if ($userId) {
            $filter[] = [
                'col'   => 'user_id',
                'oper'  => '=',
                'value' => $userId,
            ];
        }
        $sortFields = [' create_data DESC'];
        $orders = Order::getAllArray(null, $filter, ' AND ', $sortFields);


        foreach ($orders as &$order) {
            $filter = [];
            $filter[] = [
                'col'   => 'order_id',
                'oper'  => '=',
                'value' => $order['id'],
            ];
            $order['products'] = OrderProduct::getAllArray(null, $filter);
            var_dump($filter);
        }
        unset($order);
        return $orders;
    }
}