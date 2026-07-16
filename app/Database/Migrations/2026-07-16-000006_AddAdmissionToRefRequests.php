<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdmissionToRefRequests extends Migration
{
    public function up(): void
    {
        $db  = \Config\Database::connect();
        $dbName = $db->getDatabase();

        // Add admission_id_fk if not present
        $exists = $db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = '{$dbName}' AND TABLE_NAME = 'reference_requests'
              AND COLUMN_NAME = 'admission_id_fk'
        ")->getRow()->cnt;

        if (!$exists) {
            $db->query("ALTER TABLE reference_requests ADD COLUMN admission_id_fk INT(11) DEFAULT NULL AFTER user_id_fk");
        }

        // Add date_processed if not present
        $exists2 = $db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = '{$dbName}' AND TABLE_NAME = 'reference_requests'
              AND COLUMN_NAME = 'date_processed'
        ")->getRow()->cnt;

        if (!$exists2) {
            $db->query("ALTER TABLE reference_requests ADD COLUMN date_processed DATETIME DEFAULT NULL AFTER review_note");
        }
    }

    public function down(): void
    {
        $db = \Config\Database::connect();
        $dbName = $db->getDatabase();

        foreach (['admission_id_fk', 'date_processed'] as $col) {
            $exists = $db->query("
                SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = '{$dbName}' AND TABLE_NAME = 'reference_requests'
                  AND COLUMN_NAME = '{$col}'
            ")->getRow()->cnt;

            if ($exists) {
                $db->query("ALTER TABLE reference_requests DROP COLUMN {$col}");
            }
        }
    }
}
