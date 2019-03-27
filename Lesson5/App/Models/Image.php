<?php

namespace App\Models;

class Image extends Model
{
    public $url;
    public $views;
    public $title;
    public $size;

    /**
     * Image constructor.
     * @param $url
     * @param $views
     * @param $title
     * @param $size
     */
    public function __construct($url = null, $views = null, $title = null, $size = null)
    {
        $this->url = $url;
        $this->views = $views;
        $this->title = $title;
        $this->size = $size;
    }

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