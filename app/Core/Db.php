<?php

namespace App\Core;

class Db extends \PDO
{
    private static ?self $instance = null;

    /**
     * DB constant for informations to connect DB
     */
    private const DB_HOST = 'phpmvcesgi2024-db-1';
    private const DB_USER = 'root';
    private const DB_PASS = 'root';
    private const DB_NAME = 'demo_mvc';

    public function __construct()
    {
        // DSN 
        $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . ';charset=utf8mb4';

        try {
            parent::__construct($dsn, self::DB_USER, self::DB_PASS);

            $this->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8mb4');
            $this->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
            $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}