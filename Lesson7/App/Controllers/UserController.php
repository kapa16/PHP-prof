<?php

namespace App\Controllers;

use App\App;

class UserController extends Controller
{
    protected $template = 'login.twig';

    public function login(): string
    {
        $params = [];
        $error = App::call()->request->get('error');
        $errors = [
            'nodata' => 'Enter login and password',
            'nouser' => 'Login or password wrong',
        ];

        if (!empty($errors[$error])) {
            $params = [
                'error' => $errors[$error]
            ];
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
        $login = App::call()->request->post('login');
        $password = App::call()->request->post('password');

        if (empty($login) || empty($password)) {
            $this->loginError('nodata');
        }

        $filters[] = [
            'col'   => 'login',
            'oper'  => '=',
            'value' => $login,
        ];

        $user = App::call()
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