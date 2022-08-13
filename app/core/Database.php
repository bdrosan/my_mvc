<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    // CONNECT TO DATABASE
    public $error = "";
    private $pdo = null;
    private $stmt = null;

    public $select = '*';
    public $where;
    public $from;

    function __construct($dbConfig)
    {
        try {
            $this->pdo = new PDO(
                "mysql:host=" . $dbConfig['host'] . ";dbname=" . $dbConfig['database'] . ";charset=" . $dbConfig['charset'],
                $dbConfig['username'],
                $dbConfig['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    // CLOSE CONNECTION
    function __destruct()
    {
        if ($this->stmt !== null) {
            $this->stmt = null;
        }
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
    }

    public function select($columns = ['*'])
    {
        if (is_array($columns)) {
            $columns = implode(',', $columns);
        }
        $this->select = $columns;
        return $this;
    }

    public function from($table)
    {
        $this->from = $table;
        return $this;
    }

    public function where($column, $operator = null, $value = null)
    {
        //if only 2 parameter passed, we assume one is key another is value
        if (!is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = null;
        }

        if (is_null($operator)) {
            $operator = '=';
        }

        $this->where = " WHERE $column $operator $value";

        return $this;
    }

    public function get()
    {
        $result = false;
        try {
            $sql = "SELECT $this->select FROM $this->from $this->where";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute();
            $result = $this->stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    // RUN A SELECT QUERY
    /* function select($fields, $table)
    {
        $result = false;
        try {
            $sql = "SELECT $fields FROM $table";
            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute();
            $result = $this->stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    function selectWhere($fields, $table, $cond)
    {
        $result = false;
        try {
            echo implode(",", array_keys($cond));
            $sql = "SELECT $fields FROM $table";

            $this->stmt = $this->pdo->prepare($sql);
            $this->stmt->execute();
            $result = $this->stmt->fetchAll();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    } */
}
