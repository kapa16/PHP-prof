<?php

namespace App\Controllers;

use App\Views\View;

abstract class Controller
{
    protected const TEMPLATE_NAME = '';
    protected $view;
    public $data = [];
    protected static $visitedPages = [];

    protected function render(array $data = []): string
    {
        $this->logVisitedPages();
        $this->view = new View($data);
        return $this->view->render(static::TEMPLATE_NAME);
    }

    protected function logVisitedPages(): void
    {
        if (empty($_SESSION['user'])) {
            return;
        }
        $visitedPages = $_SESSION['visited_pages'] ?? [];
        $visitedPages[] = str_replace('\\', '', $_REQUEST['path']) ?: 'main';
        while (count($visitedPages) > 5) {
            array_shift($visitedPages);
        }
        $_SESSION['visited_pages'] = $visitedPages;
    }
}