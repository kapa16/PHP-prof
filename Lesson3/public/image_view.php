<?php

use App\Engine\Templater;

require_once '../vendor/autoload.php';

$id = $_GET['photo-id'] ?? 0;

$gallery = \App\Models\Gallery::getOne($id);

$data = [
    'title' => 'Gallery',
    'header' => 'Gallery',
    'image' => $gallery
];

$twig = Templater::getInstance()->twig;
$indexTemplate = $twig->load('image_view.twig');
echo $indexTemplate->render($data);