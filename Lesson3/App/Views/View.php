<?php

namespace App\Views;

use App\Engine\Templater;

class View
{
    private $data = [];

    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function render(string $templateName)
    {
        $twig = Templater::getInstance()->twig;
        $indexTemplate = $twig->load($templateName);
        return $indexTemplate->render($this->data);
    }
}