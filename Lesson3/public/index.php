<?php

use App\Engine\Templater;

require_once '../vendor/autoload.php';

$gallery = \App\Models\Gallery::getAll();

$data = [
    'title' => 'Gallery',
    'header' => 'Gallery',
    'images' => $gallery
];

$twig = Templater::getInstance()->twig;
$indexTemplate = $twig->load('gallery.twig');
echo $indexTemplate->render($data);