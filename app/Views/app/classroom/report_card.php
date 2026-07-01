<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6 d-print-none">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Report Card — Term <?= $term ?>
            </h1>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= site_url('classroom/report/' . $classroom['class_id'] . '/student/' . $student['user_id'] . '/term/' . $term . '/pdf') ?>"
               target="_blank" class="btn btn-sm btn-primary">
                <i class="ki-duotone ki-printer fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>Print / PDF
            </a>
            <a href="javascript:history.back()" class="btn btn-sm btn-light">
                <i class="ki-duotone ki-arrow-left fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>Back
            </a>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?php
$rep      = $report;
$marks    = $rep['marks']    ?? [];
$totalE   = $rep['total_earned']   ?? 0;
$totalP   = $rep['total_possible'] ?? 0;
$ovPct    = $rep['overall_pct']    ?? null;
$grade    = $ovPct !== null ? \App\Models\TermExamModel::grade($ovPct) : null;
$gColor   = $grade  ? \App\Models\TermExamModel::gradeColor($grade)  : 'secondary';

if (!function_exists('ordinal')) {
    function ordinal(int $n): string {
        if ($n % 100 >= 11 && $n % 100 <= 13) return $n . 'th';
        return $n . match($n % 10) { 1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th' };
    }
}
?>

<!--begin::Report Card-->
<div class="card border-0 shadow-sm" style="max-width:800px;margin:0 auto;" id="report_card_print">
    <div class="card-body p-8">

        <!--begin::Header-->
        <div class="d-flex align-items-center gap-4 mb-6 pb-6" style="border-bottom:2px solid #e2e8f0;">
            <!--begin::School logo (left)-->
            <?php if (!empty($classroom['sch_logo'])): ?>
            <img src="<?= base_url('uploads/school/logo/' . $classroom['sch_logo']) ?>"
                 style="width:70px;height:70px;object-fit:contain;flex-shrink:0;" alt="School Logo" />
            <?php else: ?>
            <div class="symbol symbol-70px flex-shrink-0">
                <div class="symbol-label bg-light-primary">
                    <i class="ki-duotone ki-book fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <?php endif; ?>
            <!--end::School logo-->

            <!--begin::Centre text-->
            <div class="text-center flex-grow-1">
                <div class="fw-bold text-gray-900 fs-3"><?= esc($classroom['sch_name']) ?></div>
                <div class="text-muted fs-6 mt-1">TERM <?= $term ?> EXAMINATION REPORT CARD</div>
                <div class="text-muted fs-7"><?= esc($classroom['class_name']) ?> — <?= esc($classroom['class_year']) ?></div>
                <?php
                $contactParts = array_filter([
                    $classroom['sch_address'] ?? '',
                    !empty($classroom['sch_phone']) ? 'Ph: ' . $classroom['sch_phone'] : '',
                    $classroom['sch_email'] ?? '',
                ]);
                if (!empty($contactParts)): ?>
                <div class="text-muted fs-8 mt-1"><?= esc(implode('  |  ', $contactParts)) ?></div>
                <?php endif; ?>
            </div>
            <!--end::Centre text-->

            <!--begin::Navuli logo (right)-->
            <img src="<?= base_url('icon.png') ?>"
                 style="width:70px;height:70px;object-fit:contain;flex-shrink:0;" alt="Navuli" />
            <!--end::Navuli logo-->
        </div>
        <!--end::Header-->

        <!--begin::Student info-->
        <div class="row g-4 mb-6 pb-5" style="border-bottom:1px solid #f1f1f4;">
            <div class="col-md-2 text-center">
                <?php if (!empty($student['profile_photo'])): ?>
                <img src="<?= base_url('uploads/profilePhoto/'.$student['profile_photo']) ?>"
                     class="student-photo"
                     style="width:80px;height:80px;object-fit:cover;border-radius:4px !important;" />
                <?php else: ?>
                <div class="d-flex align-items-center justify-content-center bg-light-primary fw-bold text-primary fs-3 mx-auto"
                     style="width:80px;height:80px;border-radius:4px;">
                    <?= strtoupper(substr($student['fname'],0,1).substr($student['lname'],0,1)) ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-md-5">
                <table class="table table-borderless fs-7 mb-0">
                    <tr><td class="text-muted pe-3 py-1" style="width:110px;">Full Name</td><td class="fw-bold py-1"><?= esc($student['fname'].' '.$student['lname']) ?></td></tr>
                    <tr><td class="text-muted pe-3 py-1">Class</td><td class="fw-bold py-1"><?= esc($classroom['class_name']) ?></td></tr>
                    <tr><td class="text-muted pe-3 py-1">Year</td><td class="fw-bold py-1"><?= esc($classroom['class_year']) ?></td></tr>
                </table>
            </div>
            <div class="col-md-5">
                <?php if ($ovPct !== null): ?>
                <?php $st0 = $stats ?? []; ?>
                <div class="d-flex flex-column align-items-center justify-content-center p-3 rounded-2"
                     style="background:#f8fafc;border:2px solid #e2e8f0;">
                    <div class="fw-bold text-<?= $gColor ?> lh-1 mb-2" style="font-size:1.5rem;"><?= round($totalE,1) ?> / <?= round($totalP,1) ?></div>
                    <div class="d-flex w-100" style="border-top:1px solid #dde4ed;padding-top:6px;gap:0;">
                        <?php if (!empty($st0['position'])): ?>
                        <div class="text-center flex-fill" style="border-right:1px solid #dde4ed;">
                            <div class="fw-bold text-primary" style="font-size:.9rem;line-height:1.2;"><?= (int)$st0['position'] ?></div>
                            <div class="text-muted" style="font-size:.55rem;letter-spacing:.07em;">CLASS POSITION</div>
                        </div>
                        <?php endif; ?>
                        <div class="text-center flex-fill">
                            <div class="fw-bold text-<?= $gColor ?>" style="font-size:.9rem;line-height:1.2;"><?= $grade ?></div>
                            <div class="text-muted" style="font-size:.55rem;letter-spacing:.07em;">GRADE</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Student info-->

        <!--begin::Marks table-->
        <div class="mb-6">
            <div class="fw-bold text-gray-700 fs-6 mb-3">Subject Results</div>
            <table class="table table-bordered align-middle fs-7" style="border-color:#e2e8f0;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="fw-bold ps-3">Subject</th>
                        <th class="fw-bold text-center" style="width:80px;">Mark</th>
                        <th class="fw-bold text-center" style="width:80px;">Total</th>
                        <th class="fw-bold text-center" style="width:70px;">%</th>
                        <th class="fw-bold text-center" style="width:60px;">Grade</th>
                        <th class="fw-bold">Teacher Comment</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($marks)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No marks recorded.</td></tr>
                <?php else: ?>
                <?php foreach ($marks as $m):
                    $mp  = ($m['mark'] !== null && $m['total_mark'] > 0) ? round(($m['mark']/$m['total_mark'])*100,1) : null;
                    $mg  = $mp !== null ? \App\Models\TermExamModel::grade($mp) : '—';
                    $mgc = $mp !== null ? \App\Models\TermExamModel::gradeColor($mg) : 'secondary';
                ?>
                <tr>
                    <td class="fw-semibold ps-3"><?= esc($m['subject_name']) ?></td>
                    <td class="text-center fw-bold"><?= $m['mark'] !== null ? $m['mark'] : '—' ?></td>
                    <td class="text-center text-muted"><?= $m['total_mark'] ?></td>
                    <td class="text-center"><?= $mp !== null ? $mp.'%' : '—' ?></td>
                    <td class="text-center">
                        <span class="badge badge-light-<?= $mgc ?>"><?= $mg ?></span>
                    </td>
                    <td class="text-muted fst-italic"><?= $m['teacher_comment'] ? esc($m['teacher_comment']) : '' ?></td>
                </tr>
                <?php endforeach; ?>
                <!-- Total row -->
                <tr style="background:#f8fafc;border-top:2px solid #e2e8f0;">
                    <td class="fw-bold ps-3">Overall</td>
                    <td class="text-center fw-bold"><?= round($totalE,1) ?></td>
                    <td class="text-center fw-bold"><?= round($totalP,1) ?></td>
                    <td class="text-center fw-bold"><?= $ovPct !== null ? $ovPct.'%' : '—' ?></td>
                    <td class="text-center">
                        <?php if ($grade): ?>
                        <span class="badge badge-light-<?= $gColor ?> fw-bold"><?= $grade ?></span>
                        <?php endif; ?>
                    </td>
                    <td></td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!--end::Marks table-->

        <!--begin::Grade scale reference-->
        <div class="d-flex flex-wrap gap-2 mb-4 fs-9 text-muted">
            <span class="fw-bold me-1">Grade Scale:</span>
            <span>A+ ≥ 90%</span> · <span>A ≥ 80%</span> · <span>B ≥ 70%</span> ·
            <span class="text-info fw-semibold">C ≥ 50% (Pass)</span> · <span class="text-danger">F &lt; 50% (Fail)</span>
        </div>
        <!--end::Grade scale-->

        <!--begin::Class Stats-->
        <?php
        $st = $stats ?? [];
        $statItems = [
            ['label' => 'NO. SAT',   'value' => (int)($st['number_sat']  ?? 0), 'color' => 'dark'],
            ['label' => 'PASS',      'value' => (int)($st['number_pass'] ?? 0), 'color' => 'success'],
            ['label' => 'FAIL',      'value' => (int)($st['number_fail'] ?? 0), 'color' => 'danger'],
        ];
        if (($st['number_absent'] ?? 0) > 0) {
            $statItems[] = ['label' => 'ABSENT', 'value' => (int)$st['number_absent'], 'color' => 'warning'];
        }
        $statItems[] = ['label' => 'PASS %',     'value' => ($st['pct_pass']  ?? 0).'%', 'color' => 'info'];
        $statItems[] = ['label' => 'CLASS AVG',  'value' => $st['avg_score'] !== null ? $st['avg_score'].'%' : '—', 'color' => 'primary'];
        ?>
        <div class="mb-3 px-3 py-1 rounded-2" style="background:#f8fafc;border:1px solid #e2e8f0;">
            <div class="d-flex flex-wrap align-items-center gap-0">
                <div class="text-muted fw-bold me-2" style="font-size:.58rem;letter-spacing:.08em;">CLASS STATS</div>
                <?php foreach ($statItems as $si): ?>
                <div class="text-center px-2 py-1" style="border-left:1px solid #e2e8f0;min-width:52px;">
                    <div class="fw-bold text-<?= $si['color'] ?>" style="font-size:.78rem;line-height:1.2;"><?= $si['value'] ?></div>
                    <div class="text-muted" style="font-size:.54rem;letter-spacing:.06em;"><?= $si['label'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!--end::Class Stats-->

        <!--begin::Comments (equal height)-->
        <div class="row g-3 mb-5 align-items-stretch">
            <div class="col-6">
                <div class="p-3 rounded-2 h-100 d-flex flex-column" style="background:#f0f4ff;border:1px solid #c7d2fe;">
                    <div class="fw-bold text-gray-700 fs-8 mb-2">Class Teacher Comment</div>
                    <div class="text-gray-600 fs-8 lh-lg flex-grow-1">
                        <?php if ($rep['ct_comment']): ?>
                        <?= nl2br(esc($rep['ct_comment'])) ?>
                        <?php else: ?>
                        <span class="text-muted fst-italic">No comment.</span>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3 pt-2" style="border-top:1px dashed #c7d2fe;">
                        <div class="text-muted fs-9">Signature: _________________________</div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 rounded-2 h-100 d-flex flex-column" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                    <div class="fw-bold text-gray-700 fs-8 mb-2">Principal Comment</div>
                    <div class="text-gray-600 fs-8 lh-lg flex-grow-1">
                        <?php if ($rep['principal_comment']): ?>
                        <?= nl2br(esc($rep['principal_comment'])) ?>
                        <?php else: ?>
                        <span class="text-muted fst-italic">No comment.</span>
                        <?php endif; ?>
                    </div>
                    <div class="mt-3 pt-2" style="border-top:1px dashed #bbf7d0;">
                        <div class="text-muted fs-9">Signature: _________________________</div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Comments-->

        <!--begin::Footer-->
        <div class="d-flex align-items-center justify-content-between pt-5" style="border-top:2px solid #e2e8f0;">
            <div class="text-muted fs-9">
                <?= esc($classroom['sch_name']) ?> · Term <?= $term ?>, <?= esc($classroom['class_year']) ?>
                <?php if ($rep['published_at']): ?>
                · Published <?= date('d M Y', strtotime($rep['published_at'])) ?>
                <?php endif; ?>
            </div>
            <div class="text-muted fs-9">
                Parent/Guardian Signature: _________________________
            </div>
        </div>
        <!--end::Footer-->

    </div>
</div>
<!--end::Report Card-->

</div>
</div>

<style>
#report_card_print img.student-photo {
    border-radius: 4px !important;
}
@media print {
    .app-header, .app-sidebar, #kt_app_toolbar, .app-footer, .d-print-none { display: none !important; }
    #kt_app_content { padding: 0 !important; }
    #kt_app_content_container { padding: 0 !important; max-width: 100% !important; }
    #report_card_print { box-shadow: none !important; border: none !important; }
    .badge { border: 1px solid #ccc; }
    @page { margin: 1.5cm; }
}
</style>
