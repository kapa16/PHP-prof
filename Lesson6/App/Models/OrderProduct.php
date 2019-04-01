<?php


namespace App\Models;


class OrderProduct extends Model
{
    public $order_id;
    public $product_id;
    public $quantity;
    public $fixed_price;
    public $deleted;
    public $create_data;
    public $change_data;

    protected static function getTableName(): string
    {
        return 'order_product';
    }

}