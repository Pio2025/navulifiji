<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedPEIsFunCategoryAndLinkSubjects extends Migration
{
    public function up(): void
    {
        $db    = $this->db;
        $today = date('Y-m-d');

        $existing = $db->table('subject_category')
            ->where('sub_cat_name', 'PE Is Fun')
            ->get()->getRowArray();

        if (!$existing) {
            $db->table('subject_category')->insert([
                'sub_cat_name'   => 'PE Is Fun',
                'sub_cat_status' => 1,
                'created_date'   => $today,
                'updated_date'   => $today,
            ]);
        }

        $catRow = $db->table('subject_category')
            ->where('sub_cat_name', 'PE Is Fun')
            ->get()->getRowArray();

        $catId = (int) $catRow['sub_cat_id'];

        $db->query(
            "UPDATE subject SET sub_cat_id_fk = ? WHERE subject_name REGEXP ? AND sub_cat_id_fk = 0",
            [$catId, '^PE Is Fun [0-9]+$']
        );
    }

    public function down(): void
    {
        $db = $this->db;

        $cat = $db->table('subject_category')
            ->where('sub_cat_name', 'PE Is Fun')
            ->get()->getRowArray();

        if (!$cat) {
            return;
        }

        $catId = (int) $cat['sub_cat_id'];

        $db->query(
            "UPDATE subject SET sub_cat_id_fk = 0 WHERE sub_cat_id_fk = ? AND subject_name REGEXP ?",
            [$catId, '^PE Is Fun [0-9]+$']
        );

        $inUse = $db->table('subject')->where('sub_cat_id_fk', $catId)->countAllResults();
        if ($inUse === 0) {
            $db->table('subject_category')->where('sub_cat_id', $catId)->delete();
        }
    }
}
