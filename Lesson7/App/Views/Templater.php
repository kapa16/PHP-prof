<?php

namespace App\Views;

use App\App;
use App\Traits\SingletonTrait;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Templater
{
    use SingletonTrait;

    public $loader;
    public $twig;
    /**
     * Templater constructor.
     */
    public function __construct()
    {
        $this->loader = new FilesystemLoader(App::getInstance()->getConfig('templateDir'));
        $this->twig = new Environment($this->loader);
    }
}