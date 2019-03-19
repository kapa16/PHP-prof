<?php
/**
 * Created by PhpStorm.
 * User: kapa
 * Date: 19.03.2019
 * Time: 22:57
 */

namespace App\Models;


class Gallery extends Model
{

    public const TABLE = 'images';

    public $url;
    public $views;
    public $title;
    public $size;

    /**
     * Gallery constructor.
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
}