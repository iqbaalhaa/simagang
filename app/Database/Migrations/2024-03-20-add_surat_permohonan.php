<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSuratPermohonan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pengajuan_magang', [
            'surat_permohonan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'status'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pengajuan_magang', 'surat_permohonan');
    }
} 