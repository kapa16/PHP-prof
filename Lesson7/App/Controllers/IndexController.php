<?php

namespace App\Controllers;

use App\App;

class IndexController extends Controller
{
    protected $template = 'gallery.twig';

    public function index(): string
    {
        $images = App::call()
            ->getRepository('Image')
            ->getAllArray();
        $params = [
            'header' => 'Gallery',
            'images' => $images
        ];
        return $this->render($params);
    }

    public function error($data): string
    {
        $this->template = 'error.twig';
        return $this->render($data);
    }
}