<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSuratPengantar extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pengajuan_magang', [
            'surat_pengantar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'surat_permohonan'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pengajuan_magang', 'surat_pengantar');
    }
} 