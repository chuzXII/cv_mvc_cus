<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!defined('BASE_URL')) {
    $base = dirname($_SERVER['SCRIPT_NAME']);
    $base = str_replace('\\', '/', $base);
    $base = str_replace('/public', '', $base);
    define('BASE_URL', rtrim($base, '/'));
}

use Dotenv\Dotenv;
use Core\App;
use Core\Router;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\RedirectIfAuthenticated;

require_once '../vendor/autoload.php';

Router::middleware('auth', AuthMiddleware::class);
Router::middleware('redirectIfAuthenticated', RedirectIfAuthenticated::class);
require_once __DIR__ . '/../config/load_env.php';
loadEnv(__DIR__ . '/../.env');
Router::$prefix = '/api';
require_once '../routes/api.php';
Router::$prefix = '';
require_once '../routes/web.php';

Router::dispatch();


// Memulai aplikasi
$app = new App();
$app->run();
