<?php

namespace App\Controllers;
use App\Models\ModelMahasiswa;


class Mahasiswa extends BaseController
{
    protected $ModelMahasiswa;

    public function __construct()
    {
        // Inisialisasi model
        $this->ModelMahasiswa = new ModelMahasiswa();
        
        // Cek session login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('auth'));
        }
        
        // Cek role
        if (session()->get('level') !== 'mahasiswa') {
            return redirect()->to(base_url('auth'));
        }
    }

    public function index()
    {
        $id_mahasiswa = $this->ModelMahasiswa->getMahasiswaByUserId(session()->get('id_user'))['id_mahasiswa'];
        
        $data = [
            'judul' => 'Dashboard Mahasiswa',
            'mahasiswa' => $this->ModelMahasiswa->getMahasiswaByUserId(session()->get('id_user')),
            'total_bimbingan' => $this->ModelMahasiswa->getTotalBimbingan($id_mahasiswa),
            'bimbingan_selesai' => $this->ModelMahasiswa->getBimbinganSelesai($id_mahasiswa),
            'bimbingan_pending' => $this->ModelMahasiswa->getBimbinganPending($id_mahasiswa),
            'nama_dosen' => $this->ModelMahasiswa->getDosenPembimbing($id_mahasiswa),
            'riwayat_bimbingan' => $this->ModelMahasiswa->getRiwayatBimbingan($id_mahasiswa, 5), // Ambil 5 data terakhir
            'page' => 'mahasiswa/v_dashboard'
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
                'nim' => null, // Biarkan NIM null dulu
                'angkatan' => '',
                'instansi' => '',
                'foto' => ''
            ];
            
            try {
                log_message('info', 'Data yang akan diinsert: ' . json_encode($data));
                $id_mahasiswa = $modelMahasiswa->insert($data);
                
                if (!$id_mahasiswa) {
                    throw new \Exception('Gagal membuat data mahasiswa baru');
                }
                
                // Ambil data mahasiswa yang baru dibuat
                $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId($id_user);
                
                if (!$mahasiswaData) {
                    throw new \Exception('Gagal mengambil data mahasiswa setelah insert');
                }
                
                // Set flash message untuk mengingatkan user mengisi data
                session()->setFlashdata('warning', 'Silakan lengkapi data profil Anda termasuk NIM untuk dapat menggunakan semua fitur.');
                
                log_message('info', 'Berhasil membuat data mahasiswa baru dengan ID: ' . $id_mahasiswa);
            } catch (\Exception $e) {
                log_message('error', 'Error saat insert mahasiswa: ' . $e->getMessage());
                session()->setFlashdata('error', 'Gagal membuat data mahasiswa. Silakan coba lagi.');
                return redirect()->to('Mahasiswa/Profil');
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

        // Tambahkan validasi NIM
        if (empty($this->request->getPost('nim'))) {
            session()->setFlashdata('error', 'NIM wajib diisi untuk dapat menggunakan sistem.');
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
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId(session()->get('id_user'));

        // Ambil data kelompok magang jika ada
        $kelompokMagang = $modelMahasiswa->getKelompokMagang($mahasiswaData['id_mahasiswa']);
        
        // Ambil data instansi
        $instansi = $modelMahasiswa->getAllInstansi();
        
        // Ambil data mahasiswa yang tersedia untuk anggota
        $mahasiswa_tersedia = $modelMahasiswa->getMahasiswaTersedia();

        $data = [
            'judul' => 'Pengajuan Magang',
            'page' => 'mahasiswa/v_pengajuan_magang',
            'mahasiswa' => $mahasiswaData,
            'kelompok' => $kelompokMagang,
            'instansi' => $instansi,
            'mahasiswa_tersedia' => $mahasiswa_tersedia
        ];
        return view('v_template_backend_mhs', $data);
    }

    public function tambahPengajuanMagang()
    {
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId(session()->get('id_user'));

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_kelompok' => 'required',
            'instansi_id' => 'required|numeric',
            'anggota.*' => 'permit_empty|numeric',
            'surat_permohonan' => [
                'rules' => 'uploaded[surat_permohonan]|mime_in[surat_permohonan,application/pdf]|max_size[surat_permohonan,2048]',
                'errors' => [
                    'uploaded' => 'Surat permohonan wajib diupload',
                    'mime_in' => 'File harus berformat PDF',
                    'max_size' => 'Ukuran file maksimal 2MB'
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('error', $validation->listErrors());
            return redirect()->to('Mahasiswa/PengajuanMagang');
        }

        // Handle upload surat permohonan
        $fileSurat = $this->request->getFile('surat_permohonan');
        $namaSurat = '';
        
        if ($fileSurat->isValid() && !$fileSurat->hasMoved()) {
            // Generate nama unik untuk file
            $namaSurat = $fileSurat->getRandomName();
            
            // Buat direktori jika belum ada
            if (!is_dir('uploads/surat_permohonan')) {
                mkdir('uploads/surat_permohonan', 0777, true);
            }
            
            // Pindahkan file
            try {
                $fileSurat->move('uploads/surat_permohonan', $namaSurat);
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Gagal mengupload surat permohonan');
                return redirect()->to('Mahasiswa/PengajuanMagang');
            }
        }

        // Siapkan data pengajuan
        $data = [
            'nama_kelompok' => $this->request->getPost('nama_kelompok'),
            'ketua_id' => $mahasiswaData['id_mahasiswa'],
            'instansi_id' => $this->request->getPost('instansi_id'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'surat_permohonan' => $namaSurat
        ];

        try {
            // Mulai transaksi
            $db = \Config\Database::connect();
            $db->transStart();

            // Insert data pengajuan
            $pengajuan_id = $modelMahasiswa->insertPengajuanMagang($data);

            // Insert anggota kelompok
            $anggota = $this->request->getPost('anggota');
            if (!empty($anggota)) {
                foreach ($anggota as $mahasiswa_id) {
                    $modelMahasiswa->insertAnggotaKelompok([
                        'pengajuan_id' => $pengajuan_id,
                        'mahasiswa_id' => $mahasiswa_id
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                // Jika transaksi gagal, hapus file yang sudah diupload
                if (file_exists('uploads/surat_permohonan/' . $namaSurat)) {
                    unlink('uploads/surat_permohonan/' . $namaSurat);
                }
                throw new \Exception('Gagal menyimpan pengajuan magang');
            }

            session()->setFlashdata('pesan', 'Pengajuan magang berhasil ditambahkan');
            return redirect()->to('Mahasiswa/PengajuanMagang');

        } catch (\Exception $e) {
            // Jika terjadi error, hapus file yang sudah diupload
            if (file_exists('uploads/surat_permohonan/' . $namaSurat)) {
                unlink('uploads/surat_permohonan/' . $namaSurat);
            }
            
            log_message('error', 'Error saat menambah pengajuan magang: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menambah pengajuan magang. Silakan coba lagi.');
            return redirect()->to('Mahasiswa/PengajuanMagang');
        }
    }

    public function hapusPengajuanMagang()
    {
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        $id = $this->request->getPost('id');
        
        // Ambil data pengajuan
        $pengajuan = $modelMahasiswa->getPengajuanById($id);
        
        // Cek apakah user yang login adalah ketua kelompok
        if (!$pengajuan || $pengajuan['ketua_id'] != $modelMahasiswa->getMahasiswaByUserId(session()->get('id_user'))['id_mahasiswa']) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses untuk menghapus pengajuan ini.');
            return redirect()->to('Mahasiswa/PengajuanMagang');
        }
        
        // Cek status pengajuan
        if ($pengajuan['status'] != 'pending') {
            session()->setFlashdata('error', 'Hanya pengajuan dengan status pending yang dapat dihapus.');
            return redirect()->to('Mahasiswa/PengajuanMagang');
        }

        try {
            // Mulai transaksi
            $db = \Config\Database::connect();
            $db->transStart();

            // Hapus anggota kelompok terlebih dahulu
            $modelMahasiswa->deleteAnggotaKelompok($id);
            
            // Hapus pengajuan
            $modelMahasiswa->deletePengajuan($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menghapus pengajuan magang');
            }

            session()->setFlashdata('pesan', 'Pengajuan magang berhasil dihapus');
            
        } catch (\Exception $e) {
            log_message('error', 'Error saat menghapus pengajuan magang: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus pengajuan magang. Silakan coba lagi.');
        }

        return redirect()->to('Mahasiswa/PengajuanMagang');
    }

    public function DownloadDokumen()
    {
        $modelAdmin = new \App\Models\ModelAdmin();
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        try {
            // Ambil data mahasiswa yang login
            $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId(session()->get('id_user'));
            // Ambil semua dokumen yang aktif
            $dokumenData = $modelAdmin->getAllDokumen();
            
            $data = [
                'judul' => 'Download Dokumen',
                'page' => 'mahasiswa/v_download_dokumen',
                'mahasiswa' => $mahasiswaData,
                'dokumen' => $dokumenData
            ];

            return view('v_template_backend_mhs', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error di DownloadDokumen: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data');
            return redirect()->back();
        }
    }

    public function downloadFile($id)
    {
        try {
            $modelAdmin = new \App\Models\ModelAdmin();
            $dokumen = $modelAdmin->getDokumenById($id);
            
            if (!$dokumen) {
                throw new \Exception('Dokumen tidak ditemukan');
            }

            $path = 'uploads/dokumen/' . $dokumen['file_dokumen'];
            
            if (!file_exists($path)) {
                throw new \Exception('File tidak ditemukan');
            }

            return $this->response->download($path, null)
                ->setFileName($dokumen['nama_dokumen'] . '_' . date('Ymd') . '.' . pathinfo($dokumen['file_dokumen'], PATHINFO_EXTENSION));
                
        } catch (\Exception $e) {
            log_message('error', 'Error di downloadFile: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal mengunduh file');
            return redirect()->back();
        }
    }

    public function Absensi()
    {
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId(session()->get('id_user'));

        $data = [
            'judul' => 'Absensi Magang',
            'page' => 'mahasiswa/v_absensi',
            'mahasiswa' => $mahasiswaData,
            'absensi' => $modelMahasiswa->getAbsensiMahasiswa($mahasiswaData['id_mahasiswa'])
        ];

        return view('v_template_backend_mhs', $data);
    }

    public function tambahAbsensi()
    {
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        $mahasiswaData = $modelMahasiswa->getMahasiswaByUserId(session()->get('id_user'));

        // Validasi input
        $validation = \Config\Services::validation();
        $rules = [
            'status' => 'required|in_list[hadir,izin,sakit]',
            'kegiatan' => 'required'
        ];

        // Tambahkan validasi file hanya jika status izin atau sakit
        if ($this->request->getPost('status') == 'izin' || $this->request->getPost('status') == 'sakit') {
            $rules['bukti_kehadiran'] = 'uploaded[bukti_kehadiran]|mime_in[bukti_kehadiran,image/jpg,image/jpeg,image/png]|max_size[bukti_kehadiran,2048]';
        } else {
            // Untuk status hadir, file bukti opsional
            $rules['bukti_kehadiran'] = 'permit_empty|mime_in[bukti_kehadiran,image/jpg,image/jpeg,image/png]|max_size[bukti_kehadiran,2048]';
        }

        $validation->setRules($rules, [
            'status' => [
                'required' => 'Status kehadiran wajib dipilih',
                'in_list' => 'Status kehadiran tidak valid'
            ],
            'kegiatan' => [
                'required' => 'Kegiatan wajib diisi'
            ],
            'bukti_kehadiran' => [
                'uploaded' => 'Bukti kehadiran wajib diupload untuk izin/sakit',
                'mime_in' => 'File harus berupa gambar (JPG/PNG)',
                'max_size' => 'Ukuran file maksimal 2MB'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            session()->setFlashdata('error', $validation->listErrors());
            return redirect()->to('Mahasiswa/Absensi');
        }

        // Handle upload bukti kehadiran
        $bukti = '';
        $fileBukti = $this->request->getFile('bukti_kehadiran');
        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $bukti = $fileBukti->getRandomName();
            try {
                // Buat direktori jika belum ada
                if (!is_dir('uploads/absensi')) {
                    mkdir('uploads/absensi', 0777, true);
                }
                $fileBukti->move('uploads/absensi', $bukti);
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Gagal mengupload bukti kehadiran');
                return redirect()->to('Mahasiswa/Absensi');
            }
        }

        $data = [
            'id_mahasiswa' => $mahasiswaData['id_mahasiswa'],
            'tanggal' => date('Y-m-d'),
            'jam_masuk' => date('H:i:s'),
            'kegiatan' => $this->request->getPost('kegiatan'),
            'status' => $this->request->getPost('status'),
            'bukti_kehadiran' => $bukti,
            'created_at' => date('Y-m-d H:i:s')
        ];

        try {
            $modelMahasiswa->insertAbsensi($data);
            session()->setFlashdata('pesan', 'Absensi berhasil ditambahkan');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal menambahkan absensi');
            if (!empty($bukti) && file_exists('uploads/absensi/' . $bukti)) {
                unlink('uploads/absensi/' . $bukti);
            }
        }

        return redirect()->to('Mahasiswa/Absensi');
    }

    public function absenPulang($id)
    {
        $modelMahasiswa = new \App\Models\ModelMahasiswa();
        
        try {
            $data = [
                'jam_pulang' => date('H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $modelMahasiswa->updateAbsensi($id, $data);
            session()->setFlashdata('pesan', 'Absen pulang berhasil');
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal melakukan absen pulang');
        }

        return redirect()->to('Mahasiswa/Absensi');
    }
}
