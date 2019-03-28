<?php

namespace App\Controllers;

use App\Views\View;

abstract class Controller
{
    protected const TEMPLATE_NAME = '';
    protected $view;
    public $data = [];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
    }

    protected function render(array $data = []): string
    {
        $this->view = new View($data);
        return $this->view->render(static::TEMPLATE_NAME);
    }
}