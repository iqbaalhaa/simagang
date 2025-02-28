<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index(): string
    {
        $data = [
            'judul' => 'Dashboard Admin',
            'page' => 'admin/v_dashboard',
        ];

        return view('v_template_backend', $data);
    }

    public function PengajuanMahasiswa()
    {
        $data = [
            'judul' => 'Pengajuan Mahasiswa',
            'page' => 'admin/v_pengajuan_mahasiswa',
        ];

        return view('v_template_backend', $data);
    }

    public function DataAdmin()
    {
        $data = [
            'judul' => 'Data Admin',
            'page' => 'admin/v_data_admin',
        ];

        return view('v_template_backend', $data);
    }

    public function DataDosen()
    {
        $data = [
            'judul' => 'Data Dosen',
            'page' => 'admin/v_data_dosen',
        ];

        return view('v_template_backend', $data);
    }
    public function DataMahasiswa()
    {
        $data = [
            'judul' => 'Data Mahasiswa',
            'page' => 'admin/v_data_mahasiswa',
        ];

        return view('v_template_backend', $data);
    }
}
