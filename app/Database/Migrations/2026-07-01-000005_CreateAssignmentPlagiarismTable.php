<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssignmentPlagiarismTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'plagiarism_id'     => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'submission_id_fk'  => ['type' => 'INT', 'unsigned' => false, 'null' => false],
            'scan_id'           => ['type' => 'VARCHAR', 'constraint' => 128, 'null' => false],
            'status'            => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
            'score'             => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'default' => null],
            'identical_pct'     => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'default' => null],
            'minor_changed_pct' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'default' => null],
            'paraphrased_pct'   => ['type' => 'DECIMAL', 'constraint' => '5,2', 'null' => true, 'default' => null],
            'sources_json'      => ['type' => 'MEDIUMTEXT', 'null' => true, 'default' => null],
            'webhook_raw'       => ['type' => 'MEDIUMTEXT', 'null' => true, 'default' => null],
            'error_message'     => ['type' => 'VARCHAR', 'constraint' => 1000, 'null' => true, 'default' => null],
            'submitted_at'      => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'completed_at'      => ['type' => 'DATETIME', 'null' => true, 'default' => null],
            'created_at'        => ['type' => 'DATETIME', 'null' => false],
        ]);
        $this->forge->addPrimaryKey('plagiarism_id');
        $this->forge->createTable('assignment_plagiarism', true, [
            'ENGINE'         => 'MyISAM',
            'CHARACTER SET'  => 'utf8mb4',
            'COLLATE'        => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down(): void
    {
        $this->forge->dropTable('assignment_plagiarism', true);
    }
}
