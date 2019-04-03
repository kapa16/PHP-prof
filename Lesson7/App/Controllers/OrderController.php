<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;

class OrderController extends Controller
{
    public function get(int $userId = 0)
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
        $queryParams = [
            'filters' => $filters,
            'sortFields' => $sortFields,
        ];

        $orders = Order::getAllArray($queryParams);


        foreach ($orders as &$order) {
            $filtersOrderProduct = [];
            $filtersOrderProduct[] = [
                'col'   => 'order_id',
                'oper'  => '=',
                'value' => $order['id'],
            ];
            $queryParams = [
                'filters' => $filtersOrderProduct,
            ];

            $order['products'] = OrderProduct::getAllArray($queryParams);
            foreach ($order['products'] as &$orderProduct) {
                $filtersProduct = [];
                $filtersProduct[] = [
                    'col'   => 'id',
                    'oper'  => '=',
                    'value' => $orderProduct['product_id'],
                ];
                $queryParams = [
                    'filters' => $filtersProduct
                ];
                $product = Product::getOne($queryParams);
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
