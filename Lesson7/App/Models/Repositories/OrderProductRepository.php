<?php


namespace App\Models\Repositories;


use App\Models\Order;

class OrderProductRepository extends Repository
{

    protected function getTableName(): string
    {
        return 'order_product';
    }

    protected function getEntityClass(): string
    {
        return Order::class;
    }
}