<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedSpiritualMoralDevelopmentCategoryAndLinkSubjects extends Migration
{
    public function up(): void
    {
        $db    = $this->db;
        $today = date('Y-m-d');

        $existing = $db->table('subject_category')
            ->where('sub_cat_name', 'Spiritual and Moral Development')
            ->get()->getRowArray();

        if (!$existing) {
            $db->table('subject_category')->insert([
                'sub_cat_name'   => 'Spiritual and Moral Development',
                'sub_cat_status' => 1,
                'created_date'   => $today,
                'updated_date'   => $today,
            ]);
        }

        $catRow = $db->table('subject_category')
            ->where('sub_cat_name', 'Spiritual and Moral Development')
            ->get()->getRowArray();

        $catId = (int) $catRow['sub_cat_id'];

        $db->table('subject')
            ->where('subject_name', 'Spiritual and Moral Development')
            ->where('sub_cat_id_fk', 0)
            ->update(['sub_cat_id_fk' => $catId]);
    }

    public function down(): void
    {
        $db = $this->db;

        $cat = $db->table('subject_category')
            ->where('sub_cat_name', 'Spiritual and Moral Development')
            ->get()->getRowArray();

        if (!$cat) {
            return;
        }

        $catId = (int) $cat['sub_cat_id'];

        $db->table('subject')
            ->where('subject_name', 'Spiritual and Moral Development')
            ->where('sub_cat_id_fk', $catId)
            ->update(['sub_cat_id_fk' => 0]);

        $inUse = $db->table('subject')->where('sub_cat_id_fk', $catId)->countAllResults();
        if ($inUse === 0) {
            $db->table('subject_category')->where('sub_cat_id', $catId)->delete();
        }
    }
}
