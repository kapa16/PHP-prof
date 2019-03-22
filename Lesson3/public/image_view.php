<?php

use App\Controllers\GalleryImageController;

require_once '../vendor/autoload.php';

$id = $_GET['photo-id'] ?? 0;
$controller = new GalleryImageController('image_view.twig');
echo $controller->actionSingleImage($id);


