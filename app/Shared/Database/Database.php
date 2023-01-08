<?php

declare(strict_types=1);

/**
 * Wrapper for PDO
 */

namespace App\Shared\Database;

use PDO;
use PDOStatement;
use RuntimeException;

final class Database
{
    private ?PDO $pdo;
    private ?PDOStatement $stmt;
    private readonly string $dsn;
    private int $fetchMode = PDO::FETCH_ASSOC;

    public function __construct(
        private readonly string $database,
        private readonly string $host,
        private readonly string $username,
        private readonly string $password,
        private readonly string $charset = 'utf8',
        private readonly int $port = 3306,
        private array $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,]
    ) {
        $this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->database . ';port=' . $this->port . ';charset=' . $this->charset;
        if (!in_array(PDO::ERRMODE_EXCEPTION, $this->options, true)) {
            $this->options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        }
    }

    public function beginTransaction() : void
    {
        $this->connect();
        $this->pdo->beginTransaction();
    }

    public function connect() : void
    {
        if (!isset($this->pdo)) {
            $this->pdo = new PDO($this->dsn, $this->username, $this->password, $this->options);
        }
    }

    public function commit() : void
    {
        $this->pdo->commit();
    }

    public function rollback() : void
    {
        $this->pdo->rollback();
    }

    public function disconnect() : void
    {
        unset($this->pdo);
    }

    public function pdo() : PDO
    {
        return $this->pdo;
    }

    /**
     * Escaping string in terms to prevent SQL injection
     * @param string $value
     * @return string
     */
    public function escapeString(string $value) : string
    {
        return $this->pdo->quote($value);
    }

    /**
     * @param string $query
     * @param array $binds
     * @return void
     */
    private function run(string $query, array $binds = []) : void
    {
        if (empty($binds)) {
            $this->stmt = $this->pdo->query($query);
        }
        else {
            $this->stmt = $this->pdo->prepare($query);
            $this->bindValues($binds);
        }

        $this->stmt->execute();
    }

    /**
     * Returns associative array for selected rows and conditions
     * @param string $query
     * @param array $binds
     * @return array
     */
    public function find(string $query, array $binds = []) : array
    {
        $this->run($query, $binds);
        return $this->stmt->fetchAll($this->fetchMode);
    }

    /**
     * Returns one row associative array for selected rows and conditions
     *
     * If no results were found returns false
     * @param string $query
     * @param array $binds
     * @return array|false
     */
    public function findOne(string $query, array $binds = []) : array|false
    {
        $this->run($query, $binds);
        $result = $this->stmt->fetch($this->fetchMode);
        if (empty($result)) {
            return false;
        }
        if (count($result) !== 0) {
            throw new RuntimeException('Two or more rows were found when asked for one!');
        }
        return $result;
    }

    /**
     * Returns one value for selected rows and conditions
     *
     * "SELECT COUNT(*) FROM 'table' WHERE 1"
     * @param string $query
     * @param array $binds
     * @return array
     */
    public function oneValue(string $query, array $binds) : mixed
    {
        $this->run($query, $binds);

        $result = $this->stmt->fetch($this->fetchMode);
        return reset($result);
    }

    /**
     * Execute query and returns affected rows
     *
     * Used for INSERT, UPDATE, DELETE
     * @param string $query
     * @param array $binds
     * @return int
     */
    public function execute(string $query, array $binds) : int
    {
        $this->run($query, $binds);
        return $this->stmt->rowCount();
    }

    public function lastInsertId() : bool|string
    {
        return $this->pdo->lastInsertId();
    }

    private function bindValues(array $binds) : void
    {
        $count = 1;
        foreach ($binds as $key => $value) {
            if (!is_numeric($key)) {
                $this->bindValue($key, $value);
            }
            else {
                $this->bindValue($count, $value);
            }
            $count++;
        }
    }

    private function bindValue(int|string $key, mixed $value) : void
    {
        if (is_int($value)) {
            $this->stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        elseif (is_null($value)) {
            $this->stmt->bindValue($key, $value, PDO::PARAM_NULL);
        }
        elseif (is_bool($value)) {
            $this->stmt->bindValue($key, $value, PDO::PARAM_BOOL);
        }
        else {
            $this->stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    }

    public function nextId(string $table) : ?int
    {
        $query = "SHOW TABLE STATUS WHERE Name = :table;";
        return $this->findOne($query, ['table' => $table])['Auto_increment'];
    }
}
