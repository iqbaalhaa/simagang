<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelMahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id_mahasiswa';
    protected $allowedFields = ['id_user', 'nim', 'nama', 'angkatan', 'instansi', 'foto', 'instansi_id'];

    public function getMahasiswaByUserId($userId)
    {
        try {
            $result = $this->select('mahasiswa.*, user.username, user.email')
                        ->join('user', 'user.id_user = mahasiswa.id_user')
                        ->where('mahasiswa.id_user', $userId)
                        ->first();

            // Jika tidak ada data, kembalikan array kosong dengan struktur yang sama
            if (empty($result)) {
                return [
                    'id_mahasiswa' => null,
                    'id_user' => $userId,
                    'nim' =>'',
                    'nama' => '',
                    'angkatan' => '',
                    'instansi' => '',
                    'foto' => '',
                    'username' => '',
                    'email' => ''
                ];
            }

            // Pastikan result adalah array
            return is_array($result) ? $result : (array)$result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error di getMahasiswaByUserId: ' . $e->getMessage());
            return [
                'id_mahasiswa' => null,
                'id_user' => $userId,
                'nim' =>'',
                'nama' => '',
                'angkatan' => '',
                'instansi' => '',
                'foto' => '',
                'username' => '',
                'email' => ''
            ];
        }
    }

    public function updateProfil($id_mahasiswa, $data)
    {
        try {
            if (!$id_mahasiswa || !is_array($data)) {
                log_message('error', 'Invalid input data. ID Mahasiswa: ' . $id_mahasiswa);
                throw new \Exception('Invalid input data');
            }

            // Debugging: Tampilkan data yang akan diupdate
            log_message('info', 'Updating mahasiswa with ID: ' . $id_mahasiswa . ' with data: ' . json_encode($data));
            
            // Pastikan data yang diupdate sesuai dengan struktur tabel
            $updateData = [
                'nim' => $data['nim'],
                'nama' => $data['nama'],
                'angkatan' => $data['angkatan'],
                'instansi' => $data['instansi']
            ];

            // Jika ada foto baru, tambahkan ke data update
            if (!empty($data['foto'])) {
                $updateData['foto'] = $data['foto'];
            }
            
            $result = $this->update($id_mahasiswa, $updateData);
            
            if ($result === false) {
                log_message('error', 'Update failed for mahasiswa ID: ' . $id_mahasiswa);
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error di updateProfil: ' . $e->getMessage());
            return false;
        }
    }

    public function getMahasiswaWithUser($id)
    {
        $query = $this->select('mahasiswa.*, user.username, user.email as user_email')
            ->join('user', 'user.id = mahasiswa.user_id')
            ->where('mahasiswa.id', $id)
            ->get();

        // Debugging
        dd($this->getLastQuery()->getQuery()); // Periksa query yang dijalankan

        return $query->getFirstRow();
    }

    public function getAllMahasiswa()
    {
        try {
            $result = $this->select('mahasiswa.*, user.username, user.email')
                        ->join('user', 'user.id_user = mahasiswa.id_user')
                        ->findAll();
            
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            log_message('error', 'Error di getAllMahasiswa: ' . $e->getMessage());
            return [];
        }
    }
} 