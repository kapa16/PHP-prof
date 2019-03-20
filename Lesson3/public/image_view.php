<?php

use App\Engine\Templater;

require_once '../vendor/autoload.php';

$id = $_GET['photo-id'] ?? 0;

$image = \App\Models\Image::getOne($id);
$image->addView()->update();

$data = [
    'title' => 'Gallery',
    'header' => 'Full-size image',
    'image' => $image
];

$twig = Templater::getInstance()->twig;
$indexTemplate = $twig->load('image_view.twig');
echo $indexTemplate->render($data);