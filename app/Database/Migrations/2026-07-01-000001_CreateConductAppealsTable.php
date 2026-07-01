<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConductAppealsTable extends Migration
{
    public function up(): void
    {
        if (!in_array('conduct_appeals', $this->db->listTables())) {
            $this->forge->addField([
                'appeal_id'       => ['type' => 'INT', 'auto_increment' => true],
                'incident_id'     => ['type' => 'INT', 'null' => true],
                'student_id'      => ['type' => 'INT', 'null' => true],
                'appeal_reason'   => ['type' => 'TEXT', 'null' => true],
                'appeal_status'   => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => 'Pending',
                ],
                'submitted_date'  => ['type' => 'TIMESTAMP', 'null' => true, 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
                'reviewed_by'     => ['type' => 'INT', 'null' => true],
                'reviewed_date'   => ['type' => 'DATETIME', 'null' => true],
                'review_notes'    => ['type' => 'TEXT', 'null' => true],
                'points_restored' => ['type' => 'INT', 'null' => true, 'default' => 0],
            ]);
            $this->forge->addPrimaryKey('appeal_id');
            $this->forge->addKey('incident_id');
            $this->forge->addKey('student_id');
            $this->forge->createTable('conduct_appeals', true, ['ENGINE' => 'MyISAM']);
        }

        $exists = $this->db->table('permission')->where('perm_code', '_process_conduct_appeal')->countAllResults();
        if ($exists === 0) {
            $this->db->table('permission')->insert([
                'module_id_fk'    => 10,
                'perm_name'       => 'Process Conduct Appeals',
                'perm_desc'       => 'Review and process student conduct appeals',
                'perm_controller' => 'conduct/appeals',
                'perm_code'       => '_process_conduct_appeal',
                'show_in_nav'     => 1,
                'perm_status'     => 'Active',
                'created_at'      => null,
                'updated_at'      => null,
            ]);
        }
    }

    public function down(): void
    {
        $this->db->table('permission')->where('perm_code', '_process_conduct_appeal')->delete();
        $this->forge->dropTable('conduct_appeals', true);
    }
}
