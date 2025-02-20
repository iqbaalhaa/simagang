<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index(): string
    {
        $data = [
            'judul' => 'Home',
            'page' => 'admin/v_dashboard',
        ];

        return view('v_template_backend', $data);
    }
}
