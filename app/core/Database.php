<?php
namespace App\Core;
use PDO;
use PDOException;
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Mengambil nilai host, nama database, username, dan password dari environment variables
        $this->host = getenv('DB_HOST');
        $this->db_name = getenv('DB_NAME');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASS');

        try {
            // Membuat koneksi PDO ke database menggunakan nilai yang diambil dari environment variables
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            // Mengatur mode error untuk koneksi PDO agar mengembalikan exception jika terjadi error
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            // Menangkap exception jika koneksi gagal dan menampilkan pesan error
            echo 'Connection Error: ' . $e->getMessage();
        }
    }
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }
public function query($sql) {
        return $this->conn->query($sql);
    }
    // Metode untuk mendapatkan objek koneksi PDO
    public function getConnection() {
        return $this->conn;
    }
}
?>
