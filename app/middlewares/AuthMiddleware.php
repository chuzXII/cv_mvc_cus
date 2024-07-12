<?php
namespace App\Middlewares;
class AuthMiddleware {
    public static function handle() {
        // Periksa apakah sesi pengguna ada
        if (!isset($_SESSION['user'])) {
            header('Location: '.BASE_URL);
            exit();
        }

        // Tambahkan verifikasi tambahan, misalnya token CSRF
        if (!self::verifyCsrfToken()) {
            // Jika verifikasi gagal, arahkan pengguna ke halaman utama
            header('Location: '.BASE_URL);
            exit();
        }

        // Validasi token atau mekanisme otentikasi lainnya
        // if (!self::isValidUserSession()) {
        //     // Jika sesi tidak valid, arahkan pengguna ke halaman utama
        //     header('Location: /');
        //     exit();
        // }
    }

    private static function verifyCsrfToken() {
        // Implementasikan logika verifikasi CSRF
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                return false;
            }
        }
        return true;
    }

    // private static function isValidUserSession() {
    //     // Periksa apakah sesi pengguna ada
    //     if (!isset($_SESSION['user'])) {
    //         return false;
    //     }

    //     // Periksa waktu aktivitas terakhir
    //     if (time() - $_SESSION['last_activity'] > 1800) { // 30 menit
    //         session_unset();
    //         session_destroy();
    //         return false;
    //     }

    //     // Perbarui waktu aktivitas terakhir
    //     $_SESSION['last_activity'] = time();

    //     // Verifikasi token sesi
    //     if (!isset($_SESSION['session_token']) || $_SESSION['session_token'] !== self::generateSessionToken()) {
    //         session_unset();
    //         session_destroy();
    //         return false;
    //     }

    //     // Periksa IP Address dan User-Agent
    //     if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR'] || $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    //         session_unset();
    //         session_destroy();
    //         return false;
    //     }

    //     return true;
    // }

    // public static function generateSessionToken() {
    //     // Generate token yang unik untuk sesi
    //     return hash('sha256', $_SESSION['user'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
    // }
}

