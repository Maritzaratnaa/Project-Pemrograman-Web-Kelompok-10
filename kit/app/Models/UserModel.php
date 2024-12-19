<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user'; 
    protected $primaryKey       = 'id_user';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_user', 'email', 'telepon', 'alamat', 'password', 'role'
    ];

    public function getUser($user_id = false)
    {
        if ($user_id === false) {
            return $this->findAll();
        } else {
            return $this->where('id_user', $user_id)->first();
        }
    }

    public function saveUser($data)
    {
        return $this->insert($data);
    }

    public function updateUser($user_id, $data)
    {
        return $this->update($user_id, $data);
    }

    public function deleteUser($user_id)
    {
        return $this->delete($user_id);
    }

    public function validateUser($email, $password)
    {
        $user = $this->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }

}
