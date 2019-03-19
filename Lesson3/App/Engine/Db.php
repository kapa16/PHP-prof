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

    use SingletonTrait;

    private function __construct()
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
    public function query(string $sql, array $params, string $class): array
    {
        $sth = $this->link->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $class);
    }

    /**
     * Выполняет подготовленный запрос к БД
     * @param $sql - текст запроса
     * @param $params - параметры запроса
     * @return bool - выполнен или нет запрос
     */
    public function exec(string $sql, array $params): bool
    {
        $sth = $this->link->prepare($sql);
        return $sth->execute($params);
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
