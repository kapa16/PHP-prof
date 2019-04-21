<?php


namespace App\Controllers;


use App\Models\User;
use RuntimeException;

class CategoryController extends Controller
{
    protected $template = 'category.twig';

    public function index(): string
    {
        if (!User::adminRole()) {
            throw new RuntimeException('No access');
        }
        return $this->render();
    }
}