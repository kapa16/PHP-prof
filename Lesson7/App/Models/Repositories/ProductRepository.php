<?php


namespace App\Models\Repositories;


use App\Models\Product;

class ProductRepository extends Repository
{

    protected function getTableName(): string
    {
        return 'products';
    }

    protected function getEntityClass(): string
    {
        return Product::class;
    }
}