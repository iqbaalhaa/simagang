<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelAdmin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'foto', 'user_id'];

    public function getAdminById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function updateProfile($id, $data)
    {
        return $this->update($id, $data);
    }

    public function getAdminWithUser($id)
    {
        $query = $this->select('admin.*, user.username, user.email as user_email')
            ->join('user', 'user.id = admin.user_id')
            ->where('admin.id', $id)
            ->get();

        // Debugging
        dd($this->getLastQuery()->getQuery()); // Periksa query yang dijalankan

        return $query->getFirstRow();
    }
}
