<?php
// include_once __DIR__ . '/../../config/database.php';

namespace App\Controllers;

use Core\Controller;
// use Core\View;
use Core\Database;
use PDOException;
use PDO;

class HomeController extends Controller
{
    private $conn;

    public function __construct()
    {
        $this->conn = new Database();
    }
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
        $stmt = $this->conn->query('SELECT * FROM project');
        $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('home.PortfolioView', ['title' => 'Home Page','datas'=>$datas]);
    }
    public function indsex()
    {
        $title = 'About';
        ob_start();
        include '../views/home/AboutView.php';
        $content = ob_get_clean();
        include_once __DIR__ . '/../../views/layout/layout.php';
    }
    public function iresusme()
    {
        $title = 'Resume';
        ob_start();
        include '../views/home/ResumeView.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout/layout.php';
    }
    public function iportfolsio()
    {
        $title = 'Portfolio';
        // Query untuk mengambil data pengguna
        $stmt = $this->conn->query('SELECT * FROM project');
        // $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        include '../views/home/PortfolioView.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout/layout.php';
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
        $title = 'Contact';
        ob_start();
        include '../views/home/ContactView.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout/layout.php';
    }
    public function icertificate()
    {
        $title = 'Certificate';
        ob_start();
        include '../views/home/CetificateView.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout/layout.php';
    }
}
