<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedBasicTechnologyCategoryAndLinkSubjects extends Migration
{
    public function up(): void
    {
        $db    = $this->db;
        $today = date('Y-m-d');

        $existing = $db->table('subject_category')
            ->where('sub_cat_name', 'Basic Technology')
            ->get()->getRowArray();

        if (!$existing) {
            $db->table('subject_category')->insert([
                'sub_cat_name'   => 'Basic Technology',
                'sub_cat_status' => 1,
                'created_date'   => $today,
                'updated_date'   => $today,
            ]);
        }

        $catRow = $db->table('subject_category')
            ->where('sub_cat_name', 'Basic Technology')
            ->get()->getRowArray();

        $catId = (int) $catRow['sub_cat_id'];

        $db->query(
            "UPDATE subject SET sub_cat_id_fk = ? WHERE subject_name REGEXP ? AND sub_cat_id_fk = 0",
            [$catId, '^Year [0-9]+ Basic Technology$']
        );
    }

    public function down(): void
    {
        $db = $this->db;

        $cat = $db->table('subject_category')
            ->where('sub_cat_name', 'Basic Technology')
            ->get()->getRowArray();

        if (!$cat) {
            return;
        }

        $catId = (int) $cat['sub_cat_id'];

        $db->query(
            "UPDATE subject SET sub_cat_id_fk = 0 WHERE sub_cat_id_fk = ? AND subject_name REGEXP ?",
            [$catId, '^Year [0-9]+ Basic Technology$']
        );

        $inUse = $db->table('subject')->where('sub_cat_id_fk', $catId)->countAllResults();
        if ($inUse === 0) {
            $db->table('subject_category')->where('sub_cat_id', $catId)->delete();
        }
    }
}
