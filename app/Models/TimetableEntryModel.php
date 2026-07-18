<?php

namespace App\Models;

use CodeIgniter\Model;

class TimetableEntryModel extends Model
{
    protected $table      = 'timetable_entry';
    protected $primaryKey = 'entry_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'timetable_id_fk', 'day_number', 'slot_id_fk', 'option_num',
        'sch_sub_id_fk', 'teacher_id_fk', 'room', 'notes',
    ];

    public function ensureTables(): void
    {
        try {
            $this->db->query('SELECT 1 FROM timetable_entry LIMIT 1');
        } catch (\Throwable $e) {}
    }

    public function getByTimetable(int $timetableId): array
    {
        return $this->db->table('timetable_entry te')
            ->select('te.*, sub.subject_name, sc.sub_cat_name, sc.sub_cat_initial, u.fname, u.lname')
            ->join('sch_subject ss',       'ss.sch_sub_id = te.sch_sub_id_fk',   'left')
            ->join('subject sub',          'sub.subject_id = ss.subject_id_fk',  'left')
            ->join('subject_category sc',  'sc.sub_cat_id = sub.sub_cat_id_fk',  'left')
            ->join('users u',              'u.user_id = te.teacher_id_fk',       'left')
            ->where('te.timetable_id_fk', $timetableId)
            ->orderBy('te.day_number', 'ASC')
            ->orderBy('te.slot_id_fk', 'ASC')
            ->orderBy('te.option_num', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Returns a nested map: $map[day_number][slot_id] = cell.
     *
     * Core cells: is_optional=false, convenience fields populated directly.
     * Optional cells: is_optional=true, option_num set, entries[] has one row per subject.
     */
    public function getMappedByTimetable(int $timetableId): array
    {
        $map = [];
        foreach ($this->getByTimetable($timetableId) as $row) {
            $day    = (int) $row['day_number'];
            $slotId = (int) $row['slot_id_fk'];

            if (!isset($map[$day][$slotId])) {
                $map[$day][$slotId] = [
                    'is_optional'   => false,
                    'option_num'    => null,
                    'entries'       => [],
                    'sch_sub_id_fk'    => null,
                    'subject_name'     => null,
                    'sub_cat_name'     => null,
                    'sub_cat_initial'  => null,
                    'fname'         => null,
                    'lname'         => null,
                    'teacher_id_fk' => null,
                    'room'          => null,
                ];
            }

            $map[$day][$slotId]['entries'][] = $row;

            if ($row['option_num'] !== null) {
                $map[$day][$slotId]['is_optional'] = true;
                $map[$day][$slotId]['option_num']  = (int) $row['option_num'];
            } else {
                $map[$day][$slotId]['sch_sub_id_fk']   = $row['sch_sub_id_fk'];
                $map[$day][$slotId]['subject_name']    = $row['subject_name'];
                $map[$day][$slotId]['sub_cat_name']    = $row['sub_cat_name'];
                $map[$day][$slotId]['sub_cat_initial'] = $row['sub_cat_initial'];
                $map[$day][$slotId]['fname']           = $row['fname'];
                $map[$day][$slotId]['lname']          = $row['lname'];
                $map[$day][$slotId]['teacher_id_fk']  = $row['teacher_id_fk'];
                $map[$day][$slotId]['room']           = $row['room'];
            }
        }
        return $map;
    }

    public function replaceEntries(int $timetableId, array $entries): void
    {
        $this->where('timetable_id_fk', $timetableId)->delete();

        foreach ($entries as $entry) {
            $optNum = (isset($entry['option_num']) && $entry['option_num'] !== null && $entry['option_num'] !== '')
                ? (int) $entry['option_num']
                : null;

            $this->insert([
                'timetable_id_fk' => $timetableId,
                'day_number'      => (int) $entry['day_number'],
                'slot_id_fk'      => (int) $entry['slot_id_fk'],
                'option_num'      => $optNum,
                'sch_sub_id_fk'   => !empty($entry['sch_sub_id_fk']) ? (int) $entry['sch_sub_id_fk'] : null,
                'teacher_id_fk'   => !empty($entry['teacher_id_fk']) ? (int) $entry['teacher_id_fk'] : null,
                'room'            => trim($entry['room']  ?? '') ?: null,
                'notes'           => trim($entry['notes'] ?? '') ?: null,
            ]);
        }
    }
}
