<?php

namespace App\Controllers;

use App\Models\Image;

class GalleryImageController extends Controller
{
    public function actionGallery()
    {
        $sortFields = ['views'];
        $gallery = Image::getAll($sortFields);
        $data = [
            'title' => 'Gallery',
            'header' => 'Gallery',
            'images' => $gallery
        ];
        return $this->getView($data);
    }

    public function actionSingleImage($id)
    {
        $image = Image::getOne($id);
        $image->increaseImageViews()->update();
        $data = [
            'title' => 'Gallery',
            'header' => 'Full-size image',
            'image' => $image
        ];
        return $this->getView($data);
    }
}