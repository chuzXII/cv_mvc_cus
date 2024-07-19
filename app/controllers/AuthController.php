<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Database;
use App\Models\user;
use Core\Request;
use PDOException;
use PDO;

class AuthController extends Controller
{
    public function Ilogin()
    {
        $this->view('login', ['title' => 'Login Page']);
    }
    public function iregis()
    {
        $this->view('registrasi', ['title' => 'Registrasi Page']);
        // View::render('registrasi', ['title' => 'Registrasi Page']);

    }

    public function auth(Request $req)
    {
        // Validasi input
        $username = htmlspecialchars($req->input('username'));

        $password = htmlspecialchars($req->input('password'));
        $messages = [
            'password.required' => 'Password is required.',
        ];
        $validated = $req->validate([
            'username' => 'required',
            'password' => 'required',
        ], $messages);

        // Periksa apakah validasi gagal
        
        if (!$validated->passes()) {
            $errors = $validated->errors();
            $this->withErrors($errors);
            $this->redirect('/login');
            return;
        }
        // Ambil user dari database (misalnya, menggunakan PDO)
        // $pdo = new PDO('mysql:host=localhost;dbname=cv2', 'root', '');
        // $stmt = $pdo->prepare('SELECT * FROM user WHERE username = ?');
        // $stmt->execute([$username]);
        // $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $userModel = new User();
        $user = $userModel->where('username', $username)->first();

        // Verifikasi password
        if ($user && password_verify($password, $user['password'])) {
            // Setel variabel sesi
            $_SESSION['user'] = $user['username'];
            $_SESSION['iduser'] = $user['id_user'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // $_SESSION['last_activity'] = time();
            // $_SESSION['session_token'] = AuthMiddleware::generateSessionToken();
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            // $host = $_SERVER['HTTP_HOST'];

            // Redirect ke dashboard atau halaman lain
            // $this->redirect('/dashboard');

            // $this->redirect('dashboard');
            // header('Location: '.BASE_URL.'/dashboard');
            $this->redirect('/dashboard');

            exit;
        } else {
            // Jika login gagal, kembalikan ke halaman login dengan pesan error
            $_SESSION['error'] = 'Username atau password salah.';
            $this->redirect('/login');

            // header('Location: '.BASE_URL.'/login');
            exit();
        }
    }

    public function logout()
    {
        // Hapus semua data sesi
        session_unset();
        session_destroy();

        // Redirect ke halaman utama atau halaman login
        $this->redirect('/');

        exit();
    }
    public function register(Request $req)
    {
        $username = htmlspecialchars(trim($req->input('username')));
        $email = htmlspecialchars(trim($req->input('email')));
        $password = htmlspecialchars(trim($req->input('password')));
        // $confirmPassword = htmlspecialchars(trim($req->input('password_confirmation')));
        $validated = $req->validate([
            'username'=>'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        if (!$validated->passes()) {
            $errors = $validated->errors();

            $this->redirect('/regis');
            return;
        }
        // if ($password !== $confirmPassword) {
        //     $_SESSION['error'] = 'Password dan konfirmasi password tidak cocok.';
        //     header('Location: '.BASE_URL.'/regis');

        //     exit();
        // }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // $pdo = new PDO('mysql:host=localhost;dbname=cv2', 'root', '');
        // $stmt = $pdo->prepare('INSERT INTO user (username, email, password) VALUES (?,?,?)');
        $data = ['username' => $username, 'email' => $email, 'password' => $hashedPassword];
        $userModel = new User();
        $reguser = $userModel->create($data);
        if ($reguser) {
            // $_SESSION['user'] = $username;
            // $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->redirect('/login');

            // header('Location: '.BASE_URL.'/login');

            exit();
        } else {
            $_SESSION['error'] = 'Gagal mendaftarkan pengguna baru.';
            $this->redirect('/regis');
            // header('Location: '.BASE_URL.'/regis');
            exit();
        }
    }
}
