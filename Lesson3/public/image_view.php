<?php

use App\Engine\Templater;

require_once '../vendor/autoload.php';

//use \App\Views\View;
//
//$view = new View();
//$view->title = 'Gallery';
//$view->header = 'Gallery';

//echo $view->render('layout.twig');
$gallery = \App\Models\Gallery::ge();

$data = [
    'title' => 'Gallery',
    'header' => 'Gallery',
    'images' => $gallery
];

$twig = Templater::getInstance()->twig;
$indexTemplate = $twig->load('image_view.twig');
echo $indexTemplate->render($data);