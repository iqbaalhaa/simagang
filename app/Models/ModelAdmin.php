<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelAdmin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $allowedFields = ['id_user', 'nama', 'foto'];

    public function getAdminByUserId($userId)
    {
        try {
            $result = $this->select('admin.*, user.username, user.email')
                        ->join('user', 'user.id_user = admin.id_user')
                        ->where('admin.id_user', $userId)
                        ->first();

            // Jika tidak ada data, kembalikan array kosong dengan struktur yang sama
            if (empty($result)) {
                return [
                    'id_admin' => null,
                    'id_user' => $userId,
                    'nama' => '',
                    'foto' => '',
                    'username' => '',
                    'email' => ''
                ];
            }

            // Pastikan result adalah array
            return is_array($result) ? $result : (array)$result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error di getAdminByUserId: ' . $e->getMessage());
            return [
                'id_admin' => null,
                'id_user' => $userId,
                'nama' => '',
                'foto' => '',
                'username' => '',
                'email' => ''
            ];
        }
    }

    public function updateProfil($id_admin, $data)
    {
        try {
            if (!$id_admin || !is_array($data)) {
                throw new \Exception('Invalid input data');
            }
            return $this->update($id_admin, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di updateProfil: ' . $e->getMessage());
            return false;
        }
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

    public function getAllAdmin()
    {
        try {
            $result = $this->select('admin.*, user.username, user.email')
                        ->join('user', 'user.id_user = admin.id_user')
                        ->findAll();
            
            return is_array($result) ? $result : [];
        } catch (\Exception $e) {
            log_message('error', 'Error di getAllAdmin: ' . $e->getMessage());
            return [];
        }
    }

    public function tambahAdmin($userData, $adminData)
    {
        try {
            $db = \Config\Database::connect();
            $db->transStart();

            // Insert ke tabel user
            $db->table('user')->insert($userData);
            $userId = $db->insertID();

            // Tambahkan id_user ke data admin
            $adminData['id_user'] = $userId;

            // Insert ke tabel admin
            $this->insert($adminData);

            $db->transComplete();

            return $db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'Error di tambahAdmin: ' . $e->getMessage());
            return false;
        }
    }

    public function tambahDosen($userData, $dosenData)
    {
        try {
            // Pastikan $db didefinisikan
            $db = \Config\Database::connect();
            $db->transStart();

            // Insert ke tabel user
            $db->table('user')->insert($userData);
            $userId = $db->insertID();

            // Tambahkan id_user ke data dosen
            $dosenData['id_user'] = $userId;

            // Insert ke tabel dosen_pembimbing
            $result = $db->table('dosen_pembimbing')->insert($dosenData);
            
            if (!$result) {
                log_message('error', 'Gagal insert ke tabel dosen_pembimbing: ' . $db->error()['message']);
                $db->transRollback();
                return false;
            }

            $db->transComplete();
            
            return $db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'Error di tambahDosen: ' . $e->getMessage());
            // Pastikan rollback jika terjadi error
            if (isset($db) && $db->transStatus() === false) {
                $db->transRollback();
            }
            return false;
        }
    }

    public function tambahMahasiswa($userData, $mahasiswaData)
    {
        try {
            // Pastikan $db didefinisikan
            $db = \Config\Database::connect();
            $db->transStart();

            // Insert ke tabel user
            $db->table('user')->insert($userData);
            $userId = $db->insertID();

            // Tambahkan id_user ke data mahasiswa
            $mahasiswaData['id_user'] = $userId;

            // Cek struktur tabel mahasiswa
            $fields = $db->getFieldData('mahasiswa');
            $fieldNames = [];
            foreach ($fields as $field) {
                $fieldNames[] = $field->name;
            }

            // Tambahkan kolom id_instansi jika belum ada
            if (!in_array('id_instansi', $fieldNames)) {
                $forge = \Config\Database::forge();
                $forge->addColumn('mahasiswa', [
                    'id_instansi' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true,
                        'null' => true,
                        'after' => 'angkatan'
                    ]
                ]);
                log_message('info', 'Kolom id_instansi ditambahkan ke tabel mahasiswa');
            }

            // Tambahkan kolom foto jika belum ada
            if (!in_array('foto', $fieldNames)) {
                $forge = \Config\Database::forge();
                $forge->addColumn('mahasiswa', [
                    'foto' => [
                        'type' => 'VARCHAR',
                        'constraint' => 255,
                        'null' => true,
                        'after' => 'id_instansi'
                    ]
                ]);
                log_message('info', 'Kolom foto ditambahkan ke tabel mahasiswa');
            }

            // Insert ke tabel mahasiswa
            $result = $db->table('mahasiswa')->insert($mahasiswaData);
            
            if (!$result) {
                log_message('error', 'Gagal insert ke tabel mahasiswa: ' . $db->error()['message']);
                $db->transRollback();
                return false;
            }

            $db->transComplete();
            
            return $db->transStatus();
        } catch (\Exception $e) {
            log_message('error', 'Error di tambahMahasiswa: ' . $e->getMessage());
            // Pastikan rollback jika terjadi error
            if (isset($db) && $db->transStatus() === false) {
                $db->transRollback();
            }
            return false;
        }
    }

    public function getAllMahasiswa()
    {
        try {
            $db = \Config\Database::connect();
            $result = $db->table('mahasiswa')
                        ->select('mahasiswa.*, user.username, user.email, instansi.nama_instansi')
                        ->join('user', 'user.id_user = mahasiswa.id_user')
                        ->join('instansi', 'instansi.id_instansi = mahasiswa.id_instansi', 'left')
                        ->get()
                        ->getResultArray();
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error di getAllMahasiswa: ' . $e->getMessage());
            return [];
        }
    }

    public function getAllInstansi()
    {
        try {
            $db = \Config\Database::connect();
            
            // Tambahkan log untuk debugging
            log_message('info', 'Mengambil data instansi dari database');
            
            $query = $db->table('instansi')->get();
            $result = $query->getResultArray();
            
            // Log jumlah data yang ditemukan
            log_message('info', 'Jumlah data instansi: ' . count($result));
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error di getAllInstansi: ' . $e->getMessage());
            return [];
        }
    }

    public function tambahInstansi($data)
    {
        try {
            $db = \Config\Database::connect();
            $result = $db->table('instansi')->insert($data);
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error di tambahInstansi: ' . $e->getMessage());
            return false;
        }
    }

    public function getInstansiById($id_instansi)
    {
        try {
            $db = \Config\Database::connect();
            
            // Cek struktur tabel instansi untuk mendapatkan nama primary key
            $fields = $db->getFieldData('instansi');
            $primaryKey = 'id_instansi'; // Default
            
            foreach ($fields as $field) {
                if ($field->primary_key == 1) {
                    $primaryKey = $field->name;
                    break;
                }
            }
            
            $result = $db->table('instansi')
                        ->where($primaryKey, $id_instansi)
                        ->get()
                        ->getRowArray();
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error di getInstansiById: ' . $e->getMessage());
            return null;
        }
    }

    public function updateInstansi($id_instansi, $data)
    {
        try {
            $db = \Config\Database::connect();
            
            // Cek struktur tabel instansi untuk mendapatkan nama primary key
            $fields = $db->getFieldData('instansi');
            $primaryKey = 'id_instansi'; // Default
            
            foreach ($fields as $field) {
                if ($field->primary_key == 1) {
                    $primaryKey = $field->name;
                    break;
                }
            }
            
            $result = $db->table('instansi')
                        ->where($primaryKey, $id_instansi)
                        ->update($data);
            
            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Error di updateInstansi: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteInstansi($id_instansi)
    {
        try {
            $db = \Config\Database::connect();
            
            // Cek struktur tabel instansi untuk mendapatkan nama primary key
            $fields = $db->getFieldData('instansi');
            $primaryKey = 'id_instansi'; // Default
            
            foreach ($fields as $field) {
                if ($field->primary_key == 1) {
                    $primaryKey = $field->name;
                    break;
                }
            }
            
            // Log untuk debugging
            log_message('info', "Menghapus instansi dengan $primaryKey = $id_instansi");
            
            // Cek apakah instansi digunakan di tabel mahasiswa
            if ($db->tableExists('mahasiswa') && $db->fieldExists('id_instansi', 'mahasiswa')) {
                $mahasiswaCount = $db->table('mahasiswa')
                                    ->where('id_instansi', $id_instansi)
                                    ->countAllResults();
                
                if ($mahasiswaCount > 0) {
                    throw new \Exception('Instansi tidak dapat dihapus karena masih digunakan oleh mahasiswa');
                }
            }
            
            $result = $db->table('instansi')
                        ->where($primaryKey, $id_instansi)
                        ->delete();
            
            if (!$result) {
                throw new \Exception('Gagal menghapus data instansi');
            }
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error di deleteInstansi: ' . $e->getMessage());
            throw $e;
        }
    }
}
