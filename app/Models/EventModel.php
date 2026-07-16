<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table         = 'school_event';
    protected $primaryKey    = 'event_id';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'sch_id_fk', 'title', 'description', 'event_type',
        'start_date', 'end_date', 'start_time', 'end_time',
        'location', 'organizer', 'color', 'status',
        'created_by', 'created_at', 'updated_at',
    ];

    // ── table bootstrap ──────────────────────────────────────────────────────

    public function ensureTables(): void
    {
        $db  = \Config\Database::connect();
        $dbf = \Config\Database::forge();

        if (!$db->tableExists('school_event')) {
            $dbf->addField([
                'event_id'    => ['type' => 'INT',      'unsigned' => true, 'auto_increment' => true],
                'sch_id_fk'   => ['type' => 'INT',      'unsigned' => true],
                'title'       => ['type' => 'VARCHAR',  'constraint' => 255],
                'description' => ['type' => 'TEXT',     'null' => true],
                'event_type'  => ['type' => 'ENUM',     'constraint' => ['Academic','Sports','Cultural','Meeting','Holiday','Examination','Other'], 'default' => 'Other'],
                'start_date'  => ['type' => 'DATE'],
                'end_date'    => ['type' => 'DATE',     'null' => true],
                'start_time'  => ['type' => 'TIME',     'null' => true],
                'end_time'    => ['type' => 'TIME',     'null' => true],
                'location'    => ['type' => 'VARCHAR',  'constraint' => 255, 'null' => true],
                'organizer'   => ['type' => 'VARCHAR',  'constraint' => 255, 'null' => true],
                'color'       => ['type' => 'VARCHAR',  'constraint' => 20,  'null' => true],
                'status'      => ['type' => 'ENUM',     'constraint' => ['Upcoming','Ongoing','Completed','Cancelled'], 'default' => 'Upcoming'],
                'created_by'  => ['type' => 'INT',      'unsigned' => true],
                'created_at'  => ['type' => 'DATETIME'],
                'updated_at'  => ['type' => 'DATETIME'],
            ]);
            $dbf->addPrimaryKey('event_id');
            $dbf->addKey('sch_id_fk');
            $dbf->addKey('start_date');
            $dbf->createTable('school_event', true, ['ENGINE' => 'MyISAM']);
        }
    }

    // ── queries ──────────────────────────────────────────────────────────────

    public function getAll(int $schId, array $filters = []): array
    {
        $db = \Config\Database::connect();
        $q  = $db->table('school_event se')
            ->join('users u', 'u.user_id = se.created_by', 'left')
            ->select("se.*, CONCAT(u.fname,' ',u.lname) AS creator_name")
            ->orderBy('se.start_date', 'DESC');

        if ($schId) $q->where('se.sch_id_fk', $schId);
        if (!empty($filters['type']))   $q->where('se.event_type', $filters['type']);
        if (!empty($filters['status'])) $q->where('se.status',     $filters['status']);
        if (!empty($filters['from']))   $q->where('se.start_date >=', $filters['from']);
        if (!empty($filters['to']))     $q->where('se.start_date <=', $filters['to']);

        return $q->get()->getResultArray();
    }

    public function getOne(int $id): ?array
    {
        $db  = \Config\Database::connect();
        $row = $db->table('school_event se')
            ->join('users u', 'u.user_id = se.created_by', 'left')
            ->select("se.*, CONCAT(u.fname,' ',u.lname) AS creator_name")
            ->where('se.event_id', $id)
            ->get()->getRowArray();
        return $row ?: null;
    }

    public function getCalendarEvents(int $schId, string $start, string $end): array
    {
        $db = \Config\Database::connect();
        return $db->table('school_event')
            ->where('sch_id_fk', $schId)
            ->where('start_date >=', $start)
            ->where('start_date <=', $end)
            ->orderBy('start_date', 'ASC')
            ->get()->getResultArray();
    }

    public function getSummaryStats(int $schId): array
    {
        $db   = \Config\Database::connect();
        $rows = $db->query(
            "SELECT status, COUNT(*) AS cnt FROM school_event WHERE sch_id_fk = ? GROUP BY status",
            [$schId]
        )->getResultArray();

        $stats = ['Upcoming' => 0, 'Ongoing' => 0, 'Completed' => 0, 'Cancelled' => 0, 'total' => 0];
        foreach ($rows as $r) {
            $stats[$r['status']] = (int) $r['cnt'];
            $stats['total']     += (int) $r['cnt'];
        }
        return $stats;
    }

    public function getTypeBreakdown(int $schId): array
    {
        $db = \Config\Database::connect();
        return $db->query(
            "SELECT event_type, COUNT(*) AS cnt FROM school_event WHERE sch_id_fk = ? GROUP BY event_type ORDER BY cnt DESC",
            [$schId]
        )->getResultArray();
    }

    // ── helpers ──────────────────────────────────────────────────────────────

    public static function typeColor(string $type): string
    {
        return [
            'Academic'    => '#3788d8',
            'Sports'      => '#28a745',
            'Cultural'    => '#fd7e14',
            'Meeting'     => '#6f42c1',
            'Holiday'     => '#dc3545',
            'Examination' => '#e7a800',
            'Other'       => '#6c757d',
        ][$type] ?? '#6c757d';
    }

    public static function typeBadge(string $type): string
    {
        $map = [
            'Academic'    => 'badge-light-primary',
            'Sports'      => 'badge-light-success',
            'Cultural'    => 'badge-light-warning',
            'Meeting'     => 'badge-light-info',
            'Holiday'     => 'badge-light-danger',
            'Examination' => 'badge-light-warning',
            'Other'       => 'badge-secondary',
        ];
        return $map[$type] ?? 'badge-secondary';
    }

    public static function statusBadge(string $status): string
    {
        return [
            'Upcoming'  => 'badge-light-primary',
            'Ongoing'   => 'badge-light-success',
            'Completed' => 'badge-light-secondary',
            'Cancelled' => 'badge-light-danger',
        ][$status] ?? 'badge-secondary';
    }
}
