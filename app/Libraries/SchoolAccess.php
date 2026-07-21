<?php

namespace App\Libraries;

/**
 * Resolves which school(s) a mobile API request may view/post to.
 * Mirrors the resolveSchoolId()/resolveParentSchoolIds() pattern already
 * used by the web NoticeBoardController/AnnouncementController/WallController,
 * but stateless (no session caching) since the mobile API is a JWT client.
 */
class SchoolAccess
{
    /**
     * @return array<int, array{schId:int, schName:string, schLogo:?string, own:bool}>
     */
    public function accessibleSchools(int $userId, int $roleCatId, int $ownSchId): array
    {
        $db = \Config\Database::connect();

        $userRow   = $db->table('users')->select('is_a_parent')->where('user_id', $userId)->get()->getRowArray();
        $isAParent = ((int) ($userRow['is_a_parent'] ?? 0) === 1) || $roleCatId === 6;

        $schools = [];

        if ($ownSchId !== 0) {
            $ownRow = $db->table('school')
                ->select('sch_id, sch_name, sch_logo')
                ->where('sch_id', $ownSchId)
                ->get()->getRowArray();
            if ($ownRow) {
                $schools[] = [
                    'schId'   => (int) $ownRow['sch_id'],
                    'schName' => $ownRow['sch_name'],
                    'schLogo' => $ownRow['sch_logo'] ?: null,
                    'own'     => true,
                ];
            }
        }

        if ($isAParent) {
            $childSchools = $db->query("
                SELECT DISTINCT sch.sch_id, sch.sch_name, sch.sch_logo
                FROM parent_student ps
                INNER JOIN users stu ON stu.user_id = ps.student_user_id_fk
                INNER JOIN admission a ON a.user_id_fk = stu.user_id AND a.admission_status = 'Active'
                INNER JOIN school sch ON sch.sch_id = a.sch_id_fk
                WHERE ps.parent_user_id_fk = ?
                ORDER BY sch.sch_name
            ", [$userId])->getResultArray();

            foreach ($childSchools as $s) {
                $schId = (int) $s['sch_id'];
                if ($schId === $ownSchId) {
                    continue;
                }
                $schools[] = [
                    'schId'   => $schId,
                    'schName' => $s['sch_name'],
                    'schLogo' => $s['sch_logo'] ?: null,
                    'own'     => false,
                ];
            }
        }

        return $schools;
    }

    /**
     * @param array<int, array{schId:int}> $schools
     */
    public function resolveActiveSchoolId(array $schools, ?int $requestedSchId): int
    {
        if (empty($schools)) {
            return 0;
        }
        if ($requestedSchId !== null) {
            foreach ($schools as $s) {
                if ($s['schId'] === $requestedSchId) {
                    return $requestedSchId;
                }
            }
        }
        return $schools[0]['schId'];
    }
}
