<?php
include_once __DIR__ . '/../../config/database.php';


class HomeController
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    public function index()
    {
        $title = 'About';
        ob_start();
        include '../views/home/AboutView.php';
        $content = ob_get_clean();
        include_once __DIR__ . '/../../views/layout/layout.php';
    }
    public function iresume()
    {
        $title = 'Resume';
        ob_start();
        include '../views/home/ResumeView.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layout/layout.php';
    }
    public function iportfolio()
    {
        $title = 'Portfolio';
        // Query untuk mengambil data pengguna
        $stmt = $this->conn->query('SELECT * FROM project');
        $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
