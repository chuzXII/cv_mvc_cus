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
    private $pdo;
    private static $instance = null;
    public function __construct()
    {
        $this->host = getenv('DB_HOST');
        $this->db_name = getenv('DB_NAME');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASS');

        // Debugging: tampilkan nilai environment variables
        // var_dump($this->host, $this->db_name, $this->username, $this->password);

        try {
            $this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }
    }
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function query($sql)
    {
        return $this->pdo->query($sql);
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
