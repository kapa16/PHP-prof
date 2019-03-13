<?php

namespace engine;

/**
 * Class Model реализация CRUD взаимодействия с БД
 * @package engine
 */
abstract class Model
{
    public const TABLE = '';

    public $id;

    /**
     * Получает все записи из базы данных, таблицы static::TABLE;
     */
    public static function getAll(): array
    {
        $db = new Db();
        $sql = 'SELECT * FROM `' . static::TABLE . '`;';
        return $db->query($sql, [], static::class);
    }

    /**
     * вставляет запись в базу данных
     */
    public function insert(): string
    {
        if (!static::TABLE) {
            return 'Не задано имя таблицы БД';
        }

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

        $db = new Db();
        $sql = 'INSERT INTO `' . static::TABLE . '` 
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
    public function delete()
    {

    }

    /**
     * изменяет запись в базе данных
     */
    public function update()
    {

    }
}