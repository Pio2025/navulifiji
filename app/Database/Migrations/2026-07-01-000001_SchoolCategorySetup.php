<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SchoolCategorySetup extends Migration
{
    public function up()
    {
        // 1. Create school_category_config table if it doesn't exist
        if (!$this->db->tableExists('school_category_config')) {
            $this->forge->addField([
                'sch_cat_con_id'        => ['type' => 'INT', 'auto_increment' => true],
                'sch_cat_id_fk'         => ['type' => 'INT', 'null' => false],
                'num_of_term_in_year'   => ['type' => 'INT', 'null' => false],
                'label_for_term'        => ['type' => 'VARCHAR', 'constraint' => 60, 'null' => false, 'default' => 'Term'],
                'num_of_week_in_a_term' => ['type' => 'INT', 'null' => true],
            ]);
            $this->forge->addPrimaryKey('sch_cat_con_id');
            $this->forge->createTable('school_category_config', true, ['ENGINE' => 'MyISAM']);
        }

        // 2. Fix sch_cat_term_entry.term_end_date from INT to DATE
        $this->forge->modifyColumn('sch_cat_term_entry', [
            'term_end_date' => ['type' => 'DATE', 'null' => false],
        ]);

        // 3. Insert permissions for School Category (only if not already seeded)
        $existing = $this->db->table('permission')
            ->whereIn('perm_code', ['_add_school_category', '_school_category_listing'])
            ->countAllResults();

        if ($existing === 0) {
            $now = date('Y-m-d');
            $this->db->table('permission')->insertBatch([
                [
                    'module_id_fk'    => 4,
                    'perm_name'       => 'Add School Category',
                    'perm_desc'       => 'Allows user to add a new school category with configuration and term entries',
                    'perm_controller' => 'school/category/add',
                    'perm_code'       => '_add_school_category',
                    'show_in_nav'     => 1,
                    'perm_status'     => 'Active',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'module_id_fk'    => 4,
                    'perm_name'       => 'School Category Listing',
                    'perm_desc'       => 'Allows user to view the list of school categories and their configurations',
                    'perm_controller' => 'school/category',
                    'perm_code'       => '_school_category_listing',
                    'show_in_nav'     => 1,
                    'perm_status'     => 'Active',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
            ]);
        }
    }

    public function down()
    {
        $this->db->table('permission')
            ->whereIn('perm_code', ['_add_school_category', '_school_category_listing'])
            ->delete();

        $this->forge->dropTable('school_category_config', true);

        // Revert term_end_date back to INT (best-effort rollback)
        $this->forge->modifyColumn('sch_cat_term_entry', [
            'term_end_date' => ['type' => 'INT', 'null' => false],
        ]);
    }
}
