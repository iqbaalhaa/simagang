<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'     => 'dosen',
            'password' => password_hash('dosen', PASSWORD_DEFAULT), // Hash password
            'email'    => 'dosen@gmail.com', // Ganti dengan email yang sesuai
            'role'     => 'dosen'
        ];

        // Insert data ke tabel user
        $this->db->table('user')->insert($data);
    }
}
