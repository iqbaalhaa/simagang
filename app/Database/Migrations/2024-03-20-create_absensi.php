<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAbsensi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_absensi' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_mahasiswa' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'tanggal' => [
                'type' => 'DATE'
            ],
            'jam_masuk' => [
                'type' => 'TIME',
                'null' => true
            ],
            'jam_pulang' => [
                'type' => 'TIME',
                'null' => true
            ],
            'kegiatan' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'bukti_kehadiran' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['hadir', 'izin', 'sakit'],
                'default' => 'hadir'
            ],
            'created_at' => [
                'type' => 'DATETIME'
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        $this->forge->addKey('id_absensi', true);
        $this->forge->addForeignKey('id_mahasiswa', 'mahasiswa', 'id_mahasiswa', 'CASCADE', 'CASCADE');
        $this->forge->createTable('absensi');
    }

    public function down()
    {
        $this->forge->dropTable('absensi');
    }
} 