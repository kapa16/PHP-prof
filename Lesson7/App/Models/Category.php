<?php

namespace App\Models;

class Category extends DataEntity
{
    protected $name;
    protected $discount;
    protected $parent_category_id;
}