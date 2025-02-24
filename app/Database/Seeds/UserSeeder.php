<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'     => 'admin',
            'password' => password_hash('admin', PASSWORD_DEFAULT), // Hash password
            'email'    => 'admin@gmail.com', // Ganti dengan email yang sesuai
            'role'     => 'admin'
        ];

        // Insert data ke tabel user
        $this->db->table('user')->insert($data);
    }
}
