<?php

use \App\Engine\Templater;

require_once '../config/config.php';

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    require_once __DIR__ . '/../' . $class . '.php';
});

$twig = Templater::getInstance();