<?php

use Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\AuthController;

Router::get('/', [HomeController::class, 'index']);
Router::get('/resume', [HomeController::class, 'iresume']);
Router::get('/portfolio', [HomeController::class, 'iportfolio']);
Router::get('/contact', [HomeController::class, 'icontact']);

Router::group(['middleware' => ['auth']], function() {
    Router::get('/dashboard', [AdminController::class, 'index']);
    Router::get('/dataportfolio', [AdminController::class, 'idproject']);
    Router::get('/addportfolio', [AdminController::class, 'showProjectForm']);
    Router::post('/exe/saveproject', [AdminController::class, 'saveProject']);
    Router::get('/editportfolio/{id}', [AdminController::class, 'showProjectForm']);
    Router::post('/exe/deleteproject/{id}', [AdminController::class, 'deleteProject']);
    Router::get('/detailportfolio/{id}', [AdminController::class, 'detailportfolio']);
    Router::get('/datauser', [AdminController::class, 'iduser']);
    Router::get('/adduser', [AdminController::class, 'showUserForm']);
    Router::post('/exe/saveuser', [AdminController::class, 'saveUser']);
    Router::get('/edituser/{id}', [AdminController::class, 'showUserForm']);
    Router::post('/exe/deleteuser/{id}', [AdminController::class, 'deleteUser']);
    Router::get('/datasertifikat', [AdminController::class, 'idSertifikat']);
    Router::get('/addsertifikat', [AdminController::class, 'showSertifikatForm']);
    Router::post('/exe/savesertifikat', [AdminController::class, 'saveSertifikat']);
    Router::get('/editsertifikat/{id}', [AdminController::class, 'showSertifikatForm']);
    Router::post('/exe/deletesertifikat/{id}', [AdminController::class, 'deleteSertifikat']);
});

Router::group(['middleware' => ['redirectIfAuthenticated']], function() {
    Router::get('/login', [AuthController::class, 'ilogin']);
    Router::post('/auth/login', [AuthController::class, 'auth']);
    Router::get('/regis', [AuthController::class, 'iregis']);
    Router::post('/auth/register', [AuthController::class, 'register']);
});

Router::get('/logout', [AuthController::class, 'logout']);

