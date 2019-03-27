<?php

namespace App\Engine;

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
        $this->loader = new FilesystemLoader(TEMPLATE_DIR);
        $this->twig = new Environment($this->loader);
    }
}