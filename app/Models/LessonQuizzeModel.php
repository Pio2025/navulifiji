<?php
namespace App\Models;
use CodeIgniter\Model;

class LessonQuizzeModel extends Model
{
    protected $table      = 'lesson_quizze';
    protected $primaryKey = 'lesson_quizze_id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'lesson_id_fk', 'assessment_type', 'quizze_name', 'quizze_duration', 'quizze_status',
    ];

    public function getQuizzesWithQuestionsForLesson(int $lessonId): array
    {
        $db      = \Config\Database::connect();
        $quizzes = $db->table('lesson_quizze')
            ->where('lesson_id_fk', $lessonId)
            ->orderBy('lesson_quizze_id', 'ASC')
            ->get()->getResultArray();

        foreach ($quizzes as &$quiz) {
            $quiz['questions'] = $this->loadQuestionsForQuiz((int) $quiz['lesson_quizze_id']);
        }

        return $quizzes;
    }

    public function getQuizWithQuestions(int $quizId): ?array
    {
        $db   = \Config\Database::connect();
        $quiz = $db->table('lesson_quizze')
            ->where('lesson_quizze_id', $quizId)
            ->get()->getRowArray();

        if (!$quiz) return null;

        $quiz['questions'] = $this->loadQuestionsForQuiz($quizId);

        return $quiz;
    }

    public function loadQuestionsForQuiz(int $quizId): array
    {
        $db        = \Config\Database::connect();
        $questions = $db->table('lesson_quizze_question')
            ->where('lesson_quizze_id_fk', $quizId)
            ->orderBy('quizze_quest_id', 'ASC')
            ->get()->getResultArray();

        foreach ($questions as &$q) {
            $q['files'] = $db->table('lesson_quizze_question_file')
                ->where('quizze_quest_id_fk', $q['quizze_quest_id'])
                ->get()->getResultArray();
            $q['answers'] = $db->table('lesson_quizze_answer')
                ->where('quizze_quest_id_fk', $q['quizze_quest_id'])
                ->get()->getResultArray();
        }

        return $questions;
    }
}
