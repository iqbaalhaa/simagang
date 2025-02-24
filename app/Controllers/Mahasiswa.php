<?php

namespace App\Controllers;

class Mahasiswa extends BaseController
{
    public function index(): string
    {
        $data = [
            'judul' => 'Dashboard Mahasiswa',
            'page' => 'Mahasiswa/v_dashboard',
        ];

        return view('v_template_backend_mhs', $data);
    }
}
