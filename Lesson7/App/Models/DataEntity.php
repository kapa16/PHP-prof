<?php

namespace App\Models;

use App\Engine\Db;
use App\Engine\QueryBuilder;
use RuntimeException;

/**
 * Class Model реализация CRUD взаимодействия с БД
 * @package App
 */
abstract class DataEntity
{
    public $id;

    public function __construct($modelData = [])
    {
        $fields = get_class_vars(static::class);
        foreach ($fields as $fieldName => $field) {
            $this->$fieldName = $modelData[$fieldName] ?? null;
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->$name)) {
            $this->$name = $value;
        }
    }

    public function __get($name)
    {
        return $this->$name ?? null;
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }

}