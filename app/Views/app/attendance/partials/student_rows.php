<?php
$statuses = ['Present', 'Absent', 'Sick', 'Family Obligation', 'Transportation Issue', 'Bullying Related', 'Suspension', 'No Reason'];
?>
<?php foreach ($students as $student):
    $enrolId     = (int) $student['enrol_id'];
    $fullName    = trim(($student['fname'] ?? '') . ' ' . ($student['lname'] ?? '') . ' ' . ($student['oname'] ?? ''));
    $photo       = !empty($student['profile_photo'])
                    ? base_url('uploads/profilePhoto/' . $student['profile_photo'])
                    : base_url('app/assets/media/avatars/blank.png');
    $nameAttr    = esc($fullName);
?>
<tr id="row-<?= $enrolId ?>">
    <!--begin::Photo-->
    <td class="ps-4">
        <div class="symbol symbol-45px symbol-circle">
            <img src="<?= $photo ?>" alt="<?= $nameAttr ?>"
                 onerror="this.src='<?= base_url('app/assets/media/avatars/blank.png') ?>'"
                 class="w-45px h-45px rounded-circle object-fit-cover" />
        </div>
    </td>
    <!--end::Photo-->

    <!--begin::Name-->
    <td>
        <span class="fw-semibold text-gray-800 fs-6"><?= esc($fullName) ?></span>
    </td>
    <!--end::Name-->

    <!--begin::Note-->
    <td>
        <input type="text"
               name="attendance[<?= $enrolId ?>][note]"
               class="form-control form-control-sm form-control-solid"
               placeholder="Optional note&hellip;"
               maxlength="500" />
    </td>
    <!--end::Note-->

    <!--begin::Status-->
    <td>
        <select name="attendance[<?= $enrolId ?>][status]"
                class="form-select form-select-sm form-select-solid">
            <?php foreach ($statuses as $status): ?>
            <option value="<?= $status ?>"><?= $status ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    <!--end::Status-->

    <!--begin::File Upload-->
    <td class="text-center">
        <!--Hidden file input — DataTransfer API populates this on apply-->
        <input type="file"
               id="fi-<?= $enrolId ?>"
               name="files_<?= $enrolId ?>[]"
               multiple
               class="d-none" />

        <button type="button"
                class="btn btn-sm btn-light-primary btn-upload-files position-relative"
                data-enrol-id="<?= $enrolId ?>"
                data-student-name="<?= $nameAttr ?>">
            <i class="ki-duotone ki-paper-clip fs-4">
                <span class="path1"></span><span class="path2"></span>
            </i>
            Files
            <span id="file-badge-<?= $enrolId ?>"
                  class="badge badge-circle badge-primary position-absolute top-0 start-100 translate-middle d-none"
                  style="font-size:10px; min-width:18px; height:18px; line-height:18px; padding:0;">
                0
            </span>
        </button>
    </td>
    <!--end::File Upload-->
</tr>
<?php endforeach; ?>
