<?php

namespace App\Controllers;

class Dosen extends BaseController
{
    public function index(): string
    {
        $data = [
            'judul' => 'Dashboard Dosen',
            'page' => 'dosen/v_dashboard',
        ];

        return view('v_template_backend_dosen', $data);
    }
}
