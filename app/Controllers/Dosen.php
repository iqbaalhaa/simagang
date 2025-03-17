<?php

namespace App\Controllers;

use App\Models\ModelDosen;

class Dosen extends BaseController
{
    protected $ModelDosen;

    public function __construct()
    {
        // Inisialisasi model
        $this->ModelDosen = new ModelDosen();
        
        // Cek session login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }
        
        // Cek role
        if (session()->get('level') !== 'dosen') {
            return redirect()->to(base_url('auth'));
        }
    }

    public function index()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));

        $data = [
            'judul' => 'Dashboard Dosen',
            'page' => 'dosen/v_dashboard',
            'dosen' => $dosenData
        ];

        return view('v_template_backend_dosen', $data);
    }

    public function Profil()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));

        // Jika data dosen belum ada, insert data baru
        if (empty($dosenData) || !isset($dosenData['id_dosen'])) {
            $data = [
                'id_user' => session()->get('id_user'),
                'nama' => session()->get('username'),
                'nidn' => 0 // Set default NIDN ke 0
            ];
            
            try {
                $modelDosen->insert($data);
                $dosenData = $modelDosen->getDosenByUserId(session()->get('id_user'));
                session()->setFlashdata('success', 'Data profil berhasil dibuat, silakan lengkapi data Anda.');
            } catch (\Exception $e) {
                log_message('error', 'Gagal membuat data dosen: ' . $e->getMessage());
                session()->setFlashdata('error', 'Gagal membuat data dosen.');
                return redirect()->to(base_url('Dosen'));
            }
        }
        
        $data = [
            'judul' => 'Profil Dosen',
            'page' => 'dosen/v_profil',
            'dosen' => $dosenData
        ];

        return view('v_template_backend_dosen', $data);
    }

    public function updateProfil()
    {
        $modelDosen = new \App\Models\ModelDosen();
        $id_user = session()->get('id_user');
        
        // Ambil data dosen berdasarkan id_user
        $dosenData = $modelDosen->getDosenByUserId($id_user);
        if (empty($dosenData)) {
            session()->setFlashdata('error', 'Data dosen tidak ditemukan.');
            return redirect()->to('Dosen/Profil');
        }

        // Validasi NIDN
        if (empty($this->request->getPost('nidn'))) {
            session()->setFlashdata('error', 'NIDN wajib diisi.');
            return redirect()->to('Dosen/Profil');
        }

        // Siapkan data untuk diupdate
        $data = [
            'nama' => $this->request->getPost('nama'),
            'nidn' => $this->request->getPost('nidn')
        ];

        // Validasi dan proses foto
        $foto = $this->request->getFile('foto');
        $fotoLama = $this->request->getPost('foto_lama');

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $type = $foto->getClientMimeType();
                $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
                
                if (!in_array($type, $allowedTypes)) {
                    session()->setFlashdata('error', 'Tipe file tidak didukung. Gunakan PNG, JPEG, atau GIF.');
                return redirect()->to('Dosen/Profil');
                }

                if ($foto->getSize() > 2097152) {
                    session()->setFlashdata('error', 'Ukuran file terlalu besar. Maksimal 2MB.');
                return redirect()->to('Dosen/Profil');
                }

                try {
                    $namaFoto = $foto->getRandomName();
                    
                if (!is_dir('foto/dosen')) {
                    mkdir('foto/dosen', 0777, true);
                }
                
                if ($foto->move('foto/dosen', $namaFoto)) {
                    if ($fotoLama && file_exists('foto/dosen/' . $fotoLama)) {
                        unlink('foto/dosen/' . $fotoLama);
                    }
                    $data['foto'] = $namaFoto;
                }
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Gagal mengupload foto. ' . $e->getMessage());
                return redirect()->to('Dosen/Profil');
            }
        }

        try {
            if ($modelDosen->updateProfil($dosenData['id_dosen'], $data)) {
                session()->setFlashdata('success', 'Profil berhasil diupdate');
            } else {
                throw new \Exception('Gagal mengupdate profil dosen');
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal mengupdate profil. ' . $e->getMessage());
        }

        return redirect()->to('Dosen/Profil');
    }
}
