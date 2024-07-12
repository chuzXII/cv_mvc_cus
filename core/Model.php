<?php

namespace Core;

use PDO;

class Model
{
    protected $pdo;
    protected $table;
    protected $primaryKey; // Default primary key
    protected $results = []; // To store query results

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function create(array $data)
    {
        $keys = array_keys($data);
        $columns = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    public function update($id, array $data)
    {
        $setString = '';
        foreach ($data as $key => $value) {
            $setString .= "$key = :$key, ";
        }
        $setString = rtrim($setString, ', ');

        $sql = "UPDATE {$this->table} SET $setString WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function where($field, $value, $operator = '=')
    {
        $sql = "SELECT * FROM {$this->table} WHERE $field $operator :value";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();

        $this->results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Store the results in $this->results
        return $this;
    }

    public function first()
    {
        return $this->results ? $this->results[0] : null;
    }

    public function get()
    {
        return $this->results;
    }

    public function setPrimaryKey($key)
    {
        $this->primaryKey = $key;
    }
}
