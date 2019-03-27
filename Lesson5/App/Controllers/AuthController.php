<?php

namespace App\Controllers;

use App\Engine\Auth;

class AuthController extends Controller
{
    protected const TEMPLATE_NAME = 'login.twig';

    public function login($data = [])
    {
        if (empty($_POST)) {
            echo $this->render([]);
        }
        $auth = new Auth();
        $auth->loginUser($_POST);
    }
}