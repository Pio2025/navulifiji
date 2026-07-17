<?php

namespace App\Models;

use CodeIgniter\Model;

class TimetableEntryModel extends Model
{
    protected $table      = 'timetable_entry';
    protected $primaryKey = 'entry_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'timetable_id_fk', 'day_number', 'slot_id_fk', 'sch_sub_id_fk', 'teacher_id_fk', 'room', 'notes',
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
            ->select('te.*, sub.subject_name, u.fname, u.lname')
            ->join('sch_subject ss',  'ss.sch_sub_id = te.sch_sub_id_fk', 'left')
            ->join('subject sub',     'sub.subject_id = ss.subject_id_fk', 'left')
            ->join('users u',         'u.user_id = te.teacher_id_fk',      'left')
            ->where('te.timetable_id_fk', $timetableId)
            ->orderBy('te.day_number', 'ASC')
            ->orderBy('te.slot_id_fk', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Returns a nested map: $map[day_number][slot_id] = entry row.
     */
    public function getMappedByTimetable(int $timetableId): array
    {
        $map = [];
        foreach ($this->getByTimetable($timetableId) as $row) {
            $map[$row['day_number']][$row['slot_id_fk']] = $row;
        }
        return $map;
    }

    /**
     * Delete all existing entries for this timetable and insert the new set.
     */
    public function replaceEntries(int $timetableId, array $entries): void
    {
        $this->where('timetable_id_fk', $timetableId)->delete();

        foreach ($entries as $entry) {
            $this->insert([
                'timetable_id_fk' => $timetableId,
                'day_number'      => (int) $entry['day_number'],
                'slot_id_fk'      => (int) $entry['slot_id_fk'],
                'sch_sub_id_fk'   => ($entry['sch_sub_id_fk']  > 0) ? (int) $entry['sch_sub_id_fk']  : null,
                'teacher_id_fk'   => ($entry['teacher_id_fk']  > 0) ? (int) $entry['teacher_id_fk']  : null,
                'room'            => trim($entry['room']  ?? '') ?: null,
                'notes'           => trim($entry['notes'] ?? '') ?: null,
            ]);
        }
    }
}
