<?php

namespace engine\Models;

use engine\Model;

class Category extends Model
{
    public const TABLE = 'categories';

    protected $name;
    protected $discount;
    protected $parent_category_id;

    public function __construct($name = '', $discount = 0, $parent_category_id = 0)
    {
        $this->name = $name;
        $this->discount = $discount;
        $this->parent_category_id = $parent_category_id;
    }
}