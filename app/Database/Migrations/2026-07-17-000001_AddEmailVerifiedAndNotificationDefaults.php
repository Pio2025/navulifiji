<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailVerifiedAndNotificationDefaults extends Migration
{
    public function up()
    {
        // 1. Add email_verified column to users if not already present
        $exists = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'users'
              AND COLUMN_NAME  = 'email_verified'
        ")->getRow()->cnt;

        if (!$exists) {
            $this->db->query("ALTER TABLE users ADD COLUMN email_verified TINYINT(1) NOT NULL DEFAULT 0");
        }

        // 2. Mark existing active users with an email as verified
        $this->db->query("
            UPDATE users
            SET email_verified = 1
            WHERE email IS NOT NULL
              AND email != ''
              AND user_status = 'Active'
              AND email_verified = 0
        ");

        // 3. Auto-create user_notification rows for users who don't have one yet
        //    - all ON (1) if they have an email, all OFF (0) if no email
        $users = $this->db->query("
            SELECT u.user_id,
                   CASE WHEN u.email IS NOT NULL AND u.email != '' THEN 1 ELSE 0 END AS has_email
            FROM users u
            LEFT JOIN user_notification un ON un.user_id_fk = u.user_id
            WHERE un.notification_id IS NULL
        ")->getResultArray();

        $now  = date('Y-m-d');
        $time = time();

        foreach ($users as $u) {
            $val = (int) $u['has_email'];
            $this->db->query("
                INSERT INTO user_notification
                    (user_id_fk, notif_dashboard, notif_rbac, notif_user, notif_school,
                     notif_admission, notif_enrolment, notif_classroom, notif_exam,
                     notif_conduct, notif_timetable, notif_event, notif_communication,
                     notif_security, notif_medical, notif_reference, updated_date, updated_time)
                VALUES
                    ({$u['user_id']}, {$val}, {$val}, {$val}, {$val},
                     {$val}, {$val}, {$val}, {$val},
                     {$val}, {$val}, {$val}, {$val},
                     {$val}, {$val}, {$val}, '{$now}', {$time})
            ");
        }
    }

    public function down()
    {
        $exists = $this->db->query("
            SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'users'
              AND COLUMN_NAME  = 'email_verified'
        ")->getRow()->cnt;

        if ($exists) {
            $this->db->query("ALTER TABLE users DROP COLUMN email_verified");
        }
    }
}
