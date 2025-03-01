<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'judul' => 'Home',
            'page' => 'v_home',
        ];

        return view('v_template_frontend', $data);
    }
}
