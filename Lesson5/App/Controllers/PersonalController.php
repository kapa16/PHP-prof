<?php
/**
 * Created by PhpStorm.
 * User: kapa
 * Date: 28.03.2019
 * Time: 0:19
 */

namespace App\Controllers;


class PersonalController extends Controller
{
    protected const TEMPLATE_NAME = 'personal_area.twig';

    public function index()
    {
        echo $this->render(['user' => $_SESSION['user']]);
    }
}