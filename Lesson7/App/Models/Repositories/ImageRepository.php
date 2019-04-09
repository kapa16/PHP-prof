<?php


namespace App\Models\Repositories;


use App\Models\Image;

class ImageRepository extends Repository
{

    protected function getTableName(): string
    {
        return 'images';
    }

    protected function getEntityClass(): string
    {
        return Image::class;
    }
}