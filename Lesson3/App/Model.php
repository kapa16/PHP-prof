<?php

namespace App;

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
        return $db->query($sql, [], static::class);
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

        $db->exec($sql, $params);

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
        return $db->exec($sql, [':id' => $this->id]);
    }

    /**
     * изменяет запись в базе данных
     */
    public function update()
    {

    }
}