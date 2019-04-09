<?php

namespace App\Controllers;

use App\App;
use App\Models\User;

class PersonalController extends Controller
{
    protected $template = 'personal_area.twig';

    public function index(): string
    {
        $authenticatedUser = User::getAuthorizedUser() ?? '';
        if (!$authenticatedUser) {
            header('Location: /user/login');
        }

        $userId = 0;
        if (!User::adminRole()) {
            $userId = (int) $authenticatedUser->id;
        }
        $orders = App::call()
            ->getRepository('Order')
            ->getOrdersList($userId);

        $statuses = App::call()
            ->getRepository('OrderStatus')
            ->getAllArray();

        $params = [
            'header'   => 'Personal area',
            'user'     => $authenticatedUser,
            'orders'   => $orders,
            'statuses' => $statuses,
        ];

        return $this->render($params);
    }
}