<?php

require_once '../vendor/autoload.php';

use \App\Engine\Templater;

$twig = Templater::getInstance()->twig;
$indexTemplate = $twig->load('layout.twig');
echo $indexTemplate->render([
    'title' => 'Gallery',
    'header' => 'Gallery'
]);