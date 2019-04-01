<?php

namespace App\Views;

use App\Engine\Templater;
use Twig\Environment;

class View
{
    /** @var Environment */
    protected $twig;
    private $data;

    public function __construct($data = [])
    {
        $this->data = $data;
        $this->data['auth'] = $_SESSION['user'] ?: '';
        $this->twig = Templater::getInstance()->twig;
    }

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

    public function render(string $templateName): string
    {
        $indexTemplate = $this->twig->load($templateName);
        return $indexTemplate->render($this->data);
    }
}