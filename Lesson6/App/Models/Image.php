<?php

namespace App\Models;

class Image extends Model
{
    public $url;
    public $views;
    public $title;
    public $size;

    protected static function getTableName(): string
    {
        return 'images';
    }

    public function increaseImageViews(int $count = 1): self
    {
        $this->views += $count;
        return $this;
    }
}