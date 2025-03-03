<?php

namespace App\Controllers;
use App\Models\ModelMahasiswa;


class Mahasiswa extends BaseController
{
    public function index(): string
    {
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId(session()->get('id_user'));

        $data = [
            'judul' => 'Dashboard Mahasiswa',
            'page' => 'Mahasiswa/v_dashboard',
            'mahasiswa' => $mahasiswaData
        ];

        return view('v_template_backend_mhs', $data);
    }

    public function Profil()
    {
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        $session = session();
        
        // Debug session
        log_message('info', 'Session data: ' . json_encode($session->get()));

        $id_user = $session->get('id_user');
        if (!$id_user) {
            log_message('error', 'Session data: ' . json_encode($session->get()));
            session()->setFlashdata('error', 'Sesi login tidak valid. Silakan login ulang.');
            return redirect()->to(base_url('Auth'));
        }
        
        // Ambil data mahasiswa berdasarkan user_id yang login
        $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId($id_user);
        
        // Jika data mahasiswa belum ada, insert data baru
        if (empty($mahasiswaData) || !isset($mahasiswaData['id_mahasiswa'])) {
            $data = [
                'id_user' => $id_user,
                'nama' => $session->get('username') ?? '',
                'nim' => '', // Tambahkan field yang diperlukan
                'angkatan' => '',
                'instansi' => '',
                'foto' => ''
            ];
            
            try {
                log_message('info', 'Data yang akan diinsert: ' . json_encode($data));
                $id_mahasiswa = $modelMahasiswa->insert($data); // Simpan ID yang baru dibuat
                
                if (!$id_mahasiswa) {
                    throw new \Exception('Gagal membuat data mahasiswa baru');
                }
                
                // Ambil data mahasiswa yang baru dibuat
                $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId($id_user);
                
                if (!$mahasiswaData) {
                    throw new \Exception('Gagal mengambil data mahasiswa setelah insert');
                }
                
                log_message('info', 'Berhasil membuat data mahasiswa baru dengan ID: ' . $id_mahasiswa);
            } catch (\Exception $e) {
                log_message('error', 'Error saat insert mahasiswa: ' . $e->getMessage());
                session()->setFlashdata('error', 'Gagal membuat data mahasiswa. Silakan coba lagi.');
                return redirect()->to(base_url('Auth'));
            }
        }
        
        $data = [
            'judul' => 'Profil mahasiswa',
            'page' => 'mahasiswa/v_profil',
            'mahasiswa' => $mahasiswaData
        ];

        return view('v_template_backend_mhs', $data);
    }

    public function updateProfil()
    {
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        $dataPost = $this->request->getPost();

        // Debugging: Tampilkan semua data yang diterima
        log_message('info', 'Received data: ' . json_encode($dataPost));

        $id_user = $this->request->getPost('id_user');

        if (!$id_user) {
            session()->setFlashdata('error', 'ID User tidak valid.');
            return redirect()->to('Mahasiswa/Profil');
        }

        // Ambil data mahasiswa berdasarkan id_user
        $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId($id_user);
        if (empty($mahasiswaData)) {
            session()->setFlashdata('error', 'Data mahasiswa tidak ditemukan.');
            return redirect()->to('Mahasiswa/Profil');
        }

        // Siapkan data untuk diupdate
        $data = [
            'nama' => $this->request->getPost('nama'),
            'nim' => $this->request->getPost('nim'),
            'angkatan' => $this->request->getPost('angkatan'),
            'instansi' => $this->request->getPost('instansi')
        ];

        // Validasi dan proses foto
        $foto = $this->request->getFile('foto');
        $fotoLama = $this->request->getPost('foto_lama');

        // Periksa apakah ada file foto yang diupload
        if ($foto != null) {
            // Jika ada file yang diupload
            if ($foto->isValid() && !$foto->hasMoved()) {
                // Validasi tipe file
                $type = $foto->getClientMimeType();
                $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
                
                if (!in_array($type, $allowedTypes)) {
                    session()->setFlashdata('error', 'Tipe file tidak didukung. Gunakan PNG, JPEG, atau GIF.');
                    return redirect()->to('Mahasiswa/Profil');
                }

                // Validasi ukuran file (maksimal 2MB)
                if ($foto->getSize() > 2097152) {
                    session()->setFlashdata('error', 'Ukuran file terlalu besar. Maksimal 2MB.');
                    return redirect()->to('Mahasiswa/Profil');
                }

                try {
                    // Generate nama unik untuk file
                    $namaFoto = $foto->getRandomName();
                    
                    // Buat direktori jika belum ada
                    if (!is_dir('foto/mahasiswa')) {
                        mkdir('foto/mahasiswa', 0777, true);
                    }
                    
                    // Pindahkan foto ke folder public/foto/mahasiswa
                    if ($foto->move('foto/mahasiswa', $namaFoto)) {
                        // Jika berhasil upload, hapus foto lama jika ada
                        if ($fotoLama && file_exists('foto/mahasiswa/' . $fotoLama)) {
                            unlink('foto/mahasiswa/' . $fotoLama);
                        }
                        
                        // Tambahkan nama foto baru ke data yang akan diupdate
                        $data['foto'] = $namaFoto;
                        
                        log_message('info', 'Foto berhasil diupload: ' . $namaFoto);
                    } else {
                        log_message('error', 'Gagal memindahkan file foto');
                        session()->setFlashdata('error', 'Gagal mengupload foto. Silakan coba lagi.');
                        return redirect()->to('Mahasiswa/Profil');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error saat upload foto: ' . $e->getMessage());
                    session()->setFlashdata('error', 'Gagal mengupload foto. Silakan coba lagi.');
                    return redirect()->to('Mahasiswa/Profil');
                }
            } else if ($foto->getError() == 4) { // UPLOAD_ERR_NO_FILE
                // Tidak ada file yang diupload, gunakan foto lama
                $data['foto'] = $fotoLama;
            } else {
                // Ada error lain
                log_message('error', 'File foto error: ' . $foto->getErrorString() . '(' . $foto->getError() . ')');
                session()->setFlashdata('error', 'File foto tidak valid. Error: ' . $foto->getErrorString());
                return redirect()->to('Mahasiswa/Profil');
            }
        } else {
            // Tidak ada file yang diupload, gunakan foto lama
            $data['foto'] = $fotoLama;
        }

        // Debugging: Tampilkan data yang akan diupdate
        log_message('info', 'Updating mahasiswa with ID: ' . $mahasiswaData['id_mahasiswa'] . ' with data: ' . json_encode($data));

        // Update profil mahasiswa
        try {
            if ($modelMahasiswa->updateProfil($mahasiswaData['id_mahasiswa'], $data)) {
                session()->setFlashdata('pesan', 'Profil berhasil diupdate');
            } else {
                log_message('error', 'Failed to update mahasiswa with ID: ' . $mahasiswaData['id_mahasiswa']);
                session()->setFlashdata('error', 'Gagal mengupdate profil. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saat update: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal mengupdate profil. Silakan coba lagi.');
        }

        return redirect()->to('Mahasiswa/Profil');
    }

    public function PengajuanMagang()
    {
        $data = [
            'judul' => 'Pengajuan Magang',
            'page' => 'mahasiswa/v_pengajuan_magang',
        ];
        return view('v_template_backend_mhs', $data);
    }
}
