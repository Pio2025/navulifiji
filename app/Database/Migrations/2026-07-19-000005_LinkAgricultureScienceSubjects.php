<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LinkAgricultureScienceSubjects extends Migration
{
    public function up(): void
    {
        $db = $this->db;

        $catRow = $db->table('subject_category')
            ->where('sub_cat_name', 'Agriculture Science')
            ->get()->getRowArray();

        if (!$catRow) {
            return;
        }

        $catId = (int) $catRow['sub_cat_id'];

        // Subject names carry the "Argriculture" typo from the original data
        $db->query(
            "UPDATE subject SET sub_cat_id_fk = ? WHERE subject_name REGEXP ? AND sub_cat_id_fk = 0",
            [$catId, '^Year [0-9]+ Argriculture Science$']
        );
    }

    public function down(): void
    {
        $db = $this->db;

        $catRow = $db->table('subject_category')
            ->where('sub_cat_name', 'Agriculture Science')
            ->get()->getRowArray();

        if (!$catRow) {
            return;
        }

        $catId = (int) $catRow['sub_cat_id'];

        $db->query(
            "UPDATE subject SET sub_cat_id_fk = 0 WHERE sub_cat_id_fk = ? AND subject_name REGEXP ?",
            [$catId, '^Year [0-9]+ Argriculture Science$']
        );
    }
}
