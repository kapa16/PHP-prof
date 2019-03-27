<?php

namespace App\Controllers;

use App\Engine\Templater;
use Twig\Environment;

abstract class Controller
{
    protected const TEMPLATE_NAME = '';
    /** @var Environment */
    protected $twig;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->twig = Templater::getInstance()->twig;
    }

    /**
     * Return view from template
     * @param $data array parameters for template
     * @return mixed
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function render($data)
    {
        $indexTemplate = $this->twig->load(static::TEMPLATE_NAME);
        return $indexTemplate->render($data);
    }
}