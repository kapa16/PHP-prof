<?php

namespace App\Models;

use App\Engine\Db;

/**
 * Class Model реализация CRUD взаимодействия с БД
 * @package App
 */
abstract class Model
{
    public const TABLE = '';

    public $id;

    protected static function getTableName()
    {
        if (!static::TABLE) {
            exit('Не задано имя таблицы БД');
        } else {
            return static::TABLE;
        }
    }

    /**
     * Получает все записи из базы данных, таблицы static::TABLE;
     */
    public static function getAll(): array
    {
        $db = Db::getInstance();
        $sql = 'SELECT * FROM `' . static::getTableName() . '`;';
        return $db->fetchAllClass($sql, [], static::class);
    }

    /**
     * Получает все записи из базы данных, таблицы static::TABLE;
     * @param $id
     * @return
     */
    public static function getOne($id)
    {
        $db = Db::getInstance();
        $sql = 'SELECT * FROM `' . static::getTableName() . '` WHERE `id`=:id;';
        // TODO change for get ONE
        return $db->fetchAllClass($sql, [':id' => $id], static::class)[0];
    }
    /**
     * вставляет запись в базу данных
     */
    public function insert(): string
    {


        $vars = get_object_vars($this);

        $params = [];
        $fields = [];
        foreach ($vars as $key => $val) {
            if ($key === 'id') {
                continue;
            }
            $params[':' . $key] = $val;
            $fields[] = "`$key`";
        }

        $db = Db::getInstance();
        $sql = 'INSERT INTO `' . static::getTableName() . '` 
        (' . implode(', ', $fields) . ') VALUES
        (' . implode(', ', array_keys($params)) . ');';

        $db->query($sql, $params);

        if (!$db) {
            return 'Данные не записаны в БД';
        }

        $this->id = $db->getInsertedId();
        return 'Данные записаны в БД';
    }

    /**
     * удаляет запись из базы данных
     */
    public function delete(): bool
    {
        if (!$this->id) {
            exit('Не задан ID');
        }
        $db = Db::getInstance();
        $sql = 'DELETE FROM `' . static::getTableName() . '` WHERE `id`=:id;';
        return $db->query($sql, [':id' => $this->id]);
    }

    /**
     * изменяет запись в базе данных
     */
    public function update()
    {

    }
}