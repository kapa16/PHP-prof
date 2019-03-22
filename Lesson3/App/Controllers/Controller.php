<?php

namespace App\Controllers;

use App\Engine\Templater;

abstract class Controller
{
    private $templateName;

    /**
     * Controller constructor.
     * @param string $templateName
     */
    public function __construct(string $templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * Return view from template
     * @param $data array parameters for template
     * @return mixed
     */
    protected function getView($data)
    {
        $twig = Templater::getInstance()->twig;
        $indexTemplate = $twig->load($this->templateName);
        return $indexTemplate->render($data);
    }


}