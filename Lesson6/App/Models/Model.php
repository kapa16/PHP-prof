<?php

namespace App\Models;

use App\Engine\Db;
use RuntimeException;

/**
 * Class Model реализация CRUD взаимодействия с БД
 * @package App
 */
abstract class Model
{
    public static $selectFields = [];
    public static $filters = [];
    public static $filterLogicalOperator = 'AND';
    public static $limitFrom = 0;
    public static $limitCount = 0;
    public static $sortFields = [];
    public static $reversSort = false;

    public $id;
    protected $excludeQueryParams;

    abstract protected static function getTableName();

    protected static function getDb()
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

    protected static function getSelectFieldsString(): string
    {
        $select = static::$selectFields;
        if (empty($select)) {
            $select = ['*'];
        }

        //Добавим запрос только необходимые столбцы
        $queries = [];
        foreach ($select as $key => $value) {
            if (is_int($key)) {
                $queries[] = $value;
            } else {
                //Если запрос выглядит как [col => alias] то создаем запрос `col as alias`
                $queries[] = "$key as '$value'";
            }
        }
        return implode(', ', $queries);
    }

    protected static function getFilterString(): string
    {
        $filters = static::$filters;
        $filterLogicalOperator = static::$filterLogicalOperator;

        if (empty($filters)) {
            return '';
        }
        $queries = [];
        //Проходимся по всем фильтрам
        foreach ($filters as $filter) {
            //Перебираем оператор
            switch ($filter['oper']) {
                case 'IS NULL':
                case 'IS NOT NULL':
                    //Для работы с NULL $value не нужно
                    $queries[] = "{$filter['col']} {$filter['oper']}";
                    break;
                case 'IN':
                case 'NOT IN':
                    //Для работы с IN $value должно иметь вид (1,2,3)
                    $value = $filter['value'];
                    if (is_array($value)) {
                        $value = '(' . implode(', ', $value) . ')';
                    }
                    $queries[] = "{$filter['col']} {$filter['oper']} {$value}";
                    break;
                default:
                    //В остальных случаях без обработки
                    $queries[] = "{$filter['col']} {$filter['oper']} '{$filter['value']}'";
                    break;
            };
        }
        //Добавляем выборку в запрос
        return ' WHERE ' . implode(' ' . $filterLogicalOperator . ' ', $queries);
    }

    protected static function getOrderString(): string
    {
        $sortFields = static::$sortFields;
        if (empty($sortFields)) {
            return '';
        }
        $strSortFields = implode(', ', $sortFields);
        return " ORDER BY {$strSortFields}";
    }

    protected static function getLimitString(): string
    {
        $limitFrom = static::$limitFrom;
        $limitCount = static::$limitCount;
        if ($limitFrom || $limitCount) {
            return " LIMIT {$limitFrom}, {$limitCount}";
        }
        return '';
    }

    protected static function generateSelectQuery(): string
    {
        $sql = 'SELECT ';

        $sql .= static::getSelectFieldsString();

        $sql .= ' FROM `' . static::getTableName() . '`';

        $sql .= static::getFilterString();

        $sql .= static::getOrderString();

        $sql .= static::getLimitString();

        return $sql;
    }

    /**
     * Получает все записи из базы данных
     * @return array
     */
    public static function getAll(): array
    {
        $sql = static::generateSelectQuery();
        return static::getDb()->queryAll($sql, [], static::class);
    }

    public static function getAllArray(): array
    {
        $sql = static::generateSelectQuery();
        return static::getDb()->queryAllArray($sql, []);
    }

    /**
     * Получает лимитированное количестов записей из базы данных
     * @param array|null $sortFields
     * @param $limitFrom
     * @param $limitCount
     * @return array
     */
    public static function getLimit(
        ?array $sortFields = [],
        $limitFrom = null,
        $limitCount = null): array
    {
        $sql = static::generateSelectQuery([], [], null, $sortFields, $limitFrom, $limitCount);
        // TODO Почему не работает подстановка
//        $params = [':from' => $limitFrom, ':count' => $limitCount];
        $params = [];
        return static::getDb()->queryAll($sql, $params, static::class);
    }

    /**
     * Retrieves a record from a database by unique field
     * @param $fieldName - field with unique index
     * @param $fieldValue
     * @return mixed
     */
    public static function getOne($fieldName, $fieldValue)
    {
        $sql = 'SELECT * FROM `' . static::getTableName() . "` WHERE `$fieldName`=:$fieldName;";
        return static::getDb()->queryOne($sql, [":$fieldName" => $fieldValue], static::class);
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