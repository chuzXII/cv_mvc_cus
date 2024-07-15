<?php
// include_once __DIR__ . '/../../config/database.php';

namespace App\Controllers;

use App\Models\Project;
use Core\Controller;
// use Core\View;
use Core\Database;
use PDOException;
use PDO;

class HomeController extends Controller
{

    public function index()
    {
       $this->view('home.AboutView', ['title' => 'Home Page']);
    }
    public function iresume()
    {
        $this->view('home.ResumeView', ['title' => 'Home Page']);
    }
    public function iportfolio()
    {
        $project = new Project();
        $datas = $project->all();
        $this->view('home.PortfolioView', ['title' => 'Home Page','datas'=>$datas]);
    }
    public function showportfolio($id)
    {
        // Anda bisa mendapatkan data dari database berdasarkan $id di sini
        // Misalnya:
        // $portfolio = getPortfolioById($id);
        include_once __DIR__ . '/../../views/portfolio-detail.php';
    }
    public function icontact()
    {
        $this->view('home.AboutView', ['title' => 'Home Page']);

    }
    public function icertificate()
    {
        $this->view('home.AboutView', ['title' => 'Home Page']);
    }
}
