<?php

namespace App\Engine;

use App\Models\User;

/**
 * @property array|string visitedPages
 * @property User|string user
 */
class Session
{
    public function __construct()
    {
        session_start();
    }
    public function __get($name)
    {
        return $_SESSION[$name] ?? '';
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }
}