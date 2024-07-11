<?php

namespace App\Models;

use Core\Model;

class User extends Model
{
    public function getUsers()
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
