<?php

namespace App\Models;

use App\App;

/**
 * Class Users - пользователи сайта
 * @package App\Models
 */
class User extends DataEntity
{
    public $login;
    public $password;
    public $name;
    public $lastname;
    public $email;
    public $role;


    public function authentication(string $password): void
    {
        if (!password_verify($password, $this->password)) {
            return;
        }
        $this->createSession();
    }

    public static function registration(array $userData = []): User
    {
        $userData['role'] = 0;
        $user = new self($userData);
        if ($user->insert()) {
            $user->createSession();
        }
        return $user;
    }

    public function authorized(): bool
    {
        return !empty(self::getAuthorizedUser());
    }

    public function createSession(): void
    {
        $session = App::call()->session;
        $session->user = $this;
        unset($session->password);
    }

    public static function logout(): void
    {
        App::call()->session->user = '';
    }

    public function insert(): bool
    {
        return App::call()
            ->getRepository('User')
            ->insert($this);
    }

    public static function getAuthorizedUser()
    {
        return App::call()->session->user;
    }

    public static function adminRole(): bool
    {
        return (bool) (self::getAuthorizedUser()->role ?? '');
    }
}