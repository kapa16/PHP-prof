<?php


namespace App\Engine;


class QueryBuilder
{
    /** @var array|string $selectFields - Select fields for query ['id' => 'index'] */
    protected $selectFields = [];
    /** @var array of arrays $filters - Filter for query ['col'   => 'id', 'oper'  => '=', 'value' => 1] */
    protected $filters = [];
    /** @var string $filterLogicalOperator - logical operator between filters */
    protected $filterLogicalOperator = 'AND';
    /** @var int $limitFrom - LIMIT from */
    protected $limitFrom = 0;
    /** @var int $limitCount - LIMIT count */
    protected $limitCount = 0;
    /** @var array $sortFields - fields for sort query ['col' => 'id', 'direction' => 'asc'] */
    protected $sortFields = [];

    /**
     * QueryBuilder constructor.
     * @param array|string $selectFields
     * @param array $filters
     * @param string $filterLogicalOperator
     * @param int $limitFrom
     * @param int $limitCount
     * @param array $sortFields
     */
    public function setQueryParams(
        ?array $selectFields,
        ?array $filters,
        ?string $filterLogicalOperator,
        ?array $sortFields,
        ?int $limitFrom,
        ?int $limitCount): void
    {
        $this->selectFields = $selectFields;
        $this->filters = $filters;
        $this->filterLogicalOperator = $filterLogicalOperator;
        $this->limitFrom = $limitFrom;
        $this->limitCount = $limitCount;
        $this->sortFields = $sortFields;
    }

    protected function getSelectFieldsString(): string
    {
        $select = $this->selectFields;
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

    protected function getFilterString(): string
    {
        $filters = $this->filters;
        $filterLogicalOperator = $this->filterLogicalOperator;
        if (empty($filters)) {
            return '';
        }
        $queries = [];
        foreach ($filters as $filter) {
            switch ($filter['oper']) {
                case 'IS NULL':
                case 'IS NOT NULL':
                    $queries[] = "{$filter['col']} {$filter['oper']}";
                    break;
                case 'IN':
                case 'NOT IN':
                    $value = $filter['value'];
                    if (is_array($value)) {
                        $value = '(' . implode(', ', $value) . ')';
                    }
                    $queries[] = "{$filter['col']} {$filter['oper']} {$value}";
                    break;
                default:
                    $queries[] = "{$filter['col']} {$filter['oper']} '{$filter['value']}'";
                    break;
            }
        }
        return ' WHERE ' . implode(' ' . $filterLogicalOperator . ' ', $queries);
    }

    protected function getOrderString(): string
    {
        $sortFields = $this->sortFields;

        if (empty($sortFields)) {
            return '';
        }
        $queries = [];
        foreach ($sortFields as $order) {
            $direction = strtolower($order['direction']) === 'asc' ? 'asc' : 'desc';
            $queries[] = "{$order['col']} $direction";
        }
        return ' ORDER BY ' . implode(', ', $queries);
    }

    protected function getLimitString(): string
    {
        $limitFrom = $this->limitFrom;
        $limitCount = $this->limitCount;
        if ($limitFrom || $limitCount) {
            return " LIMIT {$limitFrom}, {$limitCount}";
        }
        return '';
    }

    public function generateQuery($tableName): string
    {
        $sql = 'SELECT '
            . $this->getSelectFieldsString()
            . ' FROM `' . $tableName . '`'
            . $this->getFilterString()
            . $this->getOrderString()
            . $this->getLimitString();

        return $sql;
    }


}