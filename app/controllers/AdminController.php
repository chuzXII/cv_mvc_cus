<?php

namespace App\Controllers;
// include_once __DIR__ . '/../../config/database.php';
// namespace App\Controllers;

use App\Models\Project;
use Core\Controller;
// use Core\View;
use Core\Database; // Import namespace Database
use Core\Request;
use PDOException;
use PDO;

class AdminController extends Controller
{
    private $MProject;
    private $MUser;

    public function __construct()
    {
        $this->MProject = new Project();
    }
    public function index()
    {
        $this->view('admin.dashboard', ['title' => 'Dashboard']);
    }
    public function idproject()
    {
        $title = 'Data Project';
        $users = $this->MProject->all();
        $this->view('admin.dataportfolio', ['title' => 'Data Project', 'users' => $users]);

        // View::render('admin.dataportfolio', ['title' => 'Dashboard','users'=>$users]);   



    }
    public function showProjectForm( $id = null)
    {
        // var_dump($id);
        // die();
        if ($id !== null) {
            // $projectData = $this->MProject->find($id);
           
            $this->MProject->where('id_project',$id)->first();
            
        } else {
            $projectData = ['nama_project' => '', 'deksripsi_project' => '', 'kategori_project' => '', 'link_project' => '', 'nama_file' => '']; // Inisialisasi untuk tambah proyek baru
        }

        $this->view('admin.formportfolio', ['title' => 'Form Project', 'projectData' => $projectData]);
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
                header('Location: ' . BASE_URL . '/editportfolio/' . $projectId);
            } else {
                $_SESSION['sweet'] = [
                    'title' => 'Gagal!',
                    'text' => 'Error Validasi',
                    'icon' => 'error'
                ];

                header('Location: ' . BASE_URL . '/../addportfolio');
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
                $result = $this->MProject->getColumnValueById($projectId, ['nama_file'], 'id_project');

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
        header('Location: ' . BASE_URL . '/dataportfolio');
        exit();
    }



    private function updateProject($id, $projectName, $description, $category, $projectLink, $screenshot)
    {
        $data = [
            'nama_project' => $projectName,
            'deksripsi_project' => $description,
            'kategori_project' => $category,
            'link_project' => $projectLink,
            'nama_file' => $screenshot,
            'id_user' => $_SESSION['iduser']
        ];
        $this->MProject->update($id, $data);
    }

    private function addProject($projectName, $description, $category, $projectLink, $screenshot)
    {
        $data = [
            'nama_project' => $projectName,
            'deksripsi_project' => $description,
            'kategori_project' => $category,
            'link_project' => $projectLink,
            'nama_file' => $screenshot,
            'id_user' => $_SESSION['iduser']
        ];
        $this->MProject->create($data);
    }
    public function deleteProject($projectId)
    {
        try {
            $result = $this->MProject->getColumnValueById($projectId,['nama_file']);
            $screenshot = $result['nama_file'];
            $this->MProject->delete($projectId);
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
            header('Location: ' . BASE_URL . '/../dataportfolio');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $_SESSION['sweet'] = [
                'title' => 'Gagal!',
                'text' => 'Terjadi Kesalahan Saat Menghapus Proyek.',
                'icon' => 'error'
            ];
            header('Location: ' . BASE_URL . '/../dataportfolio');
            exit();
        }
    }
    public function detailportfolio($projectId)
    {
        try {
            $project= $this->MProject->find($projectId);

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
        $users = $this->MUser->all();
        $this->view('admin.datauser', ['title' => 'Data User', 'users' => $users]);
    }
    public function showUserForm($id = null)
    {
        // Jika $id tidak null, ini untuk edit pengguna yang ada
        if ($id !== null) {
            $userData = $this->getUserById($id);
        } else {
            $userData = ['name' => '', 'email' => '']; // Inisialisasi untuk tambah pengguna baru
        }

        $this->view('admin.formuser', ['title' => 'Data User', 'userData' => $userData]);
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
        $data =[
            'username'=>$name,
            'email'=>$email,
            'password'=>$pass,
        ];
        $this->MUser->update($id,$data);
    }

    private function addUser($name, $email, $pass)
    {
        $data =[
            'username'=>$name,
            'email'=>$email,
            'password'=>$pass,
        ];
        $this->MUser->create($data);
    }
    public function deleteuser($userId)
    {
        try {
            $this->MUser->delete($userId);
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
        // $title = 'Data Sertifikat';

        // $stmt = $this->conn->query('SELECT * FROM sertifikat');
        // $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // $this->view('admin.datasertifikat', ['title' => 'Data Sertifikat', 'users' => $users]);
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
    private function renderLayout($content)
    {
        // $layout = $this->view->render('../layout/admin/layout', ['content' => $content]);
        // echo $layout;
    }
}
