<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLinkedUserToNextOfKin extends Migration
{
    public function up()
    {
        $hasColumn = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'next_of_kin'
              AND COLUMN_NAME  = 'linked_user_id_fk'
        ")->getRow()->cnt;

        if (!$hasColumn) {
            $this->db->query("
                ALTER TABLE next_of_kin
                ADD COLUMN linked_user_id_fk INT NULL DEFAULT NULL AFTER user_id_fk,
                ADD KEY linked_user_id_fk (linked_user_id_fk),
                ADD CONSTRAINT next_of_kin_linked_user_fk
                    FOREIGN KEY (linked_user_id_fk) REFERENCES users (user_id)
                    ON DELETE SET NULL ON UPDATE CASCADE
            ");
        }
    }

    public function down()
    {
        $hasColumn = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'next_of_kin'
              AND COLUMN_NAME  = 'linked_user_id_fk'
        ")->getRow()->cnt;

        if ($hasColumn) {
            $this->db->query("ALTER TABLE next_of_kin DROP FOREIGN KEY next_of_kin_linked_user_fk");
            $this->db->query("ALTER TABLE next_of_kin DROP COLUMN linked_user_id_fk");
        }
    }
}
