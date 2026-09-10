<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPlanRankToPlans extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('plans', [
            'plan_rank' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'plan_name',
            ],
        ]);

        // Trial mirrors Standard access, so it ranks the same.
        $ranks = [
            'Standard'   => 1,
            'Trial'      => 1,
            'Premium'    => 2,
            'Ultimate'   => 3,
            'Enterprise' => 4,
        ];

        foreach ($ranks as $planName => $rank) {
            $this->db->table('plans')->where('plan_name', $planName)->update(['plan_rank' => $rank]);
        }
    }

    public function down(): void
    {
        $this->forge->dropColumn('plans', 'plan_rank');
    }
}
