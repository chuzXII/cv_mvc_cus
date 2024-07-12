<?php

namespace App\Models;

use Core\Model;
use PDO;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';

}
