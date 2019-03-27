<?php
/**
 * Created by PhpStorm.
 * User: kapa
 * Date: 28.03.2019
 * Time: 0:17
 */

namespace App\Controllers;

use App\Models\User;

class RegistrationController extends Controller
{
    protected const TEMPLATE_NAME = 'registration.twig';

    public function index(): void
    {
        echo $this->render([]);
    }

    public function send()
    {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];

        $user = new User($login, $password, $name, $lastname, $email);
        if ($user->insert()) {
            $user->createSession();
            header('Location: /personal');
        }
    }
}