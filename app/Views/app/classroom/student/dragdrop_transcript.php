<?php
$score       = (float) $attempt['score'];
$correct     = (int)   $attempt['correct_items'];
$total       = (int)   $attempt['total_items'];
$submittedAt = $attempt['submitted_at'] ? date('M j, Y g:i A', strtotime($attempt['submitted_at'])) : '—';
$startedAt   = $attempt['started_at']   ? date('M j, Y g:i A', strtotime($attempt['started_at'])) : '—';
$IMG_BASE    = base_url('uploads/dragdrop_files/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Assessment Transcript — <?= esc($quiz['quizze_name']) ?></title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 13px; color: #333; background: #fff; padding: 30px; }
h1 { font-size: 20px; color: #1a1a2e; margin-bottom: 4px; }
h2 { font-size: 14px; color: #555; font-weight: normal; margin-bottom: 20px; }
.header { border-bottom: 2px solid #1e88e5; padding-bottom: 14px; margin-bottom: 20px; }
.type-badge { display:inline-block; background:#e3f2fd; color:#1565c0; font-size:10px; font-weight:bold; padding:2px 8px; border-radius:3px; margin-bottom:8px; }
.meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 24px; margin-bottom: 20px; }
.meta-row { display: flex; gap: 8px; }
.meta-label { color: #888; min-width: 110px; font-size: 12px; }
.meta-value { font-weight: bold; font-size: 12px; }
.score-box { background: #f5f5f5; border-left: 4px solid #1e88e5; padding: 12px 16px; margin-bottom: 22px; display: flex; align-items: center; gap: 24px; }
.score-pct { font-size: 36px; font-weight: bold; color: <?php echo $score >= 80 ? '#2e7d32' : ($score >= 50 ? '#e65100' : '#c62828'); ?>; }
.score-label { font-size: 12px; color: #777; }
.score-detail { font-size: 14px; font-weight: bold; margin-top: 2px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
th { background: #f5f5f5; font-size: 11px; font-weight: bold; padding: 6px 10px; text-align: left; border-bottom: 2px solid #e0e0e0; }
td { font-size: 12px; padding: 8px 10px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
.correct-row { background: #f1f8f4; }
.incorrect-row { background: #fff5f5; }
.result-correct { color: #2e7d32; font-weight: bold; }
.result-incorrect { color: #c62828; font-weight: bold; }
.result-notplaced { color: #888; }
.item-img { width: 32px; height: 32px; object-fit: cover; border-radius: 4px; border: 1px solid #e0e0e0; }
.footer { margin-top: 30px; padding-top: 12px; border-top: 1px solid #e0e0e0; font-size: 11px; color: #999; }
@media print {
    body { padding: 10px; }
    .no-print { display: none; }
}
</style>
</head>
<body>

<div class="header">
    <div class="type-badge">Drag &amp; Drop Assessment</div>
    <h1><?= esc($quiz['quizze_name']) ?></h1>
    <h2>Assessment Transcript &mdash; <?= esc($lesson['lesson_title']) ?></h2>
</div>

<div class="meta-grid">
    <div class="meta-row"><span class="meta-label">Student:</span><span class="meta-value"><?= esc($studentName) ?></span></div>
    <div class="meta-row"><span class="meta-label">Status:</span><span class="meta-value">Submitted</span></div>
    <div class="meta-row"><span class="meta-label">Started:</span><span class="meta-value"><?= $startedAt ?></span></div>
    <div class="meta-row"><span class="meta-label">Submitted:</span><span class="meta-value"><?= $submittedAt ?></span></div>
    <div class="meta-row"><span class="meta-label">Duration:</span><span class="meta-value"><?= (int) $quiz['quizze_duration'] > 0 ? (int) $quiz['quizze_duration'] . ' min' : 'No limit' ?></span></div>
    <div class="meta-row"><span class="meta-label">Total Items:</span><span class="meta-value"><?= $total ?></span></div>
</div>

<div class="score-box">
    <div>
        <div class="score-label">Final Score</div>
        <div class="score-pct"><?= number_format($score, 1) ?>%</div>
    </div>
    <div>
        <div class="score-label">Correct Placements</div>
        <div class="score-detail"><?= $correct ?> / <?= $total ?></div>
    </div>
    <div>
        <div class="score-label">Incorrect / Not Placed</div>
        <div class="score-detail"><?= $total - $correct ?></div>
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

<table>
    <thead>
        <tr>
            <th style="width:28px;">#</th>
            <th style="width:44px;">Image</th>
            <th>Item</th>
            <th>Your Placement</th>
            <th>Correct Zone</th>
            <th style="width:80px;">Result</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($attempt['items'] as $idx => $item): ?>
    <tr class="<?= $item['is_correct'] ? 'correct-row' : ($item['is_placed'] ? 'incorrect-row' : '') ?>">
        <td><?= $idx + 1 ?></td>
        <td>
            <?php if (!empty($item['item_image'])): ?>
            <img src="<?= $IMG_BASE . esc($item['item_image']) ?>" class="item-img" alt="">
            <?php else: ?>
            <span style="color:#bbb;">—</span>
            <?php endif; ?>
        </td>
        <td><?= esc($item['item_text']) ?></td>
        <td>
            <?php if ($item['is_placed']): ?>
            <span class="<?= $item['is_correct'] ? 'result-correct' : 'result-incorrect' ?>">
                <?= esc($item['student_zone_label']) ?>
            </span>
            <?php else: ?>
            <span class="result-notplaced">Not placed</span>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($item['correct_zone_id']): ?>
            <span style="color:#2e7d32;font-weight:bold;"><?= esc($item['correct_zone_label']) ?></span>
            <?php else: ?>
            <span style="color:#888;">Not mapped</span>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($item['is_correct']): ?>
            <span class="result-correct">✓ Correct</span>
            <?php elseif ($item['is_placed']): ?>
            <span class="result-incorrect">✗ Wrong</span>
            <?php else: ?>
            <span class="result-notplaced">— Not placed</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="footer">
    Generated on <?= date('M j, Y g:i A') ?> &mdash; <?= esc($studentName) ?> &mdash; <?= esc($quiz['quizze_name']) ?>
</div>

</body>
</html>
