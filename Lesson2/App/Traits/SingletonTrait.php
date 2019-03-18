<?php

namespace App\Traits;

trait SingletonTrait
{
    private static $instance;

    private function __construct()
    {
    }

    private function __clone() {}

    private function __wakeup() {}

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

}