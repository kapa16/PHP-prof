<?php

namespace App\Engine;

use PDO;
use PDOStatement;
use RuntimeException;

/**
 * Class Db
 * Подключение и выполнение запросов к БД
 * @package App
 */
class Db
{
    private $link;
    /** @var PDOStatement */
    private $sth;
    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    private function prepareDsnString(): string
    {
        return sprintf('%s:dbname=%s;host=%s;charset=%s',
            $this->config['driver'],
            $this->config['database'],
            $this->config['host'],
            $this->config['charset']
        );
    }

    private function getConnection(): PDO
    {
        if (empty($this->link)) {
            $this->link = new PDO($this->prepareDsnString(),
                $this->config['login'],
                $this->config['password'],
                [PDO::FETCH_ASSOC]);
        }
        return $this->link;
    }

    public function exec(string $sql, array $params): bool
    {
        $this->sth = $this->getConnection()->prepare($sql);
        return $this->queryExecute($params);
    }

    private function queryExecute($params): bool
    {
        $this->sth->execute($params);
        if ($this->sth->errorCode() !== '00000') {
            throw new RuntimeException($this->sth->errorInfo()[2]);
        }
        return true;
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
        $this->sth = $this->getConnection()->prepare($sql);
        $this->sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
        return $this->queryExecute($params);
    }

    public function queryArray(string $sql, array $params): bool
    {
        $this->sth = $this->getConnection()->prepare($sql);
        $this->sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this->queryExecute($params);
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

    public function queryAllArray(string $sql, array $params): array
    {
        $this->queryArray($sql, $params);
        return $this->sth->fetchAll();
    }

    /**
     * Возвращает один обект из запроса
     * @param string $sql - текст запроса
     * @param array $params - параметры для подстановки в запрос
     * @param string $class - имя класса дла создания экземлпяров по полученным данным
     * @return object - объект переданного класса
     */
    public function queryOne(string $sql, array $params, string $class)
    {
        $this->queryClass($sql, $params, $class);
        return $this->sth->fetch();
    }

    public function queryOneAssoc(string $sql, array $params)
    {
        $this->sth = $this->getConnection()->prepare($sql);
        $this->sth->execute($params);
        return $this->sth->fetch();
    }

    /**
     * Возвращает id последних вставленных данных
     * @return string - значение ID
     */
    public function getInsertedId(): string
    {
        return $this->getConnection()->lastInsertId();
    }
}
