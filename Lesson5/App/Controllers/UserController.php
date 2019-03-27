<?php

namespace App\Controllers;

use App\Engine\Auth;
use App\Models\User;

class AuthController extends Controller
{
    protected const TEMPLATE_NAME = 'login.twig';

    public function login($data = [])
    {

        echo $this->render([]);
    }

    public function auth()
    {
        $login = $_POST['login'] ?? '';
        $params = [];

        if (!empty($login)) {
            return $this->login();
        }
        $user = User::getOne('login', $login);
        if (!$user) {
            $params['error'] = 'Неверный логин или пароль';
        }
    }
}