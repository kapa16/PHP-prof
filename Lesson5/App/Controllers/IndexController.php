<?php

namespace App\Controllers;

class IndexController extends Controller
{
    protected const TEMPLATE_NAME = 'gallery.twig';

    public function index($data = []): void
    {
        echo $this->render([]);
    }
}