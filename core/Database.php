<?php
namespace Core;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->db_name = getenv('DB_NAME');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASS');

        // Debugging: tampilkan nilai environment variables
        // var_dump($this->host, $this->db_name, $this->username, $this->password);

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
    }

    public function prepare($sql)
    {
        return $this->conn->prepare($sql);
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
