<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TermDatesDropYearToMonthDay extends Migration
{
    public function up()
    {
        $cols = $this->db->getFieldNames('sch_cat_term_entry');

        // Drop the full-date columns only if they still exist
        if (in_array('term_start_date', $cols)) {
            $this->forge->dropColumn('sch_cat_term_entry', 'term_start_date');
        }
        if (in_array('term_end_date', $cols)) {
            $this->forge->dropColumn('sch_cat_term_entry', 'term_end_date');
        }

        // Add day + month pairs only if they don't already exist
        $toAdd = [];
        if (!in_array('term_start_day', $cols)) {
            $toAdd['term_start_day']   = ['type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'after' => 'term_num'];
        }
        if (!in_array('term_start_month', $cols)) {
            $toAdd['term_start_month'] = ['type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'after' => 'term_start_day'];
        }
        if (!in_array('term_end_day', $cols)) {
            $toAdd['term_end_day']     = ['type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'after' => 'term_start_month'];
        }
        if (!in_array('term_end_month', $cols)) {
            $toAdd['term_end_month']   = ['type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'after' => 'term_end_day'];
        }
        if (!empty($toAdd)) {
            $this->forge->addColumn('sch_cat_term_entry', $toAdd);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('sch_cat_term_entry', 'term_start_day');
        $this->forge->dropColumn('sch_cat_term_entry', 'term_start_month');
        $this->forge->dropColumn('sch_cat_term_entry', 'term_end_day');
        $this->forge->dropColumn('sch_cat_term_entry', 'term_end_month');

        $this->forge->addColumn('sch_cat_term_entry', [
            'term_start_date' => ['type' => 'DATE', 'null' => false],
            'term_end_date'   => ['type' => 'DATE', 'null' => false],
        ]);
    }
}
