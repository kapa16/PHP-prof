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
    public static $sortFields = [];
    public static $reversSort = false;

    public $id;
    protected $excludeQueryParams;

    abstract protected static function getTableName();

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

    protected static function generateSelectQuery(
        ?array $select = [],
        ?array $filters = [],
        string $filterLogicalOperator = 'AND',
        ?array $sortFields = [],
        int $limitFrom = null,
        int $limitCount = null): string
    {
        $sql = 'SELECT ';

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
        $sql .= implode(', ', $queries);

        $sql .= ' FROM `' . static::getTableName() . '`';

        //Выборка (WHERE)
        if (!empty($filters)) {
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
            $sql .= ' WHERE ' . implode(' ' . $filterLogicalOperator . ' ', $queries);
        }


        if (count($sortFields) > 0) {
            $strSortFields = implode(', ', $sortFields);
            $sql .= " ORDER BY {$strSortFields}";
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
     * @param array|null $select
     * @param array|null $filters
     * @param string $filterLogicalOperator
     * @param array|null $sortFields
     * @return array
     */
    public static function getAll(
        ?array $select = [],
        ?array $filters = [],
        string $filterLogicalOperator = ' AND ',
        ?array $sortFields = []
    ): array
    {
        $db = Db::getInstance();
        $sql = static::generateSelectQuery($select, $filters, $filterLogicalOperator, $sortFields);
        return $db->queryAll($sql, [], static::class);
    }

    public static function getAllArray(
        ?array $select = [],
        ?array $filters = [],
        string $filterLogicalOperator = ' AND ',
        ?array $sortFields = []
    ): array
    {
        $db = Db::getInstance();
        $sql = static::generateSelectQuery($select, $filters, $filterLogicalOperator, $sortFields);
        return $db->queryAllArray($sql, []);
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
        /** @var Db $db */
        $db = Db::getInstance();
        $sql = static::generateSelectQuery([], [], null, $sortFields, $limitFrom, $limitCount);
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
        /** @var Db $db */
        $db = Db::getInstance();
        $sql = 'SELECT * FROM `' . static::getTableName() . "` WHERE `$fieldName`=:$fieldName;";
        return $db->queryOne($sql, [":$fieldName" => $fieldValue], static::class);
    }

    public static function getCountRows()
    {
        /** @var Db $db */
        $db = Db::getInstance();
        $sql = 'SELECT COUNT(*) count FROM `' . static::getTableName() . '`';
        return $db->queryOneAssoc($sql, [])['count'];
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

        /** @var Db $db */
        $db = Db::getInstance();
        $sql = 'INSERT INTO `' . static::getTableName() . '` 
        (' . implode(', ', $data['fields']) . ') VALUES
        (' . implode(', ', array_keys($data['params'])) . ');';

        $result = $db->exec($sql, $data['params']);

        if (!$result) {
            throw new RuntimeException('Error insert to DB');
        }
        $this->id = $db->getInsertedId();

        return $result;
    }

    /**
     * удаляет запись из базы данных
     */
    public function delete(): bool
    {
        $this->validateId();

        /** @var Db $db */
        $db = Db::getInstance();
        $sql = 'DELETE FROM `' . static::getTableName() . '` WHERE `id`=:id;';
        return $db->exec($sql, [':id' => $this->id]);
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