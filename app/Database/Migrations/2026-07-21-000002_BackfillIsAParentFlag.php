<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BackfillIsAParentFlag extends Migration
{
    // Any account that already has linked children via parent_student should
    // have is_a_parent = 1, even if that step was skipped before linkChild()
    // started setting it automatically.
    public function up(): void
    {
        $this->db->query("
            UPDATE users
            SET is_a_parent = 1
            WHERE user_id IN (SELECT DISTINCT parent_user_id_fk FROM parent_student)
              AND is_a_parent != 1
        ");
    }

    public function down(): void
    {
        // Not reversible — we don't know which of these were already 1 before.
    }
}
