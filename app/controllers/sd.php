<?php

namespace App\Controllers;

use Core\Controller;

class sd extends Controller
{
    public function index()
    {
        $this->view('home.AboutView', ['title' => 'Home Page']);
    }
}
