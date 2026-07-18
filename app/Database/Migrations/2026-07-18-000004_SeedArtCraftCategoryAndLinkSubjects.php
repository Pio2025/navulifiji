<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedArtCraftCategoryAndLinkSubjects extends Migration
{
    public function up(): void
    {
        $db    = $this->db;
        $forge = \Config\Database::forge();
        $today = date('Y-m-d');

        // Create subject_category table if it does not already exist
        if (!$db->tableExists('subject_category')) {
            $forge->addField([
                'sub_cat_id'   => ['type' => 'INT',         'auto_increment' => true],
                'sub_cat_name' => ['type' => 'VARCHAR',     'constraint' => 260],
                'created_date' => ['type' => 'DATE'],
                'updated_date' => ['type' => 'DATE'],
                'sub_cat_status' => ['type' => 'INT', 'default' => 1],
            ]);
            $forge->addPrimaryKey('sub_cat_id');
            $forge->createTable('subject_category', true);
        }

        // Insert "Art & Craft" category only if not already present
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

        $catRow = $db->table('subject_category')
            ->where('sub_cat_name', 'Art & Craft')
            ->get()->getRowArray();

        $catId = (int) $catRow['sub_cat_id'];

        // Only link subjects that are not yet assigned to any category
        $db->query(
            "UPDATE subject SET sub_cat_id_fk = ? WHERE subject_name REGEXP ? AND sub_cat_id_fk = 0",
            [$catId, '^Art Is Fun [0-9]+$']
        );
    }

    public function down(): void
    {
        $db = $this->db;

        if (!$db->tableExists('subject_category')) {
            return;
        }

        $cat = $db->table('subject_category')
            ->where('sub_cat_name', 'Art & Craft')
            ->get()->getRowArray();

        if (!$cat) {
            return;
        }

        $catId = (int) $cat['sub_cat_id'];

        // Unlink subjects we linked
        $db->query(
            "UPDATE subject SET sub_cat_id_fk = 0 WHERE sub_cat_id_fk = ? AND subject_name REGEXP ?",
            [$catId, '^Art Is Fun [0-9]+$']
        );

        // Remove the category only if nothing still references it
        $inUse = $db->table('subject')->where('sub_cat_id_fk', $catId)->countAllResults();
        if ($inUse === 0) {
            $db->table('subject_category')->where('sub_cat_id', $catId)->delete();
        }
    }
}
