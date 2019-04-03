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
    protected $template = 'personal_area.twig';

    public function index(): string
    {
        $authenticatedUser = $_SESSION['user'] ?? '';
        if (!$authenticatedUser) {
            header('Location: /login');
        }

        $ordersController = new OrderController();
        $orders = $ordersController->get($authenticatedUser->id);

        $params = [
            'header' => 'Personal area',
            'user' => $authenticatedUser,
            'orders' => $orders
        ];
        return $this->render($params);
    }
}