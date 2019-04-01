<?php

namespace App\Models;

/**
 * Class Users - пользователи сайта
 * @package App\Models
 */
class User extends Model
{
    public $login;
    public $password;
    public $name;
    public $lastname;
    public $email;
    public $role;

    protected static function getTableName(): string
    {
        return 'users';
    }

    public function authentication(string $password): void
    {
        if (!password_verify($password, $this->password)) {
            return;
        }
        $this->createSession();
    }

    public static function registration(array $userData = []): User
    {
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
        $this->excludeQueryParams[] = 'passwordCheck';
        return parent::insert();
    }

    public static function getAuthorized()
    {
        return $_SESSION['user'] ?? '';
    }
}