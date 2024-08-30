<?php

namespace App\Controllers;
// include_once __DIR__ . '/../../config/database.php';
// namespace App\Controllers;

use App\Models\Project;
use App\Models\Sertifikat;
use App\Models\User;
use Core\Controller;
// use Core\View;
use Core\Database;
use Core\Request;
use Core\Response;
use PDOException;
use PDO;

class AdminController extends Controller
{
    private $MProject;
    private $MSertifikat;
    private $MUser;


    public function __construct()
    {
        $this->MProject = new Project();
        $this->MSertifikat = new Sertifikat();
        $this->MUser = new User();
        
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
    public function showProjectForm($id = null)
    {
        // var_dump($id);
        // die();
        if ($id !== null) {
            $projectData = $this->MProject->find($id);

            // $this->MProject->where('id_project', $id)->first();
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
            $result = $this->MProject->getColumnValueById($projectId, ['nama_file']);
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
            $project = $this->MProject->find($projectId);

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
        $data = [
            'username' => $name,
            'email' => $email,
            'password' => $pass,
        ];
        $this->MUser->update($id, $data);
    }

    private function addUser($name, $email, $pass)
    {
        $data = [
            'username' => $name,
            'email' => $email,
            'password' => $pass,
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
        $dsertifikat = $this->MSertifikat->all();
        $this->view('admin.datasertifikat', ['title' => 'Data Sertifikat', 'dsertifikat' => $dsertifikat]);
    }
    public function showSertifikatForm($id = null)
    {
        if ($id !== null) {
            $sertifikatData = $this->MSertifikat->find($id);
            // $this->MProject->where('id_project',$id)->first();

        } else {
            $sertifikatData = ['nama_project' => '', 'deksripsi_project' => '', 'kategori_project' => '', 'link_project' => '', 'nama_file' => '']; // Inisialisasi untuk tambah proyek baru
        }

        $this->view('admin.formsertifikat', ['title' => 'Form Project', 'sertifikatData' => $sertifikatData]);
    }
    public function saveSertifikat(Request $req)
    {
        $sertifikatName = htmlspecialchars($req->input('sertifikat_name'));
        $screenshot = $req->file('screenshot');
        $sertifikatId = $req->input('id_sertifikat');
       
            // Validate the uploaded file
            $validated = $req->validate([
                'sertifikat_name' => 'required',
                'screenshot' => 'file|mimes:image/jpeg,image/png',
            ]);
            if (!$validated->passes()) {
                $errors = $validated->errors();
                $this->withErrors($errors);
                if ($sertifikatId) {
                    $_SESSION['sweet'] = [
                        'title' => 'Gagal!',
                        'text' => 'Error Validasi',
                        'icon' => 'error'
                    ];
                    $this->redirect('/editportfolio/' . $sertifikatId);
                } else {
                    $_SESSION['sweet'] = [
                        'title' => 'Gagal!',
                        'text' => 'Error Validasi',
                        'icon' => 'error'
                    ];
                    $this->redirect('/addportfolio/');
                }
                return;
            }
        

        $uploadDir = __DIR__ . '/../../uploads/img/sertifikat/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $extension = pathinfo($screenshot['name'], PATHINFO_EXTENSION);
        $name_file =  "img-cert-" . $sertifikatName . "." . $extension;

        if ($sertifikatId) {
            if (empty($screenshot)) {
                $result = $this->MSertifikat->getColumnValueById($sertifikatId, ['nama_file_sertifikat'], 'id_sertifikat');
                $name_file = $result['nama_file_sertifikat'];
            } else {
                move_uploaded_file($screenshot['tmp_name'], __DIR__ . '/../../uploads/img/sertifikat/' . $name_file);
            }
            $_SESSION['sweet'] = [
                'title' => 'Berhasil!',
                'text' => 'Sertifikat Berhasil Diedit.',
                'icon' => 'success'
            ];
            $this->updateSertifikat($sertifikatId, $sertifikatName, $name_file);
        } else {
            // Jika tidak, ini untuk tambah proyek baru
            move_uploaded_file($_FILES['screenshot']['tmp_name'], __DIR__ . '/../../uploads/img/sertifikat/' . $name_file);
            $_SESSION['sweet'] = [
                'title' => 'Berhasil!',
                'text' => 'Sertifikat Berhasil Ditambahkan.',
                'icon' => 'success'
            ];
            $this->addSertifikat($sertifikatName, $name_file);
        }


        $this->redirect('/datasertifikat');
    }
    private function updateSertifikat($id, $sertifikatName, $screenshot)
    {
        $data = [
            'nama_sertifikat' => $sertifikatName,
            'nama_file_sertifikat' => $screenshot,
            'id_user' => $_SESSION['iduser']
        ];
        $this->MSertifikat->update($id, $data);
    }

    private function addSertifikat($sertifikatName, $screenshot)
    {
        $data = [
            'nama_sertifikat' => $sertifikatName,
            'nama_file_sertifikat' => $screenshot,
            'id_user' => $_SESSION['iduser']
        ];
        $this->MSertifikat->create($data);
    }
    public function deleteSertifikat($projectId)
    {
        try {
            $result = $this->MSertifikat->getColumnValueById($projectId, ['nama_file']);
            $screenshot = $result['nama_file'];
            $this->MSertifikat->delete($projectId);
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
    public function detailSertifikat($projectId)
    {
        try {
            $project = $this->MProject->find($projectId);

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
    public function sd(){
        $dsertifikat = $this->MUser->all();
        Response::json($dsertifikat);
    }
    
}
