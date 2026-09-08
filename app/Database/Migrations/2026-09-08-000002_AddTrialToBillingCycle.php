<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTrialToBillingCycle extends Migration
{
    public function up(): void
    {
        $this->db->query("
            ALTER TABLE `subscription`
            MODIFY COLUMN `billing_cycle` ENUM('monthly','annual','trial') NOT NULL DEFAULT 'monthly'
        ");
    }

    public function down(): void
    {
        $this->db->query("
            UPDATE `subscription` SET `billing_cycle` = 'monthly' WHERE `billing_cycle` = 'trial'
        ");
        $this->db->query("
            ALTER TABLE `subscription`
            MODIFY COLUMN `billing_cycle` ENUM('monthly','annual') NOT NULL DEFAULT 'monthly'
        ");
    }
}
