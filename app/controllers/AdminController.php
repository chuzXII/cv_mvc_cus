<?php

// include_once __DIR__ . '/../../config/database.php';
// namespace App\Controllers;
use  App\Core\Controller;
use  App\Core\View;
use  App\Core\Database; // Import namespace Database
// use PDOException;
// use PDO;

class AdminController extends Controller
{
    private $conn;
    public function __construct() {
        $this->view = new View(__DIR__ . '/../../views/admin');
        $this->conn = new Database();
    }
    public function index()
    {
        $title = 'Dashboard';
        $content = $this->view->render('dashboard', ['title' => $title]);
        $this->renderLayout($content);
        
    }
    public function idproject()
    {
        $title = 'Data Project';
        $stmt = $this->conn->query('SELECT * FROM project');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $content = $this->view->render('dataportfolio', ['title' => $title, 'users' => $users]);
        $this->renderLayout($content);
        

    }
    public function showProjectForm($id = null)
    {
        $title = 'Form Project';
        // Jika $id tidak null, ini untuk edit proyek yang ada
        if ($id !== null) {
            $projectData = $this->getProjectById($id);
        } else {
            $projectData = ['nama_project' => '', 'deksripsi_project' => '', 'kategori_project' => '', 'link_project' => '', 'nama_file' => '']; // Inisialisasi untuk tambah proyek baru
        }
        ob_start();
        // Tampilkan formulir
        include_once __DIR__ . '/../../views/admin/formportfolio.php';
        $content = ob_get_clean();
        // Masukkan ke dalam layout
        include_once __DIR__ . '/../../views/layout/admin/layout.php';
    }
    public function editportfolio($id)
    {
        $title = 'Edit Data User';

        ob_start();
        if ($id !== null) {
            $userData = $this->getUserById($id);
        } else {
            $userData = ['name' => '', 'email' => '']; // Inisialisasi untuk tambah pengguna baru
        }
        include 'views/admin/formportfolio.php';
        $content = ob_get_clean();
        include_once __DIR__ . '/../../views/layout/admin/layout.php';
    }
    public function saveProject()
    {
        // Proses penyimpanan atau update data proyek dari formulir
        $projectId = isset($_POST['id_project']) ? $_POST['id_project'] : null;
        $projectName = isset($_POST['project_name']) ? $_POST['project_name'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $category = isset($_POST['category']) ? $_POST['category'] : '';
        $projectLink = isset($_POST['project_link']) ? $_POST['project_link'] : '';
        $screenshot = isset($_FILES['screenshot']['name']) ? $_FILES['screenshot']['name'] : '';


        // Validasi input
        $errors = [];

        if (empty($projectName)) {
            $errors['project_name'] = "Nama proyek tidak boleh kosong.";
        }

        if (empty($description)) {
            $errors['description'] = "Deskripsi tidak boleh kosong.";
        }

        if (empty($category)) {
            $errors['category'] = "Kategori tidak boleh kosong.";
        }

        if (!filter_var($projectLink, FILTER_VALIDATE_URL) && !empty($projectLink)) {
            $errors['project_link'] = "Link proyek tidak valid.";
        }

        // Cek jika screenshot kosong hanya untuk proyek baru
        if (empty($screenshot) && empty($projectId)) {
            $errors['screenshot'] = "File screenshot tidak boleh kosong.";
        }

        if ($screenshot) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['screenshot']['type'], $allowedTypes)) {
                $errors['screenshot'] = "Format file screenshot tidak didukung. Hanya JPEG, PNG, dan GIF yang diperbolehkan.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;

            if ($projectId) {
                $_SESSION['sweet'] = [
                    'title' => 'Gagal!',
                    'text' => 'Error Validasi',
                    'icon' => 'error'
                ];
                header('Location: /editportfolio/' . $projectId);
            } else {
                $_SESSION['sweet'] = [
                    'title' => 'Gagal!',
                    'text' => 'Error Validasi',
                    'icon' => 'error'
                ];
                header('Location: /addportfolio');
            }
            exit();
        }
        // Simpan file screenshot
        $uploadDir = __DIR__ . '/../../uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // 0777 memberikan izin penuh dan true membuat direktori secara rekursif
        }
        $extension = pathinfo($screenshot, PATHINFO_EXTENSION);
        $name_file =  "img-" . $projectName . "." . $extension;
        // $uploadFile = $uploadDir . $name_file;
        // $GLOBALS['name'] = "img-" . $projectName . "." . $extension;

        if ($projectId) {
            if (empty($screenshot)) {
                $query = "SELECT nama_file FROM project WHERE id_project = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $projectId);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $name_file = $result['nama_file'];
                // $extension = pathinfo($screenshot['name'], PATHINFO_EXTENSION);
                // $name_file =  "img-" . $projectName . "." . $extension;
                // echo "<script>console.log(1);</script>";


            } else {
                // Simpan file screenshot baru
                // echo "<script>console.log(2);</script>";

                move_uploaded_file($_FILES['screenshot']['tmp_name'], __DIR__ . '/../../uploads/' . $name_file);
            }
            $_SESSION['sweet'] = [
                'title' => 'Berhasil!',
                'text' => 'Proyek Berhasil Diedit.',
                'icon' => 'success'
            ];
            $this->updateProject($projectId, $projectName, $description, $category, $projectLink, $name_file);
        } else {
            // Jika tidak, ini untuk tambah proyek baru
            move_uploaded_file($_FILES['screenshot']['tmp_name'], __DIR__ . '/../../uploads/' . $name_file);
            $_SESSION['sweet'] = [
                'title' => 'Berhasil!',
                'text' => 'Proyek Berhasil Ditambahkan.',
                'icon' => 'success'
            ];
            $this->addProject($projectName, $description, $category, $projectLink, $name_file);
        }

        // Redirect ke halaman dashboard atau halaman lain yang sesuai
        header('Location: /dataportfolio');
        exit();
    }


    private function getProjectById($id)
    {
        // Mengambil data proyek berdasarkan $id dari database
        $query = "SELECT * FROM project WHERE id_project = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function updateProject($id, $projectName, $description, $category, $projectLink, $screenshot)
    {

        // Update data proyek ke dalam database
        $query = "UPDATE project SET nama_project = :project_name, deksripsi_project = :descriptions, kategori_project = :category, link_project = :project_link, nama_file = :screenshot,id_user = :iduser WHERE id_project  = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_name', $projectName);
        $stmt->bindParam(':descriptions', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':project_link', $projectLink);
        $stmt->bindParam(':screenshot', $screenshot);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':iduser', $_SESSION['iduser']);
        $stmt->execute();
    }

    private function addProject($projectName, $description, $category, $projectLink, $screenshot)
    {
        // Menambahkan data proyek baru ke dalam database
        $query = "INSERT INTO project (nama_project, deksripsi_project, kategori_project, link_project, nama_file,id_user) VALUES (:project_name, :descriptions, :category, :project_link, :screenshot, :iduser)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':project_name', $projectName);
        $stmt->bindParam(':descriptions', $description);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':project_link', $projectLink);
        $stmt->bindParam(':screenshot', $screenshot);
        $stmt->bindParam(':iduser', $_SESSION['iduser']);
        $stmt->execute();
    }
    public function deleteProject($projectId)
    {
        $query = "SELECT nama_file FROM project WHERE id_project = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $projectId);


        try {

            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $screenshot = $result['nama_file'];

            $query = "DELETE FROM project WHERE id_project = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $projectId);
            $stmt->execute();

            if (!empty($screenshot)) {
                $uploadPath = __DIR__ . '/../../uploads/';
                $filePath = $uploadPath . $screenshot;
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file dari folder
                }
            }

            $_SESSION['sweet'] = [
                'title' => 'Berhasil!',
                'text' => 'Proyek Berhasil Dihapus.',
                'icon' => 'success'
            ];
            header('Location: /dataportfolio');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $_SESSION['sweet'] = [
                'title' => 'Gagal!',
                'text' => 'Terjadi Kesalahan Saat Menghapus Proyek.',
                'icon' => 'error'
            ];
            header('Location: /dataportfolio');
            exit();
        }
    }
    public function detailportfolio($projectId)
    {
        $query = "SELECT * FROM project WHERE id_project = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $projectId);

        try {
            $stmt->execute();
            $project = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($project) {
                $title = 'Data Portfolio';
                ob_start();
                include 'views/admin/detailportfolio.php';
                $content = ob_get_clean();
                include_once __DIR__ . '/../../views/layout/admin/layout.php';
            } else {
                header('Location: /dataportfolio');
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function iduser()
    {
        $title = 'Data User';
        // Query untuk mengambil data pengguna
        $stmt = $this->conn->query('SELECT * FROM user');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Panggil tampilan datauser.php dengan menyertakan data pengguna
        ob_start();
        include_once __DIR__ . '/../../views/admin/datauser.php';
        $content = ob_get_clean();
        include_once __DIR__ . '/../../views/layout/admin/layout.php';
    }
    public function showUserForm($id = null)
    {
        // Jika $id tidak null, ini untuk edit pengguna yang ada
        if ($id !== null) {
            $userData = $this->getUserById($id);
        } else {
            $userData = ['name' => '', 'email' => '']; // Inisialisasi untuk tambah pengguna baru
        }

        // Tampilkan formulir
        ob_start();
        include_once __DIR__ . '/../../views/admin/formuser.php';
        $content = ob_get_clean();
        // Masukkan ke dalam layout
        include_once __DIR__ . '/../../views/layout/admin/layout.php';
    }
    public function saveUser()
    {
        // Proses penyimpanan atau update data pengguna dari formulir
        $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
        $name = isset($_POST['username']) ? $_POST['username'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $pass = isset($_POST['password']) ? $_POST['password'] : '';
        $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);


        if ($userId) {
            // Jika $userId ada, ini untuk update pengguna yang ada
            $this->updateUser($userId, $name, $email, $hashedPassword);
            $_SESSION['sweet'] = [
                'title' => 'Berhasil!',
                'text' => 'User Berhasil Di Ubah.',
                'icon' => 'success'
            ];
        } else {
            // Jika tidak, ini untuk tambah pengguna baru
            $this->addUser($name, $email, $hashedPassword);
            $_SESSION['sweet'] = [
                'title' => 'Berhasil!',
                'text' => 'User Berhasil Ditambah.',
                'icon' => 'success'
            ];
        }

        // Redirect ke halaman dashboard atau halaman lain yang sesuai
        header('Location: /datauser');
        exit();
    }

    private function updateUser($id, $name, $email, $pass)
    {
        // Update data pengguna ke dalam database
        $query = "UPDATE user SET username = :username, email = :email,password = :password WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $pass);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    private function addUser($name, $email, $pass)
    {
        // Menambahkan data pengguna baru ke dalam database
        $query = "INSERT INTO user (username, email,password) VALUES (:name, :email,:password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $pass);
        $stmt->execute();
    }
    public function deleteuser($userId)
    {
        $query = "DELETE FROM user WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);

        try {
            $stmt->execute();
            // Redirect ke halaman dashboard atau halaman lain yang sesuai setelah berhasil dihapus
            $_SESSION['sweet'] = [
                'title' => 'Berhasil!',
                'text' => 'User Berhasil Dihapus.',
                'icon' => 'success'
            ];
            header('Location: /datauser');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $_SESSION['sweet'] = [
                'title' => 'Gagal!',
                'text' => 'Terjadi Kesalahan Saat Menghapus User.',
                'icon' => 'error'
            ];
        }
    }
    public function detailuser()
    {
        $title = 'Data User';
        ob_start();
        include 'views/admin/detailuser.php';
        $content = ob_get_clean();
        include_once __DIR__ . '/../../views/layout/admin/layout.php';
    }
    public function ilogin()
    {
        $title = 'Login App';
        // ob_start();
        // include 'views/login.php';
        // $content = ob_get_clean();
        include_once __DIR__ . '/../../views/login.php';
    }
    public function iregis()
    {
        $title = 'Registrasi App';
        // ob_start();
        // include 'views/registrasi.php';
        // $content = ob_get_clean();
        include_once __DIR__ . '/../../views/registrasi.php';
    }

    public function auth()
    {
        // Validasi input
        $username = htmlspecialchars(trim($_POST['username']));
        $password = htmlspecialchars(trim($_POST['password']));

        // Ambil user dari database (misalnya, menggunakan PDO)
        $pdo = new PDO('mysql:host=localhost;dbname=cv2', 'root', '');
        $stmt = $pdo->prepare('SELECT * FROM user WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifikasi password
        if ($user && password_verify($password, $user['password'])) {
            // Setel variabel sesi
            $_SESSION['user'] = $user['username'];
            $_SESSION['iduser'] = $user['id_user'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // $_SESSION['last_activity'] = time();
            // $_SESSION['session_token'] = AuthMiddleware::generateSessionToken();
            $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $host = $_SERVER['HTTP_HOST'];

            // Redirect ke dashboard atau halaman lain
            $this->redirect('/dashboard');

            // $this->redirect('dashboard');

        } else {
            // Jika login gagal, kembalikan ke halaman login dengan pesan error
            $_SESSION['error'] = 'Username atau password salah.';
            header('Location: /login');
            exit();
        }
    }

    public function logout()
    {
        // Hapus semua data sesi
        session_unset();
        session_destroy();

        // Redirect ke halaman utama atau halaman login
        header('Location: /');
        exit();
    }
    public function register()
    {
        $username = htmlspecialchars(trim($_POST['username']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));
        $confirmPassword = htmlspecialchars(trim($_POST['cpassword']));

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Password dan konfirmasi password tidak cocok.';
            header('Location: /regis');
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $pdo = new PDO('mysql:host=localhost;dbname=cv2', 'root', '');
        $stmt = $pdo->prepare('INSERT INTO user (username, email, password) VALUES (?,?,?)');
        if ($stmt->execute([$username, $email, $hashedPassword])) {
            // $_SESSION['user'] = $username;
            // $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: /login');
            exit();
        } else {
            $_SESSION['error'] = 'Gagal mendaftarkan pengguna baru.';
            header('Location: /regis');
            exit();
        }
    }

    private function getUserById($id)
    {
        // Mengambil data pengguna berdasarkan $id dari database
        $pdo = new PDO('mysql:host=localhost;dbname=cv2', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT * FROM user WHERE id_user = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function idSertifikat()
    {
        $title = 'Data Sertifikat';

        $stmt = $this->conn->query('SELECT * FROM sertifikat');
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Panggil tampilan datauser.php dengan menyertakan data pengguna
        ob_start();
        include_once __DIR__ . '/../../views/admin/datasertifikat.php';
        $content = ob_get_clean();
        include_once __DIR__ . '/../../views/layout/admin/layout.php';
    }


    public function redirect($route)
    {
        // Tentukan protokol berdasarkan kondisi HTTPS
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

        // Dapatkan hostname dan port dari permintaan
        $full_host = $_SERVER['HTTP_HOST'];

        // Pisahkan hostname dan port jika ada
        $url_parts = explode(':', $full_host);
        $hostname = $url_parts[0];
        $port = isset($url_parts[1]) ? ':' . $url_parts[1] : '';

        if (isset($url_parts[1])) {
            $redirect_url = "$protocol$full_host$route";
        } else {
            $redirect_url = "$protocol$hostname/cvv$route";
        }

        header("Location: $redirect_url");
        exit;
    }
    private function renderLayout($content) {
        $layout = $this->view->render('../layout/admin/layout', ['content' => $content]);
        echo $layout;
    }
}