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
require_once '../vendor/autoload.php';
require_once '../core/App.php';
require_once '../core/Controller.php';
require_once '../core/Model.php';
require_once '../core/Database.php';
require_once '../core/View.php';
require_once '../core/Router.php';


require_once __DIR__ . '/../config/load_env.php';
loadEnv(__DIR__ . '/../.env');
require_once '../routes/web.php';


// Memulai aplikasi
$app = new App();
$app->run();
