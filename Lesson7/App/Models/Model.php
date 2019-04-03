<?php

namespace App\Models;

use App\Engine\Db;
use App\Engine\QueryBuilder;
use RuntimeException;

/**
 * Class Model реализация CRUD взаимодействия с БД
 * @package App
 */
abstract class Model
{
    public $id;
    protected $excludeQueryParams;

    abstract protected static function getTableName();

    protected static function getDb(): Db
    {
        /** @var Db Db */
        return Db::getInstance();
    }

    public function __construct($modelData = [])
    {
        $fields = get_class_vars(static::class);
        foreach ($fields as $fieldName => $field) {
            $this->$fieldName = $modelData[$fieldName] ?? null;
        }
        $this->excludeQueryParams = ['excludeQueryParams'];
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

    protected function validateId(): void
    {
        if (!$this->id) {
            exit('Не задан ID');
        }
    }

    protected function getQueryParams(): array
    {
        $vars = get_object_vars($this);
        $data = [
            'params' => [],
            'fields' => [],
            'set'    => [],
        ];

        foreach ($vars as $key => $val) {
            if (in_array($key, $this->excludeQueryParams, false)) {
                continue;
            }
            $data['params'][":{$key}"] = htmlspecialchars($val);
            $data['fields'][] = "`{$key}`";
            $data['set'][] = "`$key`=:{$key}";
            if ($key === 'password') {
                $data['params'][":{$key}"] = password_hash($val, PASSWORD_BCRYPT);
            }
        }
        return $data;
    }

    protected static function generateQuery($queryParams): string
    {
        return (new QueryBuilder($queryParams))->generateQuery(static::getTableName());
    }

    /**
     * Получает все записи из базы данных
     * @param array $queryParams
     * @return array
     */
    public static function getAll(array $queryParams = []): array
    {

        $sql = static::generateQuery($queryParams);
        return static::getDb()->queryAll($sql, [], static::class);
    }

    public static function getAllArray(array $queryParams = []): array
    {
        $sql = static::generateQuery($queryParams);
        return static::getDb()->queryAllArray($sql, []);
    }

    /**
     * Retrieves a record from a database by unique field
     * @param array $queryParams
     * @return mixed
     */
    public static function getOne(array $queryParams = [])
    {
        $sql = static::generateQuery($queryParams);
        return static::getDb()->queryOne($sql, [], static::class);
    }

    public static function getCountRows()
    {
        $sql = 'SELECT COUNT(*) count FROM `' . static::getTableName() . '`';
        return static::getDb()->queryOneAssoc($sql, [])['count'];
    }

    public function save(): bool
    {
        if ($this->id) {
            return $this->update();
        }
        return $this->insert();
    }

    /**
     * вставляет запись в базу данных
     */
    public function insert(): bool
    {
        $this->excludeQueryParams[] = 'id';
        $this->excludeQueryParams[] = 'deleted';
        $this->excludeQueryParams[] = 'create_data';
        $this->excludeQueryParams[] = 'change_data';
        $data = $this->getQueryParams();

        $sql = 'INSERT INTO `' . static::getTableName() . '` 
        (' . implode(', ', $data['fields']) . ') VALUES
        (' . implode(', ', array_keys($data['params'])) . ');';

        $result = static::getDb()->exec($sql, $data['params']);

        if (!$result) {
            throw new RuntimeException('Error insert to DB');
        }
        $this->id = static::getDb()->getInsertedId();

        return $result;
    }

    /**
     * удаляет запись из базы данных
     */
    public function delete(): bool
    {
        $this->validateId();

        $sql = 'DELETE FROM `' . static::getTableName() . '` WHERE `id`=:id;';
        return static::getDb()->exec($sql, [':id' => $this->id]);
    }

    /**
     * изменяет запись в базе данных
     */
    public function update()
    {
        $this->validateId();

        $data = $this->getQueryParams();

        $sql = 'UPDATE `' . static::getTableName() . '` SET ' . implode(', ', $data['set']) . ' WHERE `id`=:id;';
        return static::getDb()->exec($sql, $data['params']);
    }
}