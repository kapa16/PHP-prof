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
}