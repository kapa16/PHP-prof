<?php


namespace App\Controllers;


use App\Models\User;
use RuntimeException;

class CategoryController extends Controller
{
    public function index()
    {
        if (!User::adminRole()) {
            throw new RuntimeException('No access');
        }
    }
}