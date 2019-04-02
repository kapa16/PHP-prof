<?php

namespace App\Models;

class Category extends Model
{
    protected $name;
    protected $discount;
    protected $parent_category_id;

    protected static function getTableName(): string
    {
        return 'categories';
    }
}