<?php

declare(strict_types=1);

/**
 * Wrapper for PDO
 */

namespace App\Shared\Database;

use PDO;
use PDOStatement;

final class Database
{
    private ?PDO $db;
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
        if (!in_array(PDO::ERRMODE_EXCEPTION, $this->options)) {
            $this->options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        }
    }

    public function beginTransaction() : void
    {
        $this->connect();
        $this->db->beginTransaction();
    }

    public function connect() : void
    {
        if (!isset($this->db)) {
            $this->db = new PDO($this->dsn, $this->username, $this->password, $this->options);
        }
    }

    public function commit() : void
    {
        $this->db->commit();
    }

    public function rollback() : void
    {
        $this->db->rollback();
    }

    public function disconnect() : void
    {
        unset($this->db);
    }

    public function pdo() : PDO
    {
        return $this->db;
    }

    /**
     * Escaping string in terms to prevent SQL injection
     * @param string $value
     * @return string
     */
    public function escapeString(string $value) : string
    {
        return $this->db->quote($value);
    }

    /**
     * @param string $query
     * @param array $binds
     * @return void
     */
    private function run(string $query, array $binds = []) : void
    {
        if (empty($binds)) {
            $this->stmt = $this->db->query($query);
        }

        $this->stmt = $this->db->prepare($query);
        $this->bindValues($binds);
        $this->stmt->execute();
    }

    /**
     * Returns associative array for selected rows and conditions
     * @param string $query
     * @param array $binds
     * @return array
     */
    public function rows(string $query, array $binds = []) : array
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
    public function row(string $query, array $binds = [],) : array|false
    {
        $this->run($query, $binds);
        $result = $this->stmt->fetch($this->fetchMode);
        if (empty($result)) {
            return false;
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
        return $this->db->lastInsertId();
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
        elseif (is_int($value)) {
            $this->stmt->bindValue($key, $value, PDO::PARAM_INT);
        }
        elseif (is_bool($value)) {
            $this->stmt->bindValue($key, $value, PDO::PARAM_BOOL);
        }
        else {
            $this->stmt->bindValue($key, $value);
        }
    }

    public function nextId(string $table) : ?int
    {
        $query = "SHOW TABLE STATUS WHERE Name = :table;";
        return $this->row($query, ['table' => $table])['Auto_increment'];
    }
}
