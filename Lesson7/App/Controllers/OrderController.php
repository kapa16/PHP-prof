<?php

namespace App\Controllers;

use App\App;

class OrderController extends Controller
{
//    public function get(int $userId = 0): array
//    {
//
//        $filters = [];
//        if ($userId) {
//            $filters[] = [
//                'col'   => 'user_id',
//                'oper'  => '=',
//                'value' => $userId,
//            ];
//        }
//        $sortFields[] = [
//            'col' => ' create_data',
//            'direction' => 'DESC'
//        ];
//
//        $orders = App::getInstance()
//            ->getRepository('Order')
//            ->setQueryParams(null, $filters, null, $sortFields)
//            ->getAllArray();
//
//        foreach ($orders as &$order) {
//            $filters = [];
//            $filters[] = [
//                'col'   => 'order_id',
//                'oper'  => '=',
//                'value' => $order['id'],
//            ];
//
//            $order['products'] = App::getInstance()
//                ->getRepository('OrderProduct')
//                ->setQueryParams(null, $filters)
//                ->getAllArray();
//            foreach ($order['products'] as &$orderProduct) {
//                $filters = [];
//                $filters[] = [
//                    'col'   => 'id',
//                    'oper'  => '=',
//                    'value' => $orderProduct['product_id'],
//                ];
//
//                $product = App::getInstance()
//                    ->getRepository('Product')
//                    ->setQueryParams(null, $filters)
//                    ->getOne();
//                $orderProduct['name'] = $product->name;
//                $orderProduct['price'] = $product->price;
//                $orderProduct['sum'] = $orderProduct['price'] * $orderProduct['quantity'];
//            }
//            unset($orderProduct);
//        }
//        unset($order);
//        return $orders;
//    }
}
