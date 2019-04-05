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
        return !empty($_SESSION['user']);
    }

    public function createSession(): void
    {
        $_SESSION['user'] = $this;
        unset($_SESSION['user']->password);
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }

    public function insert(): bool
    {
        return App::getInstance()
            ->getRepository('User')
            ->insert($this);
    }

    public static function getAuthorized()
    {
        return $_SESSION['user'] ?? '';
    }

    public static function adminRole(): bool
    {
        return (bool) ($_SESSION['user']->role ?? '');
    }
}