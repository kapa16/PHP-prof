<?php


namespace App\Models\Repositories;


use App\Engine\Db;
use App\Engine\QueryBuilder;
use App\Models\DataEntity;
use RuntimeException;

abstract class Repository
{
    protected $excludeQueryParams;
    /** @var QueryBuilder $queryBuilder */
    protected $queryBuilder;

    /**
     * Repository constructor.
     * @param QueryBuilder $queryBuilder
     */
    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }

    abstract protected function getTableName();

    abstract protected function getEntityClass();

    /**
     * Repository constructor.
     * @param array|null $selectFields
     * @param array|null $filters
     * @param string|null $filterLogicalOperator
     * @param array|null $sortFields
     * @param int|null $limitFrom
     * @param int|null $limitCount
     * @return Repository
     */
    public function setQueryParams(
        ?array $selectFields = [],
        ?array $filters = [],
        ?string $filterLogicalOperator = 'AND',
        ?array $sortFields = [],
        ?int $limitFrom = 0,
        ?int $limitCount = 0): Repository
    {
        $this->queryBuilder->setQueryParams(
        $selectFields,
        $filters,
        $filterLogicalOperator,
        $sortFields,
        $limitFrom,
        $limitCount);
        return $this;
    }

    protected function getDb(): Db
    {
        /** @var Db Db */
        return Db::getInstance();
    }

    protected function getQueryParams($entity): array
    {
        $vars = get_object_vars($entity);
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

    /**
     * Получает все записи из базы данных
     * @return array
     */
    public function getAll(): array
    {
        $sql = $this->queryBuilder->generateQuery($this->getTableName());
        return $this->getDb()->queryAll($sql, [], $this->getEntityClass());
    }

    public function getAllArray(): array
    {
        $sql = $this->queryBuilder->generateQuery($this->getTableName());
        return $this->getDb()->queryAllArray($sql, []);
    }

    /**
     * Retrieves a record from a database by unique field
     * @return mixed
     */
    public function getOne()
    {
        $sql = $this->queryBuilder->generateQuery($this->getTableName());
        return $this->getDb()->queryOne($sql, [], $this->getEntityClass());
    }

    public function getCountRows()
    {
        $sql = $this->queryBuilder->generateQuery($this->getTableName());
        return $this->getDb()->queryOneAssoc($sql, [])['count'];
    }

    public function save(DataEntity $entity): bool
    {
        if ($entity->id) {
            return $this->update($entity);
        }
        return $this->insert($entity);
    }

    /**
     * вставляет запись в базу данных
     * @param DataEntity $entity
     * @return bool
     */
    public function insert(DataEntity $entity): bool
    {
        $this->excludeQueryParams[] = 'id';
        $this->excludeQueryParams[] = 'deleted';
        $this->excludeQueryParams[] = 'create_data';
        $this->excludeQueryParams[] = 'change_data';
        $this->excludeQueryParams[] = 'excludeQueryParams';

        $data = $this->getQueryParams($entity);

        $sql = 'INSERT INTO `' . $this->getTableName() . '` 
        (' . implode(', ', $data['fields']) . ') VALUES
        (' . implode(', ', array_keys($data['params'])) . ');';

        $result = $this->getDb()->exec($sql, $data['params']);

        if (!$result) {
            throw new RuntimeException('Error insert to DB');
        }
        $entity->id = $this->getDb()->getInsertedId();

        return $result;
    }

    /**
     * удаляет запись из базы данных
     * @param DataEntity $entity
     * @return bool
     */
    public function delete(DataEntity $entity): bool
    {
        $sql = 'DELETE FROM `' . $this->getTableName() . '` WHERE `id`=:id;';
        return $this->getDb()->exec($sql, [':id' => $entity->id]);
    }

    /**
     * изменяет запись в базе данных
     * @param DataEntity $entity
     * @return bool
     */
    public function update(DataEntity $entity): bool
    {
        $data = $this->getQueryParams($entity);

        $sql = 'UPDATE `' . $this->getTableName() . '` SET ' . implode(', ', $data['set']) . ' WHERE `id`=:id;';
        return $this->getDb()->exec($sql, $data['params']);
    }
}