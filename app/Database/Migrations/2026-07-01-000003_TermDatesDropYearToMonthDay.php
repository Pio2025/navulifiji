<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TermDatesDropYearToMonthDay extends Migration
{
    public function up()
    {
        // Drop the full-date columns (table is empty — no data loss)
        $this->forge->dropColumn('sch_cat_term_entry', 'term_start_date');
        $this->forge->dropColumn('sch_cat_term_entry', 'term_end_date');

        // Add day + month pairs for start and end
        $this->forge->addColumn('sch_cat_term_entry', [
            'term_start_day'   => ['type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'after' => 'term_num'],
            'term_start_month' => ['type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'after' => 'term_start_day'],
            'term_end_day'     => ['type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'after' => 'term_start_month'],
            'term_end_month'   => ['type' => 'TINYINT', 'unsigned' => true, 'null' => false, 'after' => 'term_end_day'],
        ]);
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
