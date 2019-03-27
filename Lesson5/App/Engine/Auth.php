<?php

namespace App\Engine;

use \App\Models\User;

class Auth
{
    private function getDataFromPost($postParams, $fields)
    {
        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $postParams[$field] ?? '';
        }
        return $data;
    }

    public function loginUser($postParams)
    {
        $fields = ['login', 'password'];
        $userData = $this->getDataFromPost($postParams, $fields);

        $user = User::getOne('login', $userData['login']);

        if (!$user) {
            return false;
        }

        if (password_verify($userData['password'], $user.password)) {
            unset($user['password']);
            return $user;
        }

        return false;
    }

}