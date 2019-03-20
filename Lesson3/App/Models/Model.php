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

    protected static function getTableName(): string
    {
        if (!static::TABLE) {
            exit('Не задано имя таблицы БД');
        } else {
            return static::TABLE;
        }
    }

    protected function getQueryParams($excludeVars = []): array
    {
        $vars = get_object_vars($this);
        $data = [
            'params' => [],
            'fields' => [],
            'set' => []
        ];

        foreach ($vars as $key => $val) {
            if (in_array($key, $excludeVars, false)) {
                continue;
            }
            $data['params'][":{$key}"] = $val;
            $data['fields'][] = "`{$key}`";
            $data['set'][] = "`$key`=:{$key}";
        }
        return $data;
    }

    /**
     * Получает все записи из базы данных, таблицы static::TABLE;
     */
    public static function getAll(): array
    {
        $db = Db::getInstance();
        $sql = 'SELECT * FROM `' . static::getTableName() . '`;';
        return $db->queryAll($sql, [], static::class);
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
        return $db->queryOne($sql, [':id' => $id], static::class);
    }

    /**
     * вставляет запись в базу данных
     */
    public function insert(): string
    {
        $data = $this->getQueryParams(['id']);

        $db = Db::getInstance();
        $sql = 'INSERT INTO `' . static::getTableName() . '` 
        (' . implode(', ', $data['fields']) . ') VALUES
        (' . implode(', ', array_keys($data['params'])) . ');';

        $db->query($sql, $data['params']);

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
        if (!$this->id) {
            exit('Не задан ID');
        }

        $data = $this->getQueryParams();

        $db = Db::getInstance();
        $sql = 'UPDATE `' . static::getTableName() . '` SET ' . implode(', ', $data['set']) . ' WHERE `id`=:id;';
        return $db->exec($sql, $data['params']);
    }
}