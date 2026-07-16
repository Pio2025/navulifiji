<?php
namespace App\Models;
use CodeIgniter\Model;

class ConductTypeModel extends Model
{
    protected $table      = 'conduct_types';
    protected $primaryKey = 'type_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'type_name',
        'category',
        'is_positive',
        'default_points',
        'severity_level',
    ];

    public function ensureTables(): void
    {
        $db = \Config\Database::connect();

        $db->query("CREATE TABLE IF NOT EXISTS `conduct_types` (
            `type_id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `type_name`      VARCHAR(150) NOT NULL,
            `category`       VARCHAR(50)  NOT NULL,
            `is_positive`    TINYINT(1)   NOT NULL DEFAULT 1,
            `default_points` INT          NOT NULL DEFAULT 0,
            `severity_level` VARCHAR(20)  NOT NULL DEFAULT 'Positive',
            PRIMARY KEY (`type_id`),
            KEY `category` (`category`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4");

        $count = (int) $db->query("SELECT COUNT(*) AS cnt FROM `conduct_types`")->getRow()->cnt;
        if ($count === 0) {
            $db->query("INSERT INTO `conduct_types`
                (`type_id`,`type_name`,`category`,`is_positive`,`default_points`,`severity_level`) VALUES
                (1,'Excellent Class Participation','Academic',1,5,'Positive'),
                (2,'Significant Academic Improvement','Academic',1,10,'Positive'),
                (3,'Outstanding Homework/Project Submission','Academic',1,5,'Positive'),
                (4,'Perfect Attendance (Weekly/Monthly)','Academic',1,10,'Positive'),
                (5,'Academic Excellence (Top Score in Test/Quiz)','Academic',1,15,'Positive'),
                (6,'Showing Strong Critical Thinking','Academic',1,5,'Positive'),
                (7,'Consistently Prepared for Class','Academic',1,3,'Positive'),
                (8,'Helping a Peer with Classwork','Social',1,5,'Positive'),
                (9,'Demonstrating Excellent Teamwork/Collaboration','Social',1,5,'Positive'),
                (10,'Showing Kindness to a Fellow Student','Social',1,5,'Positive'),
                (11,'Mediating a Conflict Peacefully','Social',1,10,'Positive'),
                (12,'Including an Excluded Student','Social',1,10,'Positive'),
                (13,'Demonstrating Outstanding Leadership','Social',1,10,'Positive'),
                (14,'Being a Positive Role Model','Social',1,5,'Positive'),
                (15,'Exceptional Honesty/Integrity','Personal',1,10,'Positive'),
                (16,'Demonstrating Strong Perseverance/Resilience','Personal',1,5,'Positive'),
                (17,'Taking Initiative Without Being Asked','Personal',1,5,'Positive'),
                (18,'Showing Outstanding Effort','Personal',1,5,'Positive'),
                (19,'Excellent Self-Regulation','Personal',1,5,'Positive'),
                (20,'Demonstrating a Growth Mindset','Personal',1,5,'Positive'),
                (21,'Outstanding Contribution to a School Event','Community',1,10,'Positive'),
                (22,'Excellent Service to the School Community','Community',1,10,'Positive'),
                (23,'Representing the School in Sports/Arts/Academics','Community',1,15,'Positive'),
                (24,'Excellent Stewardship','Community',1,5,'Positive'),
                (25,'Exceptional School Spirit','Community',1,3,'Positive'),
                (26,'Tardiness (Unexcused)','Attendance',0,-2,'Minor'),
                (27,'Truancy/Cutting Class','Attendance',0,-15,'Major'),
                (28,'Leaving School Without Permission','Attendance',0,-15,'Major'),
                (29,'Excessive Absenteeism','Attendance',0,-20,'Major'),
                (30,'Skipping Detention','Attendance',0,-10,'Major'),
                (31,'Disruptive Classroom Behavior','Disrespect',0,-3,'Minor'),
                (32,'Insubordination/Defiance','Disrespect',0,-5,'Minor'),
                (33,'Inappropriate Language/Profanity','Disrespect',0,-5,'Minor'),
                (34,'Disrespect Toward Staff','Disrespect',0,-10,'Major'),
                (35,'Disrespect Toward Students','Disrespect',0,-5,'Minor'),
                (36,'Horseplay/Reckless Behavior','Disrespect',0,-3,'Minor'),
                (37,'Cheating on Tests/Assignments','Academic',0,-20,'Major'),
                (38,'Plagiarism','Academic',0,-20,'Major'),
                (39,'Forgery/Falsifying Documents','Academic',0,-15,'Major'),
                (40,'Lying to School Personnel','Academic',0,-10,'Major'),
                (41,'Sharing Homework Inappropriately','Academic',0,-5,'Minor'),
                (42,'Physical Fighting','Conflict',0,-30,'Critical'),
                (43,'Verbal Altercation/Threats','Conflict',0,-20,'Major'),
                (44,'Bullying (Physical, Verbal, Social)','Conflict',0,-30,'Critical'),
                (45,'Cyberbullying','Conflict',0,-30,'Critical'),
                (46,'Intimidation/Harassment','Conflict',0,-25,'Critical'),
                (47,'Throwing Objects in Anger','Conflict',0,-20,'Major'),
                (48,'Theft','Property',0,-30,'Critical'),
                (49,'Vandalism/Damaging Property','Property',0,-25,'Critical'),
                (50,'Graffiti','Property',0,-20,'Major'),
                (51,'Misuse of School Equipment','Property',0,-10,'Major'),
                (52,'Unauthorized Use of Personal Devices in Class','Property',0,-5,'Minor'),
                (53,'Vaping/Smoking on Campus','Safety',0,-25,'Critical'),
                (54,'Possession of Alcohol/Illegal Substances','Safety',0,-35,'Critical'),
                (55,'Possession of Weapons/Dangerous Objects','Safety',0,-40,'Critical'),
                (56,'Violating Fire/Safety Drills','Safety',0,-15,'Major'),
                (57,'Endangering Others','Safety',0,-25,'Critical'),
                (58,'Dress Code Violation (Minor)','Uniform',0,-2,'Minor'),
                (59,'Dress Code Violation (Repeated)','Uniform',0,-5,'Minor'),
                (60,'Wearing Inappropriate Accessories','Uniform',0,-2,'Minor'),
                (61,'Unauthorized Recording/Photography','Technology',0,-10,'Major'),
                (62,'Inappropriate Internet Use','Technology',0,-15,'Major'),
                (63,'Accessing Prohibited Websites','Technology',0,-10,'Major'),
                (64,'Sharing Passwords/Account Misuse','Technology',0,-10,'Major')");
        }
    }

    public function getAll(): array
    {
        return $this->orderBy('category', 'ASC')->orderBy('type_name', 'ASC')->findAll();
    }

    public function getGroupedByCategory(): array
    {
        $types   = $this->getAll();
        $grouped = [];

        foreach ($types as $type) {
            $grouped[$type['category']][] = $type;
        }

        return $grouped;
    }
}
