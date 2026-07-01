<?php if (!empty($title)): ?>
<h4 class="fw-bold fs-6 text-gray-800 mb-4"><?= esc($title) ?></h4>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-2 fs-7">
        <thead>
            <tr class="fw-bold text-muted">
                <th class="min-w-200px">Exam</th>
                <th>Level</th>
                <th>Year</th>
                <th>Term</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($studentExams as $se): ?>
            <tr>
                <td class="fw-semibold text-gray-900"><?= esc($se['exam_name']) ?></td>
                <td class="text-muted"><?= esc($se['level_name'] ?? '—') ?></td>
                <td class="text-muted"><?= esc($se['exam_year']) ?></td>
                <td class="text-muted">Term <?= esc($se['exam_term']) ?></td>
                <td>
                    <span class="badge badge-light-<?= $se['student_exam_status'] === 'Active' ? 'success' : 'secondary' ?>">
                        <?= esc($se['student_exam_status']) ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
