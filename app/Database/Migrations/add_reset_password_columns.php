<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetPasswordColumns extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'reset_expired' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'reset_token');
        $this->forge->dropColumn('users', 'reset_expired');
    }
} 