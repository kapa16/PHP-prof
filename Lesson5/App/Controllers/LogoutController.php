<?php

namespace App\Controllers;

use App\Models\User;

class LogoutController extends Controller
{
    public function index(): void
    {
        User::logout();
        header('Location: /');
    }
}