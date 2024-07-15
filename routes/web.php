<?php

use Core\Router;
use Core\Middleware;
use App\Middlewares\AuthMiddleware;
// Tambahkan middleware ke Router
Router::middleware('auth', AuthMiddleware::class);

Router::get('/', 'HomeController@index');
Router::get('/resume', 'HomeController@iresume');
Router::get('/portfolio', 'HomeController@iportfolio');
Router::get('/contact', 'HomeController@icontact');
Router::group(['auth'], function() {
    Router::get('/dashboard', 'AdminController@index');
    Router::get('/dataportfolio', 'AdminController@idproject');
    Router::get('/addportfolio', 'AdminController@showProjectForm');
    Router::post('/exe/saveproject', 'AdminController@saveProject');
    Router::get('/editportfolio/{id}', 'AdminController@showProjectForm');
    Router::post('/exe/deleteproject/(\d+)', 'AdminController@deleteProject');
    Router::get('/detailportfolio/(\d+)', 'AdminController@detailportfolio');
    Router::get('/datauser', 'AdminController@iduser');
    Router::get('/adduser', 'AdminController@showUserForm');
    Router::post('/exe/saveuser', 'AdminController@saveUser');
    Router::get('/edituser/(\d+)', 'AdminController@showUserForm');
    Router::post('/exe/deleteuser/(\d+)', 'AdminController@deleteUser');
    Router::get('/datasertifikat', 'AdminController@idSertifikat');
});

Router::get('/login', 'AuthController@ilogin');
Router::post('/auth/login', 'AuthController@auth');
Router::get('/regis', 'AuthController@iregis');
Router::post('/auth/register', 'AuthController@register');
Router::get('/logout', 'AuthController@logout');

Router::dispatch();
