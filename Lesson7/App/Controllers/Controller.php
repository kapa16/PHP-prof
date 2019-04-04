<?php

namespace App\Controllers;

use App\Views\View;

abstract class Controller
{
    protected $template = '';
    protected $view;
    public $data = [];
    protected static $visitedPages = [];

    protected function render(array $data = []): string
    {
        $this->logVisitedPages();
        $this->view = new View($data);
        return $this->view->render($this->template);
    }

    protected function logVisitedPages(): void
    {
        if (empty($_SESSION['user'])) {
            return;
        }
        $visitedPages = $_SESSION['visited_pages'] ?? [];
        $path = $_REQUEST['path'] ?? 'main';
        $visitedPages[] = str_replace('\\', '', $path);
        while (count($visitedPages) > 5) {
            array_shift($visitedPages);
        }
        $_SESSION['visited_pages'] = $visitedPages;
    }
}