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

    /**
     * User constructor.
     * @param $login
     * @param $password
     * @param $name
     * @param $lastname
     * @param $email
     */
    public function __construct($login = null, $password = null, $name = null, $lastname = null, $email = null)
    {
        $this->login = $login;
        $this->password = $password;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    protected static function getTableName(): string
    {
        return 'users';
    }

    public function authentication($password)
    {
        if (!password_verify($password, $this->password)) {
            return false;
        }
        $this->createSession();
        return true;
    }

    public function createSession(): void
    {
        $_SESSION['user'] = $this;
        unset($_SESSION['user']['password']);
    }

    public function clearSession(): void
    {
        unset($_SESSION['user']);
    }

}