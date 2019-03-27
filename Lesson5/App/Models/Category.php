<?php

namespace App\Models;

class Category extends Model
{
    protected $name;
    protected $discount;
    protected $parent_category_id;

    public function __construct($name = '', $discount = 0, $parent_category_id = 0)
    {
        $this->name = $name;
        $this->discount = $discount;
        $this->parent_category_id = $parent_category_id;
    }

    protected static function getTableName(): string
    {
        return 'categories';
    }
}