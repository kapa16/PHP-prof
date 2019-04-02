<?php


namespace App\Controllers;


use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

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
        $sortFields[] = ['col' => ' create_data', 'direction' => 'DESC'];
        OrderProduct::setQueryParams([], $filter, 'AND', $sortFields);
        $orders = Order::getAllArray();


        foreach ($orders as &$order) {
            $filter = [];
            $filter[] = [
                'col'   => 'order_id',
                'oper'  => '=',
                'value' => $order['id'],
            ];
            OrderProduct::setQueryParams([], $filter);
            $order['products'] = OrderProduct::getAllArray();

            foreach ($order['products'] as &$orderProduct) {
                Product::setQueryParams();
                $product = Product::getOne('id', $orderProduct['product_id']);
                $orderProduct['name'] = $product->name;
                $orderProduct['price'] = $product->price;
                $orderProduct['sum'] = $orderProduct['price'] * $orderProduct['quantity'];
            }
            unset($orderProduct);
        }
        unset($order);
        return $orders;
    }
}
