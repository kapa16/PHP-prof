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

    public function index(): void
    {
        $authenticatedUser = $_SESSION['user'] ?? '';
        if (!$authenticatedUser) {
            header('Location: /login');
        }
        $params = [
            'header' => 'Personal area',
            'user' => $authenticatedUser,
            'pages' => $_SESSION['visited_pages']
        ];
        echo $this->render($params);
    }
}