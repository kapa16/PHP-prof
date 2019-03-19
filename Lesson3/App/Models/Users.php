<?php

namespace App\Models;

/**
 * Class Users - пользователи сайта
 * @package App\Models
 */
class Users extends Model
{
    public const TABLE = 'users';

    public $login;
    public $name;
    public $lastname;
    public $email;

    public function __construct($login = '', $name = '', $lastName = '', $email = '')
    {
        $this->login = $login;
        $this->name = $name;
        $this->lastname = $lastName;
        $this->email = $email;
    }

    /**
     * Возвращяет ФИО пользователя
     * @return string
     */
    public function getFullName()
    {
        return $this->name . ' ' . $this->lastname;
    }
}