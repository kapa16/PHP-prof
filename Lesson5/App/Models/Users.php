<?php

namespace App\Models;

/**
 * Class Users - пользователи сайта
 * @package App\Models
 */
class Users extends Model
{
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

    protected static function getTableName(): string
    {
        return 'users';
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