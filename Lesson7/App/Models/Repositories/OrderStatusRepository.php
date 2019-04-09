<?php


namespace App\Models\Repositories;


use App\Models\OrderStatus;

class OrderStatusRepository extends Repository
{

    protected function getTableName(): string
    {
        return 'order_status';
    }

    protected function getEntityClass(): string
    {
        return OrderStatus::class;
    }
}