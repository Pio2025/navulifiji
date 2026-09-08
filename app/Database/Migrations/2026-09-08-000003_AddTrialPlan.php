<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTrialPlan extends Migration
{
    public function up(): void
    {
        $existing = $this->db->table('plans')->where('plan_id', 5)->get()->getRow();

        if (!$existing) {
            $this->db->table('plans')->insert([
                'plan_id' => 5,
                'plan_name' => 'Trial',
                'plan_desc' => 'Full access to all Standard plan modules and features, on the web app, free for 30 days — no payment required to get started.',
                'plan_monthly_cost' => 0,
                'plan_monthly_cost_web_n_mobile' => 0,
            ]);
        }
    }

    public function down(): void
    {
        $this->db->table('plans')->where('plan_id', 5)->where('plan_name', 'Trial')->delete();
    }
}
