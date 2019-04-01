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
    protected $template = 'registration.twig';

    public function index(): string
    {
        return $this->render();
    }

    public function send(): void
    {
        $user = User::registration($_POST);
        $page = $user->authorized() ? 'personal' : 'registration';
        header('Location: /' . $page);
    }
}