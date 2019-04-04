<?php


namespace App\Models\Repositories;


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
}