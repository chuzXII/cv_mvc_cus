<?php
session_start(); // Mulai sesi

// Base directory for controllers
$baseDir = __DIR__ . '/app/controllers/';

// Include controllers
include_once $baseDir . 'HomeController.php';
include_once $baseDir . 'AdminController.php';
require_once __DIR__ . '/config/load_env.php';
loadEnv(__DIR__ . '/.env');

// Include middleware
include_once __DIR__ . '/app/middleware/AuthMiddleware.php';

// Define routes
$routes = [
    '/' => ['controller' => 'HomeController@index', 'middleware' => null],
    '/portfolio' => ['controller' => 'HomeController@iportfolio', 'middleware' => null],
    '/resume' => ['controller' => 'HomeController@iresume', 'middleware' => null],
    '/contact' => ['controller' => 'HomeController@icontact', 'middleware' => null],
    '/dashboard' => ['controller' => 'AdminController@index', 'middleware' => 'AuthMiddleware'],
    '/dataportfolio' => ['controller' => 'AdminController@idproject', 'middleware' => 'AuthMiddleware'],
    '/detailportfolio/(\d+)' => ['controller' => 'AdminController@detailportfolio', 'middleware' => 'AuthMiddleware'],
    '/addportfolio' => ['controller' => 'AdminController@showProjectForm', 'middleware' => 'AuthMiddleware'],
    '/exe/saveproject' => ['controller' => 'AdminController@saveProject', 'middleware' => 'AuthMiddleware'],
    '/exe/deleteproject/(\d+)' => ['controller' => 'AdminController@deleteProject', 'middleware' => 'AuthMiddleware'],
    '/datauser' => ['controller' => 'AdminController@iduser', 'middleware' => 'AuthMiddleware'],
    '/detailuser' => ['controller' => 'AdminController@detailuser', 'middleware' => 'AuthMiddleware'],
    '/adduser' => ['controller' => 'AdminController@showUserForm', 'middleware' => 'AuthMiddleware'],
    '/exe/saveuser' => ['controller' => 'AdminController@saveUser', 'middleware' => 'AuthMiddleware'],
    '/exe/deleteuser/(\d+)' => ['controller' => 'AdminController@deleteUser', 'middleware' => 'AuthMiddleware'],
    '/datasertifikat' => ['controller' => 'AdminController@idSertifikat', 'middleware' => 'AuthMiddleware'],
    '/login' => ['controller' => 'AdminController@ilogin', 'middleware' => null],
    '/regis' => ['controller' => 'AdminController@iregis', 'middleware' => null],
    '/auth/login' => ['controller' => 'AdminController@auth', 'middleware' => null],
    '/logout' => ['controller' => 'AdminController@logout', 'middleware' => null],
    '/register' => ['controller' => 'AdminController@showRegisterForm', 'middleware' => null],
    '/auth/register' => ['controller' => 'AdminController@register', 'middleware' => null],

    // Dynamic route example
    '/portfolio/(\d+)' => ['controller' => 'AdminController@show', 'middleware' => null],
    '/edituser/(\d+)'  => ['controller' => 'AdminController@showUserForm', 'middleware' =>   'AuthMiddleware'],
    '/editportfolio/(\d+)'  => ['controller' => 'AdminController@showProjectForm', 'middleware' =>   'AuthMiddleware'],


];

// Get the current URI
$uri = $_SERVER['REQUEST_URI'];

// Hapus hanya prefiks "/cvv" dari awal URI
if (strpos($uri, '/cvv') === 0) {
    $uri = substr($uri, strlen('/cvv'));
}
// var_dump($uri);
// die();


// Find route
$routeFound = false;
foreach ($routes as $routePattern => $routeData) {
    // var_dump($uri);

    // $pattern = '@^' . preg_replace('/\\\[(\d\+)]/', '(\d+)', preg_quote($routePattern)) . '$@';
    $pattern = '@^' . $routePattern . '$@';
    if (preg_match($pattern, $uri, $matches)) {
        array_shift($matches); // Remove full match
        $controllerData = $routeData;


        $routeFound = true;
        break;
    }
}

// Handle route
if ($routeFound) {
    list($controllerName, $methodName) = explode('@', $controllerData['controller']);

    // Include controller file
    include_once $baseDir . $controllerName . '.php';

    // Create controller object
    $controller = new $controllerName();

    // Execute middleware if defined
    if ($controllerData['middleware']) {
        call_user_func([$controllerData['middleware'], 'handle']);
    }

    // Call method with parameters
    call_user_func_array([$controller, $methodName], $matches);
} else {
    // Handle 404 Not Found
    header("HTTP/1.0 404 Not Found");
    echo '404 Not Found';
}
