<?php

namespace App\Controllers;

use App\App;

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

        $statuses = App::getInstance()
            ->getRepository('OrderStatus')
            ->getAllArray();

        $params = [
            'header' => 'Personal area',
            'user' => $authenticatedUser,
            'orders' => $orders,
            'statuses' => $statuses
        ];
        return $this->render($params);
    }
}