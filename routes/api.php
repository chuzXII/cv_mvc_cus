<?php

use App\Controllers\Api\AdminapiController;
use Core\Router;
Router::get('/usera', [AdminapiController::class,'userall']);
Router::get('/userdetail/{id}', [AdminapiController::class,'detailuser']);
Router::post('/cuser', [AdminapiController::class,'saveuser']);


// Router::dispatch();
