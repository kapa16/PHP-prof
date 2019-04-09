<?php

namespace App\Controllers;

use App\App;
use App\Models\User;
use App\Views\View;

abstract class Controller
{
    protected $template = '';
    protected $view;
    protected static $visitedPages = [];

    protected function render(array $data = []): string
    {
        $this->logVisitedPages();
        $this->view = new View($data);
        return $this->view->render($this->template);
    }

    protected function logVisitedPages(): void
    {
        if (empty(User::getAuthorizedUser())) {
            return;
        }
        $visitedPages = App::call()->session->visitedPages ?? [];
        $path = $_REQUEST['path'] ?? 'main';
        $visitedPages[] = str_replace('\\', '', $path);
        while (count($visitedPages) > 5) {
            array_shift($visitedPages);
        }
        App::call()->session->visitedPages = $visitedPages;
    }
}