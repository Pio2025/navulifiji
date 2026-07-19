<?php

namespace App\Libraries;

/**
 * Fire-and-forget bridge to the navuli_chat Socket.IO server's /internal/notify
 * endpoint, so already-open browser tabs get a live push the moment a notice,
 * announcement, event, wall post, conduct appeal, or activity log entry is
 * created — instead of waiting for the next unread-counts poll.
 *
 * Every public method is best-effort: failures are swallowed so a broadcast
 * outage never breaks the page request that triggered it. Clients always
 * reconcile against dashboard/unread-counts regardless, so a dropped push is
 * only ever a delay, never a correctness issue.
 */
class RealtimeNotifier
{
    private static function socketUrl(): ?string
    {
        $url = getenv('CHAT_SOCKET_URL');
        return $url ?: null;
    }

    private static function secret(): string
    {
        return env('CHAT_JWT_SECRET', 'navuli-chat-secret-change-me-in-production');
    }

    /** Push a `notification` event to every given user's already-connected sockets. */
    public static function notify(array $userIds, string $domain, array $payload = []): void
    {
        $userIds = array_values(array_unique(array_filter(
            array_map('intval', $userIds),
            static fn (int $id) => $id > 0
        )));
        if (empty($userIds)) return;

        $url = self::socketUrl();
        if (!$url) return;

        try {
            $isProd = getenv('CI_ENVIRONMENT') === 'production';
            $ch = curl_init(rtrim($url, '/') . '/internal/notify');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json',
                    'X-Chat-Internal-Secret: ' . self::secret(),
                ],
                CURLOPT_POSTFIELDS     => json_encode([
                    'userIds' => $userIds,
                    'payload' => array_merge(['domain' => $domain], $payload),
                ]),
                CURLOPT_TIMEOUT        => 2,
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_SSL_VERIFYPEER => $isProd,
                CURLOPT_SSL_VERIFYHOST => $isProd ? 2 : 0,
            ]);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $e) {
            // Best-effort push — never let a broadcast failure break the caller.
        }
    }

    // ── recipient resolution ────────────────────────────────────────────────

    /**
     * User IDs that should be notified for $schId, scoped to $audience
     * ('All'|'Teachers'|'Students'|'Parents'), mirroring the audience
     * semantics already used by DashboardController::unreadCounts().
     */
    public static function recipientsForSchool(int $schId, string $audience = 'All'): array
    {
        if ($schId <= 0) return [];

        try {
            $db  = \Config\Database::connect();
            $ids = [];

            if ($audience === 'Students' || $audience === 'All') {
                $rows = $db->query(
                    "SELECT user_id_fk FROM admission WHERE sch_id_fk = ? AND admission_status = 'Active'",
                    [$schId]
                )->getResultArray();
                foreach ($rows as $r) $ids[] = (int) $r['user_id_fk'];
            }

            if ($audience === 'Parents' || $audience === 'All') {
                $rows = $db->query("
                    SELECT DISTINCT ps.parent_user_id_fk AS user_id_fk
                    FROM parent_student ps
                    INNER JOIN admission a ON a.user_id_fk = ps.student_user_id_fk
                    WHERE a.sch_id_fk = ? AND a.admission_status = 'Active'
                ", [$schId])->getResultArray();
                foreach ($rows as $r) $ids[] = (int) $r['user_id_fk'];
            }

            if ($audience === 'Teachers') {
                $rows = $db->query("
                    SELECT s.user_id_fk
                    FROM staff s
                    INNER JOIN user_role ur ON ur.user_id_fk = s.user_id_fk AND ur.user_role_status = 'Active'
                    INNER JOIN role r ON r.role_id = ur.role_id_fk
                    WHERE s.sch_id_fk = ? AND s.staff_status = 'Active' AND r.role_cat_id_fk = 3
                ", [$schId])->getResultArray();
                foreach ($rows as $r) $ids[] = (int) $r['user_id_fk'];
            } elseif ($audience === 'All') {
                $rows = $db->query(
                    "SELECT user_id_fk FROM staff WHERE sch_id_fk = ? AND staff_status = 'Active'",
                    [$schId]
                )->getResultArray();
                foreach ($rows as $r) $ids[] = (int) $r['user_id_fk'];
            }

            return array_values(array_unique($ids));
        } catch (\Throwable $e) {
            return [];
        }
    }

    /** User IDs at $schId whose active role carries $permCode (e.g. staff who can process conduct appeals). */
    public static function recipientsWithPermission(int $schId, string $permCode): array
    {
        if ($schId <= 0) return [];

        try {
            $db   = \Config\Database::connect();
            $rows = $db->query("
                SELECT DISTINCT s.user_id_fk
                FROM staff s
                INNER JOIN user_role ur ON ur.user_id_fk = s.user_id_fk AND ur.user_role_status = 'Active'
                INNER JOIN role_permission rp ON rp.role_id_fk = ur.role_id_fk
                INNER JOIN permission p ON p.perm_id = rp.perm_id_fk AND p.perm_code = ?
                WHERE s.sch_id_fk = ? AND s.staff_status = 'Active'
            ", [$permCode, $schId])->getResultArray();

            return array_values(array_unique(array_map(static fn ($r) => (int) $r['user_id_fk'], $rows)));
        } catch (\Throwable $e) {
            return [];
        }
    }
}
