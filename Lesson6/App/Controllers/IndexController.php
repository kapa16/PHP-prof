<?php

namespace App\Controllers;

class IndexController extends Controller
{
    protected $template = 'gallery.twig';

    public function index(): string
    {
        return $this->render(['header' => 'Gallery']);
    }

    public function error($data)
    {
        $this->template = 'error.twig';
        return $this->render($data);
    }
}