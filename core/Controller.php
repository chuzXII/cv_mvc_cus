<?php

namespace Core;
use Core\View;
class Controller
{
    public function model($model)
    {
        require_once '../app/Models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = [])
    {
        View::render($view, $data);
    }
    
}
