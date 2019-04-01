<?php

namespace App\Controllers\Api;

abstract class ApiController
{
    public $data = [];
    protected $errorMessage;
    protected $resultData;

    protected function success(): array
    {
        return ['result' => 1];
    }

    protected function error(): array
    {
        return ['result' => 0];
    }
}