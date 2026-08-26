<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBillingCycleToSubscription extends Migration
{
    public function up(): void
    {
        $this->db->query("
            ALTER TABLE `subscription`
            ADD COLUMN `billing_cycle` ENUM('monthly','annual') NOT NULL DEFAULT 'monthly' AFTER `subscription_term`,
            ADD COLUMN `discount_percent` DECIMAL(5,2) NOT NULL DEFAULT 0 AFTER `billing_cycle`,
            ADD COLUMN `amount_paid` DECIMAL(10,2) NULL DEFAULT NULL AFTER `discount_percent`,
            MODIFY COLUMN `payment_mode` VARCHAR(260) NOT NULL DEFAULT ''
        ");
    }

    public function down(): void
    {
        $this->db->query("
            ALTER TABLE `subscription`
            DROP COLUMN `billing_cycle`,
            DROP COLUMN `discount_percent`,
            DROP COLUMN `amount_paid`,
            MODIFY COLUMN `payment_mode` VARCHAR(260) NOT NULL
        ");
    }
}
