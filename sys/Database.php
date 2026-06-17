<?php

namespace Sys;

use PDO;
use PDOException;
use PDOStatement;
use Sys\Orm\ConnectionInterface;

class Database implements ConnectionInterface
{
    public $host;
    public $port;
    public $dbname;
    public $user;
    public $password;
    public $pdo;

    public function __construct()
    {
        $envPath = realpath(dirname(__FILE__, 2) . '/.env');
        $env = $envPath ? parse_ini_file($envPath) : [];

        $this->host = $this->getConfigValue('DB_HOST', $env, 'localhost');
        $this->dbname = $this->getConfigValue('DB_DATABASE', $env, '');
        $this->user = $this->getConfigValue('DB_USERNAME', $env, 'root');
        $this->password = $this->getConfigValue('DB_PASSWORD', $env, '');
        $this->port = $this->getConfigValue('DB_PORT', $env, '3306');
    }

    private function getConfigValue(string $key, array $env, string $default = ''): string
    {
        $value = getenv($key);

        if ($value !== false && $value !== '') {
            return $value;
        }

        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }

        if (isset($env[$key]) && $env[$key] !== '') {
            return trim((string) $env[$key]);
        }

        return $default;
    }

    public function connect()
    {
        // var_dump($this->pdo);die();
        if (!$this->pdo) {
            try {
                $dsn = "mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8mb4";
                $this->pdo = new PDO($dsn, $this->user, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log('Erro ao conectar com o banco de dados: ' . $e->getMessage());
                throw $e;
            }
        }

        return $this->pdo;
    }

    public function prepareAndExecute(string $query, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->connect()->prepare($query);

            foreach ($params as $paramName => $paramValue) {
                $stmt->bindValue($paramName, $paramValue);
            }

            $stmt->execute();
            
            return $stmt;
        } catch (PDOException $e) {
            error_log('Erro na consulta: ' . $e->getMessage());
            throw $e;
        }
    }

    public function query($query)
    {
        try {
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt;
        } catch (PDOException $e) {
            error_log('Erro na consulta: ' . $e->getMessage());
            throw $e;
        }

    }

    public function execute($query, $params = [])
    {
        $stmt = $this->connect()->prepare($query);
        return $stmt->execute($params);
    }

    public function lastInsertId(): string
    {
        return $this->connect()->lastInsertId();
    }

    public function saveQuery($query)
    {
        $query = substr($query, 0, 500); // limita a consulta a 500 caracteres
        $date = date('Y-m-d H:i:s');

        $sql = "INSERT INTO query_history (query_date, query_sql) VALUES (:date, :query)";
        $params = array(':date' => $date, ':query' => $query);        

        $this->execute($sql, $params);
    }

    public function executeSaveQuery($query)
    {
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    

}
