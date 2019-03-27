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
        $this->link = new \PDO($dsn, DB_USER, DB_PASSWORD, [\PDO::FETCH_ASSOC]);
    }

    public function exec(string $sql, array $params): bool
    {
        $this->sth = $this->link->prepare($sql);
        return $this->sth->execute($params);
    }

    /**
     * Подготавливает и вполняет запрос
     * @param $sql - текст запроса
     * @param $params - параметры для подстановки в запрос
     * @param $class - имя класса дла создания экземлпяров по полученным данным
     * @return bool - выполнен запрос или нет
     */
    public function queryClass(string $sql, array $params, string $class): bool
    {
        $this->sth = $this->link->prepare($sql);
        $this->sth->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class);
        return $this->sth->execute($params);
    }

    /**
     * Возвращает все данные из запроса
     * @param string $sql - текст запроса
     * @param array $params - параметры для подстановки в запрос
     * @param $class - имя класса дла создания экземлпяров по полученным данным
     * @return array - массив объектов переданного класса
     */
    public function queryAll(string $sql, array $params, string $class): array
    {
        $this->queryClass($sql, $params, $class);
        return $this->sth->fetchAll();
    }

    /**
     * Возвращает один обект из запроса
     * @param string $sql - текст запроса
     * @param array $params - параметры для подстановки в запрос
     * @param string $class- имя класса дла создания экземлпяров по полученным данным
     * @return object - объект переданного класса
     */
    public function queryOne(string $sql, array $params, string $class)
    {
        $this->queryClass($sql, $params, $class);
        return $this->sth->fetch();
    }

    public function queryOneAssoc(string $sql, array $params)
    {
        $this->sth = $this->link->prepare($sql);
        $this->sth->execute($params);
        return $this->sth->fetch();
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
