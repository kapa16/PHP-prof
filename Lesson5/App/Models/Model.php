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

    public static $sortFields = [];
    public static $reversSort = false;

    public $id;

    abstract protected static function getTableName();

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

    protected function validateId(): void
    {
        if (!$this->id) {
            exit('Не задан ID');
        }
    }

    protected function getQueryParams($excludeVars = []): array
    {
        $vars = get_object_vars($this);
        $data = [
            'params' => [],
            'fields' => [],
            'set'    => [],
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

    protected static function generateSelectQuery(int $limitFrom = null, int $limitCount = null): string
    {
        $sql = 'SELECT * FROM `' . static::getTableName() . '`';

        if (count(static::$sortFields) > 0) {
            $strSortFields = implode(', ', static::$sortFields);
            $sql .= " ORDER BY {$strSortFields} " . (static::$reversSort ? 'ASC' : 'DESC');
        }

//        if ($limitCount) {
//            $sql .= ' LIMIT :limitFrom, :limitCount';
//        }
        if ($limitFrom || $limitCount) {
            // TODO Почему не работает подстановка
            $sql .= " LIMIT {$limitFrom}, {$limitCount}";
//            $sql .= ' LIMIT :from, :count;';
        }

        return $sql;
    }

    /**
     * Получает все записи из базы данных
     * @return array
     */
    public static function getAll(): array
    {
        $db = Db::getInstance();
        $sql = static::generateSelectQuery();

        return $db->queryAll($sql, [], static::class);
    }

    /**
     * Получает лимитированное количестов записей из базы данных
     * @param $limitFrom
     * @param $limitCount
     * @return array
     */
    public static function getLimit($limitFrom, $limitCount): array
    {
        $db = Db::getInstance();
        $sql = static::generateSelectQuery($limitFrom, $limitCount);
        // TODO Почему не работает подстановка
//        $params = [':from' => $limitFrom, ':count' => $limitCount];
        $params = [];
        return $db->queryAll($sql, $params, static::class);
    }

    /**
     * Retrieves a record from a database by unique field
     * @param $fieldName
     * @param $fieldValue
     * @return mixed
     */
    public static function getOne($fieldName, $fieldValue)
    {
        $db = Db::getInstance();
        $sql = 'SELECT * FROM `' . static::getTableName() . "` WHERE `$fieldName`=:$fieldName;";
        return $db->queryOne($sql, [":$fieldName" => $fieldValue], static::class);
    }

    public static function getCountRows()
    {
        $db = Db::getInstance();
        $sql = 'SELECT COUNT(*) count FROM `' . static::getTableName() . '`';
        return $db->queryOneAssoc($sql, [])['count'];
    }

    /**
     * вставляет запись в базу данных
     */
    public function insert(): bool
    {
        $data = $this->getQueryParams(['id']);

        $db = Db::getInstance();
        $sql = 'INSERT INTO `' . static::getTableName() . '` 
        (' . implode(', ', $data['fields']) . ') VALUES
        (' . implode(', ', array_keys($data['params'])) . ');';

        $db->exec($sql, $data['params']);

        if (!$db) {
            return false;
        }

        $this->id = $db->getInsertedId();
        return true;
    }

    /**
     * удаляет запись из базы данных
     */
    public function delete(): bool
    {
        $this->validateId();

        $db = Db::getInstance();
        $sql = 'DELETE FROM `' . static::getTableName() . '` WHERE `id`=:id;';
        return $db->query($sql, [':id' => $this->id]);
    }

    /**
     * изменяет запись в базе данных
     */
    public function update()
    {
        $this->validateId();

        $data = $this->getQueryParams();

        $db = Db::getInstance();
        $sql = 'UPDATE `' . static::getTableName() . '` SET ' . implode(', ', $data['set']) . ' WHERE `id`=:id;';
        return $db->exec($sql, $data['params']);
    }
}