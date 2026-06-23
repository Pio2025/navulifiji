<?php
$statuses = [
    'Present'              => ['class' => 'success',  'icon' => 'ki-check-circle'],
    'Absent'               => ['class' => 'danger',   'icon' => 'ki-cross-circle'],
    'Sick'                 => ['class' => 'warning',  'icon' => 'ki-heart-circle'],
    'Family Obligation'    => ['class' => 'info',     'icon' => 'ki-people'],
    'Transportation Issue' => ['class' => 'warning',  'icon' => 'ki-car'],
    'Bullying Related'     => ['class' => 'danger',   'icon' => 'ki-shield-cross'],
    'Suspension'           => ['class' => 'danger',   'icon' => 'ki-lock'],
    'No Reason'            => ['class' => 'secondary','icon' => 'ki-question-2'],
];
?>
<!--begin::Detail Table-->
<div class="table-responsive">
    <table class="table table-row-dashed table-row-gray-200 align-middle gs-0 mb-0">
        <thead>
            <tr class="fw-bold text-muted bg-light">
                <th class="ps-6 min-w-55px rounded-start">Photo</th>
                <th class="min-w-160px">Student</th>
                <th class="min-w-155px">Status</th>
                <th class="min-w-180px">Note</th>
                <th class="min-w-60px text-center">Files</th>
                <th class="min-w-110px text-end pe-4 rounded-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($records as $rec):
            $id       = (int) $rec['stud_att_id'];
            $fullName = trim(($rec['fname'] ?? '') . ' ' . ($rec['lname'] ?? '') . ' ' . ($rec['oname'] ?? ''));
            $photo    = !empty($rec['profile_photo'])
                        ? base_url('uploads/profilePhoto/' . $rec['profile_photo'])
                        : base_url('app/assets/media/avatars/blank.png');
            $curStatus = $rec['attendance_status'] ?? 'Present';
            $statusCfg = $statuses[$curStatus] ?? ['class' => 'secondary', 'icon' => 'ki-question-2'];
            $files     = $rec['files'] ?? [];
        ?>
        <tr id="att-row-<?= $id ?>">
            <!--begin::Photo-->
            <td class="ps-6">
                <div class="symbol symbol-40px symbol-circle">
                    <img src="<?= $photo ?>"
                         alt="<?= esc($fullName) ?>"
                         onerror="this.src='<?= base_url('app/assets/media/avatars/blank.png') ?>'"
                         class="w-40px h-40px rounded-circle object-fit-cover" />
                </div>
            </td>
            <!--end::Photo-->

            <!--begin::Name-->
            <td>
                <span class="fw-semibold text-gray-800 fs-6"><?= esc($fullName) ?></span>
            </td>
            <!--end::Name-->

            <!--begin::Status-->
            <td>
                <select class="form-select form-select-sm form-select-solid att-status-select"
                        style="min-width:150px;">
                    <?php foreach ($statuses as $sVal => $sCfg): ?>
                    <option value="<?= $sVal ?>" <?= $curStatus === $sVal ? 'selected' : '' ?>>
                        <?= $sVal ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <!--end::Status-->

            <!--begin::Note-->
            <td>
                <input type="text"
                       class="form-control form-control-sm form-control-solid att-note-input"
                       value="<?= esc($rec['attendance_note'] ?? '') ?>"
                       placeholder="Optional note…"
                       maxlength="500" />
            </td>
            <!--end::Note-->

            <!--begin::Files-->
            <td class="text-center">
                <button type="button"
                        class="btn btn-sm btn-icon btn-light-primary position-relative"
                        data-bs-toggle="collapse"
                        data-bs-target="#files-panel-<?= $id ?>"
                        title="<?= count($files) ?> file(s)">
                    <i class="ki-duotone ki-paper-clip fs-4">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <?php if (count($files) > 0): ?>
                    <span class="badge badge-circle badge-primary position-absolute top-0 start-100 translate-middle"
                          style="font-size:9px;min-width:16px;height:16px;line-height:16px;padding:0;">
                        <?= count($files) ?>
                    </span>
                    <?php endif; ?>
                </button>
            </td>
            <!--end::Files-->

            <!--begin::Actions-->
            <td class="text-end pe-4">
                <button type="button"
                        class="btn btn-sm btn-light-primary btn-save-row me-1"
                        data-id="<?= $id ?>">
                    Save
                </button>
                <button type="button"
                        class="btn btn-sm btn-icon btn-light-danger btn-delete-row"
                        data-id="<?= $id ?>"
                        title="Delete this record">
                    <i class="ki-duotone ki-trash fs-5">
                        <span class="path1"></span><span class="path2"></span>
                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                    </i>
                </button>
            </td>
            <!--end::Actions-->
        </tr>

        <!--begin::Files Panel (collapsible row)-->
        <tr>
            <td colspan="6" class="p-0 border-0">
                <div class="collapse" id="files-panel-<?= $id ?>">
                    <div class="bg-light-primary rounded mx-4 mb-3 p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-semibold text-primary fs-7">
                                <i class="ki-duotone ki-paper-clip fs-5 me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                Files for <?= esc($fullName) ?>
                            </span>
                            <button type="button"
                                    class="btn btn-sm btn-light-primary btn-upload-more"
                                    data-att-id="<?= $id ?>"
                                    data-student-name="<?= esc($fullName) ?>">
                                <i class="ki-duotone ki-cloud-upload fs-5 me-1">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                                Upload More
                            </button>
                        </div>

                        <div id="file-list-<?= $id ?>">
                        <?php if (empty($files)): ?>
                            <p class="text-muted fs-7 mb-0">No files uploaded for this student.</p>
                        <?php else: ?>
                            <?php foreach ($files as $f):
                                $fileUrl  = base_url('uploads/attendance/' . $f['stud_att_file_src']);
                                $ext      = strtolower($f['stud_att_file_type'] ?? '');
                                $isImage  = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                            ?>
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light"
                                 id="file-item-<?= $f['stud_att_file_id'] ?>">
                                <a href="<?= $fileUrl ?>" target="_blank"
                                   class="d-flex align-items-center gap-2 text-gray-700 text-hover-primary">
                                    <?php if ($isImage): ?>
                                    <img src="<?= $fileUrl ?>" alt="file"
                                         style="width:32px;height:32px;object-fit:cover;border-radius:4px;" />
                                    <?php else: ?>
                                    <i class="ki-duotone ki-file fs-2x text-primary">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <?php endif; ?>
                                    <span class="fs-7"><?= esc($f['stud_att_file_src']) ?></span>
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-icon btn-light-danger btn-delete-file"
                                        data-file-id="<?= $f['stud_att_file_id'] ?>"
                                        title="Delete file">
                                    <i class="ki-duotone ki-trash fs-5">
                                        <span class="path1"></span><span class="path2"></span>
                                        <span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                    </i>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <!--end::Files Panel-->

        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!--end::Detail Table-->
