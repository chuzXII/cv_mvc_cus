<?php

namespace App\Controllers\Api;
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

class AdminapiController extends Controller
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
    public function userall(){
        $dsertifikat = $this->MUser->all();
        Response::json($dsertifikat);
    }
    public function detailuser($id){
        $dsertifikat = $this->MUser->find($id);
        Response::json($dsertifikat);
    }
    public function saveuser(Request $req){
        $u = $req->input('username');
        $p = $req->input('password');
        Response::json(['data'=>[$u,$p]]);
    }
    
}
