<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Database;
use PDOException;
use PDO;

class AuthController extends Controller
{
    private $conn;

    public function __construct()
    {
        $this->conn = new Database();
    }
    public function Ilogin()
    {
        View::render('login', ['title' => 'Login Page']);
    }
    public function iregis()
    {
        View::render('registrasi', ['title' => 'Registrasi Page']);
       
    }

    public function auth()
    {
        // Validasi input
        $username = htmlspecialchars(trim($_POST['username']));
        $password = htmlspecialchars(trim($_POST['password']));

        // Ambil user dari database (misalnya, menggunakan PDO)
        $pdo = new PDO('mysql:host=localhost;dbname=cv2', 'root', '');
        $stmt = $pdo->prepare('SELECT * FROM user WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password
        if ($user && password_verify($password, $user['password'])) {
            // Setel variabel sesi
            $_SESSION['user'] = $user['username'];
            $_SESSION['iduser'] = $user['id_user'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // $_SESSION['last_activity'] = time();
            // $_SESSION['session_token'] = AuthMiddleware::generateSessionToken();
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $host = $_SERVER['HTTP_HOST'];

            // Redirect ke dashboard atau halaman lain
            // $this->redirect('/dashboard');

            // $this->redirect('dashboard');

        } else {
            // Jika login gagal, kembalikan ke halaman login dengan pesan error
            $_SESSION['error'] = 'Username atau password salah.';
            header('Location: /login');
            exit();
        }
    }

    public function logout()
    {
        // Hapus semua data sesi
        session_unset();
        session_destroy();

        // Redirect ke halaman utama atau halaman login
        header('Location: /');
        exit();
    }
    public function register()
    {
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));
        $confirmPassword = htmlspecialchars(trim($_POST['cpassword']));

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Password dan konfirmasi password tidak cocok.';
            header('Location: /regis');
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $pdo = new PDO('mysql:host=localhost;dbname=cv2', 'root', '');
        $stmt = $pdo->prepare('INSERT INTO user (username, email, password) VALUES (?,?,?)');
        if ($stmt->execute([$username, $email, $hashedPassword])) {
            // $_SESSION['user'] = $username;
            // $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: /login');
            exit();
        } else {
            $_SESSION['error'] = 'Gagal mendaftarkan pengguna baru.';
            header('Location: /regis');
            exit();
        }
    }
    public function redirect($route)
    {
        // Tentukan protokol berdasarkan kondisi HTTPS
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

        // Dapatkan hostname dan port dari permintaan
        $full_host = $_SERVER['HTTP_HOST'];

        // Pisahkan hostname dan port jika ada
        $url_parts = explode(':', $full_host);
        $hostname = $url_parts[0];
        $port = isset($url_parts[1]) ? ':' . $url_parts[1] : '';

        if (isset($url_parts[1])) {
            $redirect_url = "$protocol$full_host$route";
        } else {
            $redirect_url = "$protocol$hostname/cvv$route";
        }

        header("Location: $redirect_url");
        exit;
    }
}
