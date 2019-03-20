<?php

namespace App\Engine;

use App\Traits\SingletonTrait;

/**
 * Class Db
 * Подключение и выполнение запросов к БД
 * @package App
 */
class Db
{
    private $link;
    private $sth;

    use SingletonTrait;

    private function __construct()
    {
        $dsn = DB_DRIVER . ':dbname=' . DB_NAME . ';host=' . DB_HOST;
        $this->link = new \PDO($dsn, DB_USER, DB_PASSWORD);
    }

    /**
     * Выполняет запрос на получение данных и создает массив объектов по полученным данным
     * имена свойств классов должны соответсвтовать именам полей, получаемых из БД
     * @param $sql - текст запроса
     * @param $params - параметры для подстановки в запрос
     * @param $class - имя класса дла создания экземлпяров по полученным данным
     * @return array - массив объектов
     */
    public function query(string $sql, array $params): bool
    {
        $this->sth = $this->link->prepare($sql);
        return $this->sth->execute($params);
    }

    public function fetchAllClass(string $sql, array $params, string $class): array
    {
        $this->query($sql, $params);
        return $this->sth->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class);
    }

     /**
     * Возвращает id последних вставленных данных
     * @return string - значение ID
     */
    public function getInsertedId():string
    {
        return $this->link->lastInsertId();
    }
}
