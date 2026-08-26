<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPackageTypeToSubscription extends Migration
{
    public function up(): void
    {
        $this->db->query("
            ALTER TABLE `subscription`
            ADD COLUMN `package_type` ENUM('web','web_mobile') NOT NULL DEFAULT 'web' AFTER `billing_cycle`
        ");
    }

    public function down(): void
    {
        $this->db->query("
            ALTER TABLE `subscription`
            DROP COLUMN `package_type`
        ");
    }
}
