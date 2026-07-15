<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TermWeekPerEntry extends Migration
{
    public function up()
    {
        // Remove columns from config only if they still exist
        $configCols = $this->db->getFieldNames('school_category_config');
        if (in_array('num_of_term_in_year', $configCols)) {
            $this->forge->dropColumn('school_category_config', 'num_of_term_in_year');
        }
        if (in_array('num_of_week_in_a_term', $configCols)) {
            $this->forge->dropColumn('school_category_config', 'num_of_week_in_a_term');
        }

        // Add per-term week count only if not already present
        $termCols = $this->db->getFieldNames('sch_cat_term_entry');
        if (!in_array('num_of_week', $termCols)) {
            $this->forge->addColumn('sch_cat_term_entry', [
                'num_of_week' => [
                    'type'     => 'TINYINT',
                    'unsigned' => true,
                    'null'     => false,
                    'default'  => 0,
                    'after'    => 'term_num',
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('sch_cat_term_entry', 'num_of_week');

        $this->forge->addColumn('school_category_config', [
            'num_of_term_in_year'   => ['type' => 'INT', 'null' => false, 'default' => 0],
            'num_of_week_in_a_term' => ['type' => 'INT', 'null' => false, 'default' => 0],
        ]);
    }
}
