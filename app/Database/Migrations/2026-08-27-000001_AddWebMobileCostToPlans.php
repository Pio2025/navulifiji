<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWebMobileCostToPlans extends Migration
{
    public function up(): void
    {
        $this->db->query("
            ALTER TABLE `plans`
            ADD COLUMN `plan_monthly_cost_web_n_mobile` DOUBLE NULL DEFAULT NULL AFTER `plan_monthly_cost`
        ");

        $this->db->query("UPDATE `plans` SET `plan_monthly_cost_web_n_mobile` = 300 WHERE `plan_name` = 'Standard'");
        $this->db->query("UPDATE `plans` SET `plan_monthly_cost_web_n_mobile` = 400 WHERE `plan_name` = 'Premium'");
        $this->db->query("UPDATE `plans` SET `plan_monthly_cost_web_n_mobile` = 500 WHERE `plan_name` = 'Ultimate'");
    }

    public function down(): void
    {
        $this->db->query("
            ALTER TABLE `plans`
            DROP COLUMN `plan_monthly_cost_web_n_mobile`
        ");
    }
}
