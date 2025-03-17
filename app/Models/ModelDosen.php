<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelDosen extends Model
{
    protected $table = 'dosen_pembimbing';
    protected $primaryKey = 'id_dosen';
    protected $allowedFields = ['id_user', 'nama', 'nidn', 'foto'];

    public function getDosenByUserId($userId)
    {
        try {
            $builder = $this->db->table($this->table);
            $result = $builder->select('dosen_pembimbing.*, user.username, user.email')
                            ->join('user', 'user.id_user = dosen_pembimbing.id_user')
                            ->where('dosen_pembimbing.id_user', $userId)
                            ->get()
                            ->getRowArray();

            if (empty($result)) {
                return [
                    'id_dosen' => null,
                    'id_user' => $userId,
                    'nama' => '',
                    'nidn' => '',
                    'foto' => null,
                    'username' => '',
                    'email' => ''
                ];
            }

            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error di getDosenByUserId: ' . $e->getMessage());
            return [
                'id_dosen' => null,
                'id_user' => $userId,
                'nama' => '',
                'nidn' => '',
                'foto' => null,
                'username' => '',
                'email' => ''
            ];
        }
    }

    public function updateProfil($id_dosen, $data)
    {
        try {
            if (!$id_dosen || !is_array($data)) {
                throw new \Exception('Invalid input data');
            }

            // Pastikan data yang diupdate sesuai dengan struktur tabel
            $updateData = [
                'nama' => $data['nama'],
                'nidn' => $data['nidn']
            ];

            // Jika ada foto baru, tambahkan ke data update
            if (!empty($data['foto'])) {
                $updateData['foto'] = $data['foto'];
            }
            
            return $this->update($id_dosen, $updateData);
            
        } catch (\Exception $e) {
            log_message('error', 'Error di updateProfil: ' . $e->getMessage());
            return false;
        }
    }

    public function getAllDosen()
    {
        try {
            $result = $this->select('dosen_pembimbing.*, user.username, user.email')
                        ->join('user', 'user.id_user = dosen_pembimbing.id_user')
                        ->findAll();
            
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            log_message('error', 'Error di getAllDosen: ' . $e->getMessage());
            return [];
        }
    }

    public function getDosenWithUser($orderBy = 'id_dosen')
    {
        $builder = $this->db->table('dosen_pembimbing');
        $builder->select('dosen_pembimbing.*, user.username, user.email');
        $builder->join('user', 'user.id_user = dosen_pembimbing.id_user', 'left');
        $builder->orderBy($orderBy);
        return $builder->get()->getResultArray();
    }

    public function getBimbingan($id_dosen)
    {
        return $this->db->table('bimbingan b')
                        ->select('b.*, m.nama as nama_mahasiswa, m.nim')
                        ->join('mahasiswa m', 'm.id_mahasiswa = b.id_mahasiswa')
                        ->where('b.id_dosen', $id_dosen)
                        ->orderBy('b.tanggal', 'DESC')
                        ->get()
                        ->getResultArray();
    }

    public function getBimbinganById($id_bimbingan)
    {
        return $this->db->table('bimbingan')
                        ->where('id_bimbingan', $id_bimbingan)
                        ->get()
                        ->getRowArray();
    }

    public function insertBimbingan($data)
    {
        return $this->db->table('bimbingan')->insert($data);
    }

    public function updateBimbingan($id_bimbingan, $data)
    {
        return $this->db->table('bimbingan')
                        ->where('id_bimbingan', $id_bimbingan)
                        ->update($data);
    }

    public function deleteBimbingan($id_bimbingan)
    {
        return $this->db->table('bimbingan')
                        ->where('id_bimbingan', $id_bimbingan)
                        ->delete();
    }

    public function getMahasiswaBimbingan($id_dosen)
    {
        return $this->db->table('mahasiswa')
                        ->select('id_mahasiswa, nim, nama')
                        ->get()
                        ->getResultArray();
    }
} 