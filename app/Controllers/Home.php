<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelMahasiswa;
use App\Models\ModelAdmin;
use App\Models\ModelDosen;
use App\Models\ModelInstansi;
use App\Models\ModelPengajuan;

class Home extends BaseController
{
    protected $ModelMahasiswa;
    protected $ModelDosen;
    protected $ModelInstansi;
    protected $ModelPengajuan;

    public function __construct()
    {
        $this->ModelMahasiswa = new ModelMahasiswa();
        $this->ModelDosen = new ModelDosen();
        $this->ModelInstansi = new ModelInstansi();
        $this->ModelPengajuan = new ModelPengajuan();
    }

    public function index()
    {
        // Ambil data statistik
        $total_mahasiswa = $this->ModelMahasiswa->countAllResults();
        $total_dosen = $this->ModelDosen->countAllResults();
        $total_instansi = $this->ModelInstansi->countAllResults();
        
        // Ambil total pengajuan yang statusnya disetujui
        $db = \Config\Database::connect();
        $total_pengajuan = $db->table('pengajuan_magang')
                             ->where('status', 'disetujui')
                             ->countAllResults();

        $data = [
            'judul' => 'Home',
            'page' => 'v_home',
            'total_mahasiswa' => $total_mahasiswa,
            'total_dosen' => $total_dosen,
            'total_instansi' => $total_instansi,
            'total_pengajuan' => $total_pengajuan
        ];

        return view('v_template_frontend', $data);
    }
}
