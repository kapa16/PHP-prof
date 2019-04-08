<?php
/**
 * Created by PhpStorm.
 * User: kapa
 * Date: 28.03.2019
 * Time: 0:17
 */

namespace App\Controllers;

use App\App;
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
        $user = User::registration(App::call()->request->post());
        $page = $user->authorized() ? 'personal' : 'registration';
        header('Location: /' . $page);
    }
}