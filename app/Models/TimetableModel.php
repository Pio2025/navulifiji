<?php

namespace App\Models;

use CodeIgniter\Model;

class TimetableModel extends Model
{
    protected $table      = 'timetable';
    protected $primaryKey = 'timetable_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'sch_id_fk', 'stream_id_fk', 'template_id_fk', 'academic_year', 'term',
        'rotation_start_date', 'rotation_start_day', 'timetable_status',
        'created_by', 'created_at', 'updated_at',
    ];

    public function ensureTables(): void
    {
        try {
            $this->db->query('SELECT 1 FROM timetable LIMIT 1');
        } catch (\Throwable $e) {}
    }

    private function baseSelect(): \CodeIgniter\Database\BaseBuilder
    {
        return $this->db->table('timetable t')
            ->select('t.*, stream.stream_name, level.level_name, school.sch_name,
                      sch_category.sch_cat_name, tpl.template_name')
            ->join('stream',        'stream.stream_id = t.stream_id_fk', 'left')
            ->join('sch_level',     'sch_level.sch_level_id = stream.sch_level_id_fk', 'left')
            ->join('level',         'level.level_id = sch_level.level_id_fk', 'left')
            ->join('school',        'school.sch_id = sch_level.sch_id_fk', 'left')
            ->join('sch_category',  'sch_category.sch_cat_id = school.sch_cat_id_fk', 'left')
            ->join('timetable_template tpl', 'tpl.template_id = t.template_id_fk', 'left');
    }

    public function getBySchool(int $schId): array
    {
        return $this->baseSelect()
            ->where('t.sch_id_fk', $schId)
            ->orderBy('t.academic_year', 'DESC')
            ->orderBy('t.term', 'ASC')
            ->orderBy('stream.stream_name', 'ASC')
            ->get()->getResultArray();
    }

    public function getAllWithDetails(): array
    {
        return $this->baseSelect()
            ->orderBy('t.academic_year', 'DESC')
            ->orderBy('school.sch_name', 'ASC')
            ->orderBy('stream.stream_name', 'ASC')
            ->get()->getResultArray();
    }

    public function getDetail(int $id): ?array
    {
        $row = $this->baseSelect()
            ->select('u.fname AS creator_fname, u.lname AS creator_lname', false)
            ->join('users u', 'u.user_id = t.created_by', 'left')
            ->where('t.timetable_id', $id)
            ->get()->getRowArray();
        return $row ?: null;
    }

    /**
     * Given the rotation start anchor, compute the day number (1–6) for any calendar date.
     * School days are Mon–Fri only.
     */
    public function getDayNumberForDate(string $startDate, int $startDay, string $targetDate): int
    {
        $start  = new \DateTime($startDate);
        $target = new \DateTime($targetDate);
        if ($target < $start) return 0;

        $schoolDays = 0;
        $cur = clone $start;
        while ($cur <= $target) {
            if ((int) $cur->format('N') <= 5) $schoolDays++;
            $cur->modify('+1 day');
        }

        // schoolDays=1 when target===start; offset by (startDay-1)
        return (($startDay - 1 + $schoolDays - 1) % 6) + 1;
    }

    /**
     * Returns an array keyed Mon–Fri for the current ISO week,
     * each element: ['date' => 'd M', 'day_number' => 1–6 or null].
     */
    public function getWeekDayMap(string $startDate, int $startDay): array
    {
        $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        $monday = new \DateTime('monday this week');
        $start  = new \DateTime($startDate);
        $map    = [];

        foreach ($labels as $i => $lbl) {
            $d = clone $monday;
            $d->modify("+{$i} days");
            $map[$lbl] = [
                'date'       => $d->format('d M'),
                'day_number' => ($d >= $start)
                    ? $this->getDayNumberForDate($startDate, $startDay, $d->format('Y-m-d'))
                    : null,
            ];
        }

        return $map;
    }
}
