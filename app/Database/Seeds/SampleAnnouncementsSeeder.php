<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SampleAnnouncementsSeeder extends Seeder
{
    public function run()
    {
        $rows = [
            [
                'sch_id_fk'           => 12,
                'posted_by'           => 1,
                'title'               => 'Tropical Cyclone Alert — School Closure Wednesday 17 July',
                'content'             => "Dear Students, Staff and Parents,\n\nIn response to the Fiji Meteorological Service Tropical Cyclone Warning currently in effect, the school will be CLOSED on Wednesday, 17 July 2026.\n\nAll classes, examinations and school activities scheduled for that day are cancelled and will be rescheduled. Students are advised to stay at home and remain safe with their families.\n\nSchool will resume on Thursday, 18 July 2026, subject to further updates from the Meteorological Service.\n\nPlease monitor the school notice board and your registered email for further updates.\n\nThank you for your cooperation.",
                'priority'            => 'Critical',
                'attachment'          => null,
                'attachment_type'     => null,
                'attachment_name'     => null,
                'expires_at'          => '2026-07-19 23:59:59',
                'announcement_status' => 'Active',
                'created_at'          => '2026-07-16 06:30:00',
                'updated_at'          => '2026-07-16 06:30:00',
            ],
            [
                'sch_id_fk'           => 12,
                'posted_by'           => 1,
                'title'               => 'Term 2 Final Examination Schedule — Year 9 to Year 13',
                'content'             => "All students are hereby notified of the upcoming Term 2 Final Examinations:\n\nYear 9 & 10 Examinations: Monday 28 July – Friday 1 August 2026\nYear 11 & 12 Examinations: Monday 28 July – Friday 1 August 2026\nYear 13 Mock Examinations: Monday 4 August – Friday 8 August 2026\n\nKey Reminders:\n• Students must arrive at least 15 minutes before each examination.\n• School uniform is compulsory — no exceptions.\n• Mobile phones and electronic devices are strictly prohibited in the examination hall.\n• Students who miss an examination without a valid medical certificate will receive a zero mark.\n\nA detailed timetable has been posted on each classroom door. Please consult your class teacher for any queries.",
                'priority'            => 'Important',
                'attachment'          => null,
                'attachment_type'     => null,
                'attachment_name'     => null,
                'expires_at'          => '2026-08-08 23:59:59',
                'announcement_status' => 'Active',
                'created_at'          => '2026-07-14 08:00:00',
                'updated_at'          => '2026-07-14 08:00:00',
            ],
            [
                'sch_id_fk'           => 12,
                'posted_by'           => 1,
                'title'               => 'Parent–Teacher Conference — Saturday 26 July 2026',
                'content'             => "Dear Parents and Guardians,\n\nYou are warmly invited to attend our Term 2 Parent–Teacher Conference:\n\nDate: Saturday, 26 July 2026\nTime: 8:00 AM – 1:00 PM\nVenue: School Hall and Classrooms\n\nThis is an important opportunity to meet with your child's teachers to discuss academic progress, attendance, and areas for improvement.\n\nAppointment slips will be distributed through your child by Friday, 18 July. If you have not received a slip, please contact the school office at 9807645.\n\nLight refreshments will be provided. We look forward to seeing you.",
                'priority'            => 'Important',
                'attachment'          => null,
                'attachment_type'     => null,
                'attachment_name'     => null,
                'expires_at'          => '2026-07-26 23:59:59',
                'announcement_status' => 'Active',
                'created_at'          => '2026-07-13 09:15:00',
                'updated_at'          => '2026-07-13 09:15:00',
            ],
            [
                'sch_id_fk'           => 12,
                'posted_by'           => 1,
                'title'               => 'Congratulations — National Mathematics Olympiad Winners 2026',
                'content'             => "It is with immense pride that we announce our students' outstanding achievements at the 2026 National Mathematics Olympiad held in Suva last week.\n\nGold Medal: Mere Tuivaga (Year 13A)\nSilver Medal: Jone Nailatikau (Year 12B)\nBronze Medal: Ana Cakobau (Year 13A)\n\nOur team placed 2nd overall out of 47 schools nationwide — the best result in our school's history.\n\nA special assembly to celebrate their success will be held on Friday, 18 July at 10:00 AM. All students and staff are encouraged to attend.\n\nWell done and vinaka vakalevu!",
                'priority'            => 'Info',
                'attachment'          => null,
                'attachment_type'     => null,
                'attachment_name'     => null,
                'expires_at'          => '2026-08-16 23:59:59',
                'announcement_status' => 'Active',
                'created_at'          => '2026-07-11 11:00:00',
                'updated_at'          => '2026-07-11 11:00:00',
            ],
            [
                'sch_id_fk'           => 12,
                'posted_by'           => 1,
                'title'               => 'Revised Canteen Operating Hours — Effective 21 July 2026',
                'content'             => "Please be advised that the school canteen will be operating under revised hours effective Monday, 21 July 2026:\n\nBreakfast Service: 7:00 AM – 7:45 AM\nRecess: 10:00 AM – 10:30 AM\nLunch: 12:30 PM – 1:15 PM\n\nThe canteen will NO LONGER be open during class time. Students are reminded that leaving class to purchase food during teaching hours is not permitted.\n\nA revised menu with improved healthy meal options has been introduced in line with the Ministry of Education's Healthy Schools Initiative. Menus are displayed at the canteen window.\n\nFor catering enquiries please contact the school office.",
                'priority'            => 'Info',
                'attachment'          => null,
                'attachment_type'     => null,
                'attachment_name'     => null,
                'expires_at'          => null,
                'announcement_status' => 'Active',
                'created_at'          => '2026-07-10 14:00:00',
                'updated_at'          => '2026-07-10 14:00:00',
            ],
        ];

        $this->db->table('school_announcement')->insertBatch($rows);

        echo "Inserted " . count($rows) . " sample announcements.\n";
    }
}
