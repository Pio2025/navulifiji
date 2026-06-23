<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Quiz Transcript — <?= esc($quiz['quizze_name']) ?></title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 13px; color: #333; background: #fff; padding: 30px; }
h1 { font-size: 20px; color: #1a1a2e; margin-bottom: 4px; }
h2 { font-size: 14px; color: #555; font-weight: normal; margin-bottom: 20px; }
.header { border-bottom: 2px solid #1e88e5; padding-bottom: 14px; margin-bottom: 20px; }
.meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 24px; margin-bottom: 20px; }
.meta-row { display: flex; gap: 8px; }
.meta-label { color: #888; min-width: 110px; font-size: 12px; }
.meta-value { font-weight: bold; font-size: 12px; }
.score-box { background: #f5f5f5; border-left: 4px solid #1e88e5; padding: 12px 16px; margin-bottom: 22px; display: flex; align-items: center; gap: 24px; }
.score-pct { font-size: 36px; font-weight: bold; color: <?php
    $sc = (float) $attempt['score'];
    echo $sc >= 80 ? '#2e7d32' : ($sc >= 50 ? '#e65100' : '#c62828');
?>; }
.score-label { font-size: 12px; color: #777; }
.score-detail { font-size: 14px; font-weight: bold; margin-top: 2px; }
.question-block { margin-bottom: 18px; page-break-inside: avoid; border: 1px solid #e0e0e0; border-radius: 4px; padding: 12px 14px; }
.question-block.correct { border-left: 4px solid #2e7d32; }
.question-block.incorrect { border-left: 4px solid #c62828; }
.question-block.unanswered { border-left: 4px solid #bdbdbd; }
.q-num { display: inline-block; background: #1e88e5; color: #fff; font-size: 11px; font-weight: bold; padding: 2px 8px; border-radius: 3px; margin-bottom: 6px; }
.q-text { font-size: 13px; color: #222; line-height: 1.5; margin-bottom: 10px; }
.answer-row { display: flex; align-items: flex-start; gap: 8px; font-size: 12px; padding: 4px 6px; border-radius: 3px; margin-bottom: 3px; }
.answer-row.correct-answer { background: #e8f5e9; }
.answer-row.wrong-selected { background: #ffebee; }
.answer-letter { font-weight: bold; min-width: 18px; }
.badge { font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 8px; }
.badge-correct { background: #e8f5e9; color: #2e7d32; }
.badge-wrong { background: #ffebee; color: #c62828; }
.badge-na { background: #f5f5f5; color: #888; }
.footer { margin-top: 30px; padding-top: 12px; border-top: 1px solid #e0e0e0; font-size: 11px; color: #999; }
@media print {
    body { padding: 10px; }
    .no-print { display: none; }
}
</style>
</head>
<body>
<?php
$score       = (float) $attempt['score'];
$correct     = (int) $attempt['correct_answers'];
$total       = (int) $attempt['total_questions'];
$responded   = count($attempt['responses'] ?? []);
$status      = $attempt['status'];
$statusLabel = $status === 'timed_out' ? 'Timed Out' : 'Submitted';
$submittedAt = $attempt['submitted_at'] ? date('M j, Y g:i A', strtotime($attempt['submitted_at'])) : '—';
$startedAt   = $attempt['started_at'] ? date('M j, Y g:i A', strtotime($attempt['started_at'])) : '—';

// Build response map
$responseMap = [];
foreach ($attempt['responses'] as $r) {
    $responseMap[(int) $r['question_id_fk']] = $r;
}
$letters = ['A','B','C','D','E'];
?>

<div class="header">
    <h1><?= esc($quiz['quizze_name']) ?></h1>
    <h2>Quiz Transcript &mdash; <?= esc($lesson['lesson_title']) ?></h2>
</div>

<div class="meta-grid">
    <div class="meta-row"><span class="meta-label">Student:</span><span class="meta-value"><?= esc($studentName) ?></span></div>
    <div class="meta-row"><span class="meta-label">Status:</span><span class="meta-value"><?= $statusLabel ?></span></div>
    <div class="meta-row"><span class="meta-label">Started:</span><span class="meta-value"><?= $startedAt ?></span></div>
    <div class="meta-row"><span class="meta-label">Submitted:</span><span class="meta-value"><?= $submittedAt ?></span></div>
    <div class="meta-row"><span class="meta-label">Duration:</span><span class="meta-value"><?= (int) $quiz['quizze_duration'] > 0 ? (int) $quiz['quizze_duration'] . ' min' : 'No limit' ?></span></div>
    <div class="meta-row"><span class="meta-label">Questions:</span><span class="meta-value"><?= $total ?></span></div>
</div>

<div class="score-box">
    <div>
        <div class="score-label">Final Score</div>
        <div class="score-pct"><?= number_format($score, 1) ?>%</div>
    </div>
    <div>
        <div class="score-label">Correct Answers</div>
        <div class="score-detail"><?= $correct ?> / <?= $total ?></div>
    </div>
    <div>
        <div class="score-label">Unanswered</div>
        <div class="score-detail"><?= $total - $responded ?></div>
    </div>
</div>

<div class="no-print" style="margin-bottom:16px;">
    <button onclick="window.print()" style="padding:8px 16px;background:#1e88e5;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:13px;">
        Print / Save as PDF
    </button>
    <button onclick="window.close()" style="padding:8px 16px;background:#f5f5f5;color:#333;border:1px solid #ddd;border-radius:4px;cursor:pointer;font-size:13px;margin-left:8px;">
        Close
    </button>
</div>

<?php $qi = 0; foreach ($quiz['questions'] as $q):
    $qi++;
    $qId       = (int) $q['quizze_quest_id'];
    $response  = $responseMap[$qId] ?? null;
    $isCorrect = $response && (int) $response['is_correct'] === 1;
    $isAnswered= $response !== null;
    $blockCls  = !$isAnswered ? 'unanswered' : ($isCorrect ? 'correct' : 'incorrect');
?>
<div class="question-block <?= $blockCls ?>">
    <div>
        <span class="q-num">Q<?= $qi ?></span>
        <?php if (!$isAnswered): ?>
        <span class="badge badge-na">Not Answered</span>
        <?php elseif ($isCorrect): ?>
        <span class="badge badge-correct">✓ Correct</span>
        <?php else: ?>
        <span class="badge badge-wrong">✗ Incorrect</span>
        <?php endif; ?>
    </div>
    <div class="q-text"><?= nl2br(esc($q['question'])) ?></div>
    <?php if (!empty($q['files'])): ?>
    <div style="display:flex;flex-wrap:wrap;gap:10px;margin:8px 0 10px 0;">
        <?php foreach ($q['files'] as $f): ?>
        <img src="<?= base_url('uploads/quiz_files/' . $f['file_src']) ?>"
             style="max-height:140px;max-width:220px;object-fit:contain;border:1px solid #e0e0e0;border-radius:4px;" alt="">
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php foreach ($q['answers'] as $ai => $ans):
        $aId     = (int) $ans['lesson_quizze_answer_id'];
        $selected= $response && (int) $response['answer_id_fk'] === $aId;
        $isRight = (int) $ans['is_correct_answer'] === 1;
        $rowCls  = $isRight ? 'correct-answer' : ($selected && !$isRight ? 'wrong-selected' : '');
    ?>
    <div class="answer-row <?= $rowCls ?>">
        <span class="answer-letter"><?= $letters[$ai] ?? chr(65 + $ai) ?>.</span>
        <span><?= esc($ans['answer']) ?></span>
        <?php if ($selected): ?><strong style="margin-left:auto;font-size:11px;"><?= $isRight ? '✓ Your answer (Correct)' : '✗ Your answer' ?></strong><?php endif; ?>
        <?php if ($isRight && !$selected): ?><span style="margin-left:auto;font-size:11px;color:#2e7d32;">✓ Correct answer</span><?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

<div class="footer">
    Generated on <?= date('M j, Y g:i A') ?> &mdash; <?= esc($studentName) ?> &mdash; <?= esc($quiz['quizze_name']) ?>
</div>
</body>
</html>
