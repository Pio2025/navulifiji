<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReferenceRequests extends Migration
{
    public function up(): void
    {
        $db  = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');

        // Reference requests table
        $db->query("
            CREATE TABLE IF NOT EXISTS `reference_requests` (
                `request_id`     INT(11) NOT NULL AUTO_INCREMENT,
                `user_id_fk`     INT(11) NOT NULL,
                `ref_cat_id`     INT(11) NOT NULL,
                `ref_type_name`  VARCHAR(100) NOT NULL,
                `request_note`   TEXT DEFAULT NULL,
                `request_status` ENUM('Pending','In Progress','Completed','Rejected') NOT NULL DEFAULT 'Pending',
                `reviewed_by`    INT(11) DEFAULT NULL,
                `review_note`    TEXT DEFAULT NULL,
                `created_at`     DATETIME DEFAULT NULL,
                `updated_at`     DATETIME DEFAULT NULL,
                PRIMARY KEY (`request_id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4
        ");

        // Add permission for Reference Requests (module 3 = User)
        $db->query("
            INSERT INTO `permission`
                (module_id_fk, perm_name, perm_desc, perm_controller, perm_code, show_in_nav, perm_status, created_at, updated_at)
            SELECT 3, 'Reference Requests', 'View and manage student reference requests', 'reference/requests', '_reference_requests', 1, 1, '{$now}', '{$now}'
            WHERE NOT EXISTS (
                SELECT 1 FROM `permission` WHERE perm_code = '_reference_requests'
            )
        ");

        // Grant to staff roles: Super Admin, School Admin, Principal, HOD, Assistant Teacher
        $db->query("
            INSERT INTO role_permission (perm_id_fk, role_id_fk, created_at, updated_at)
            SELECT p.perm_id, r.role_id, '{$now}', '{$now}'
            FROM permission p
            CROSS JOIN role r
            WHERE p.perm_code = '_reference_requests'
              AND r.role_name IN ('Super Admin','Admin','School Admin','Principal','HOD','Assistant Teacher','Support Staff')
              AND NOT EXISTS (
                  SELECT 1 FROM role_permission x
                  WHERE x.perm_id_fk = p.perm_id AND x.role_id_fk = r.role_id
              )
        ");
    }

    public function down(): void
    {
        $db = \Config\Database::connect();

        $db->query("
            DELETE rp FROM role_permission rp
            JOIN permission p ON p.perm_id = rp.perm_id_fk
            WHERE p.perm_code = '_reference_requests'
        ");

        $db->query("DELETE FROM permission WHERE perm_code = '_reference_requests'");

        $db->query("DROP TABLE IF EXISTS `reference_requests`");
    }
}
