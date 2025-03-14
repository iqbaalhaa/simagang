<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'     => 'admin02',
            'password' => password_hash('admin02', PASSWORD_DEFAULT), // Hash password
            'email'    => 'admin02@gmail.com', // Ganti dengan email yang sesuai
            'role'     => 'admin'
        ];

        // Insert data ke tabel user
        $this->db->table('user')->insert($data);
    }
}
