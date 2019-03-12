<?php

namespace App;

/**
 * Class Model реализация CRUD взаимодействия с БД
 * @package App
 */
class Model
{
    public const TABLE = '';

    public $id;

    /**
     * Получает все записи из базы данных, таблицы static::TABLE;
     */
    public static function getAll()
    {
        $db = new Db();
        $sql = 'SELECT * FROM `' . static::TABLE . '`;';
        return $db->query($sql, [], static::class);
    }

    /**
     * вставляет запись в базу данных
     */
    public static function insert()
    {

    }

    /**
     * удаляет запись из базы данных
     */
    public static function delete()
    {

    }

    /**
     * изменяет запись в базе данных
     */
    public static function update()
    {

    }
}