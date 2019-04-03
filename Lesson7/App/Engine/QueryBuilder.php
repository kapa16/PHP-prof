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
     * @param $queryParams
     */
    public function __construct(array $queryParams = [])
    {
        $this->selectFields = $queryParams['selectFields'] ?? [];
        $this->filters = $queryParams['filters'] ?? [];
        $this->filterLogicalOperator = $queryParams['filterLogicalOperator'] ?? 'AND';
        $this->limitFrom = $queryParams['limitFrom'] ?? 0;
        $this->limitCount = $queryParams['limitCount'] ?? 0;
        $this->sortFields = $queryParams['sortFields'] ?? [];
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
        $sql = 'SELECT ';

        $sql .= $this->getSelectFieldsString();

        $sql .= ' FROM `' . $tableName . '`';

        $sql .= $this->getFilterString();

        $sql .= $this->getOrderString();

        $sql .= $this->getLimitString();

        return $sql;
    }


}