<?php

namespace Core;

use InvalidArgumentException;
use PDO;
use PDOException;

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
        if (!empty($data) && is_array($data) && count(array_filter(array_keys($data), 'is_string')) === count($data)) {
            // Mendapatkan daftar kolom
            $columns = implode(', ', array_keys($data));

            // Membuat placeholder untuk nilai
            $placeholders = ':' . implode(', :', array_keys($data));

            // Membuat query SQL INSERT
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

            // Menyiapkan statement SQL dengan PDO
            $stmt = $this->pdo->prepare($sql);

            // Mengikat nilai ke placeholder dalam statement SQL
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }

            // Mengeksekusi statement SQL dan mengembalikan hasilnya
            return $stmt->execute();
        } else {
            throw new \InvalidArgumentException('Input harus berupa array asosiatif yang tidak kosong.');
        }
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
        // var_dump($value);
        // die();
        try {
            $sql = "SELECT * FROM {$this->table} WHERE $field $operator :value";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':value', $value);
            $stmt->execute();

            $this->results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this;
        } catch (PDOException $e) {
            // Handle your PDO exception, log or throw as necessary
            echo "Error executing query: " . $e->getMessage();
            return null; // Or handle the error in an appropriate manner
        }
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
    public function getColumnValueById($id, array $column, $field = null, $oprator = '=')
    {
        if (empty($columns)) {
            throw new InvalidArgumentException('Daftar kolom tidak boleh kosong.');
        }
        $field = $field ?? $this->primaryKey;
        // Membuat daftar kolom untuk SELECT
        $columnsList = implode(', ', $columns);

        // Membuat query SQL dengan placeholder untuk nilai id
        $sql = "SELECT $columnsList FROM {$this->table} WHERE $field = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        foreach ($columns as $column) {
            if (isset($result[$column])) {
                return $result[$column];
            }
        }

        return null;
    }
}
