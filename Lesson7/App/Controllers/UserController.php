<?php

namespace App\Controllers;

use App\App;

class UserController extends Controller
{
    protected $template = 'login.twig';

    public function login(): string
    {
        $params = [];
        $error = $this->data['error'] ?? '';
        $errors = [
            'nodata' => 'Enter login and password',
            'nouser' => 'Login or password wrong',
        ];

        if (!empty($errors[$error])) {
            $params['error'] = $errors[$error];
        }

        return $this->render($params);
    }

    private function loginError($error): void
    {
        header('Location: /user/login?error=' . $error);
        exit;
    }

    public function authentication(): void
    {
        $login = $_POST['login'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($login) || empty($password)) {
            $this->loginError('nodata');
        }

        $filters[] = [
            'col'   => 'login',
            'oper'  => '=',
            'value' => $login,
        ];

        $user = App::getInstance()
            ->getRepository('User')
            ->setQueryParams(null, $filters)
            ->getOne();

        if (!$user) {
            $this->loginError('nouser');
        }

        $user->authentication($password);
        if (!$user->authorized()){
            $this->loginError('nouser');
        }
        header('Location: /personal');
    }

}