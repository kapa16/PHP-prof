<?php

namespace App\Models;

class OrderStatus extends Model
{
    public $status;

    protected static function getTableName(): string
    {
        return 'order_status';
    }
}