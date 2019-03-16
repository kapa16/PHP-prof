<?php

namespace engine;

/**
 * Class Db
 * Подключение и выполнение запросов к БД
 * @package engine
 */
class Db
{
    private $link;

    public function __construct()
    {
        $dsn = 'mysql:dbname=shop;host=localhost';
        $this->link = new \PDO($dsn, 'root', '');
    }

    /**
     * Выполняет запрос на получение данных и создает массив объектов по полученным данным
     * имена свойств классов должны соответсвтовать именам полей, получаемых из БД
     * @param $sql - текст запроса
     * @param $params - параметры для подстановки в запрос
     * @param $class - имя класса дла создания экземлпяров по полученным данным
     * @return array - массив объектов
     */
    public function query($sql, $params, $class): array
    {
        $sth = $this->link->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class);
    }

    public function exec($sql, $params): bool
    {
        $sth = $this->link->prepare($sql);
        return $sth->execute($params);
    }

    public function getInsertedId():string
    {
        return $this->link->lastInsertId();
    }
}
