<?php


namespace App\Models\Repositories;

use App\Models\User;

class UserRepository extends Repository
{

    protected function getTableName(): string
    {
        return 'users';
    }

    protected function getEntityClass(): string
    {
        return User::class;
    }
}