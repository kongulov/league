<?php

namespace Infrastructure;

class Database
{
    private $pdo;

    public function __construct()
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../', '.env');
        $dotenv->load();

        if ($_SERVER['PHP_SELF'] === './vendor/bin/phpunit')
            $_ENV['DB_HOST'] = '127.0.0.1';

        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_DATABASE'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];
        $charset = 'utf8mb4';
        $dsn = 'mysql:host='.$host.';dbname='.$db.';charset='.$charset;

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
