<?php


namespace App\Models\Repositories;


use App\Models\Category;

class CategoryRepository extends Repository
{

    protected function getTableName(): string
    {
        return 'categories';
    }

    protected function getEntityClass(): string
    {
        return Category::class;
    }

}