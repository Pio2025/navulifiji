<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedArtCraftCategoryAndLinkSubjects extends Migration
{
    public function up(): void
    {
        $db    = $this->db;
        $today = date('Y-m-d');

        // Insert "Art & Craft" category if not already present
        $existing = $db->table('subject_category')
            ->where('sub_cat_name', 'Art & Craft')
            ->get()->getRowArray();

        if (!$existing) {
            $db->table('subject_category')->insert([
                'sub_cat_name'   => 'Art & Craft',
                'sub_cat_status' => 1,
                'created_date'   => $today,
                'updated_date'   => $today,
            ]);
        }

        $catId = (int) $db->table('subject_category')
            ->where('sub_cat_name', 'Art & Craft')
            ->get()->getRowArray()['sub_cat_id'];

        // Link all "Art Is Fun N" subjects (N = one or more digits)
        $db->query(
            "UPDATE subject SET sub_cat_id_fk = ? WHERE subject_name REGEXP ?",
            [$catId, '^Art Is Fun [0-9]+$']
        );
    }

    public function down(): void
    {
        $db = $this->db;

        $cat = $db->table('subject_category')
            ->where('sub_cat_name', 'Art & Craft')
            ->get()->getRowArray();

        if (!$cat) {
            return;
        }

        $catId = (int) $cat['sub_cat_id'];

        // Only unlink subjects we linked (avoid touching manually assigned ones)
        $db->query(
            "UPDATE subject SET sub_cat_id_fk = 0 WHERE sub_cat_id_fk = ? AND subject_name REGEXP ?",
            [$catId, '^Art Is Fun [0-9]+$']
        );

        // Remove the category only if no subjects still reference it
        $inUse = $db->table('subject')->where('sub_cat_id_fk', $catId)->countAllResults();
        if ($inUse === 0) {
            $db->table('subject_category')->where('sub_cat_id', $catId)->delete();
        }
    }
}
