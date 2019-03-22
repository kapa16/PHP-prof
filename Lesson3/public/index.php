<?php

use \App\Controllers\GalleryImageController;

require_once '../vendor/autoload.php';

$controller = new GalleryImageController('gallery.twig');
echo $controller->actionGallery();