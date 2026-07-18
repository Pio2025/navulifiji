<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LinkHindiSubjects extends Migration
{
    public function up(): void
    {
        $db = $this->db;

        $catRow = $db->table('subject_category')
            ->where('sub_cat_name', 'Hindi')
            ->get()->getRowArray();

        if (!$catRow) {
            return;
        }

        $catId = (int) $catRow['sub_cat_id'];

        $db->query(
            "UPDATE subject SET sub_cat_id_fk = ? WHERE subject_name REGEXP ? AND sub_cat_id_fk = 0",
            [$catId, '^Year [0-9]+ Hindi$']
        );
    }

    public function down(): void
    {
        $db = $this->db;

        $catRow = $db->table('subject_category')
            ->where('sub_cat_name', 'Hindi')
            ->get()->getRowArray();

        if (!$catRow) {
            return;
        }

        $catId = (int) $catRow['sub_cat_id'];

        $db->query(
            "UPDATE subject SET sub_cat_id_fk = 0 WHERE sub_cat_id_fk = ? AND subject_name REGEXP ?",
            [$catId, '^Year [0-9]+ Hindi$']
        );
    }
}
