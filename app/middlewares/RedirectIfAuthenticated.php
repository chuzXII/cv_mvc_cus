<?php
namespace App\Middlewares;

class RedirectIfAuthenticated
{
    public static function handle()
    {
        if (isset($_SESSION['user_id'])) {
            // Redirect to dashboard if user is already logged in

            header('Location: /dashboard');
            exit();
        }
    }
}
