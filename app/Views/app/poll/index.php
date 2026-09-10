<?php
$polls            = $polls ?? [];
$canCreate        = $canCreate ?? false;
$canManageAll     = $canManageAll ?? false;
$myUserId         = $myUserId ?? 0;
$audienceRoleCats = $audienceRoleCats ?? [];
$isSuperAdmin     = $isSuperAdmin ?? false;
$allSchools       = $allSchools ?? [];

function poll_status_badge(bool $isClosed): string {
    return $isClosed
        ? '<span class="badge badge-light-danger">Closed</span>'
        : '<span class="badge badge-light-success">Active</span>';
}
?>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Polls</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Polls</li>
            </ul>
        </div>
        <?php if ($canCreate): ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newPollModal">
            <i class="ki-duotone ki-plus fs-2"></i>
            New Poll
        </button>
        <?php endif; ?>
    </div>
</div>
<!--end::Toolbar-->

<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

<?= $this->include('templates/flash_messages') ?>

<?php if (empty($polls)): ?>
<div class="card">
    <div class="card-body text-center py-16">
        <i class="ki-duotone ki-chart-simple fs-4x text-gray-200 mb-4">
            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
        </i>
        <div class="fs-6 fw-semibold text-gray-600">No polls yet.</div>
    </div>
</div>
<?php else: ?>
<div class="row g-6">
    <?php foreach ($polls as $p):
        $pollId    = (int) $p['poll_id'];
        $isClosed  = !empty($p['is_closed']);
        $isOwner   = (int) $p['created_by_user_id_fk'] === $myUserId;
        $canManage = $isOwner || $canManageAll;
    ?>
    <div class="col-12 col-lg-6">
        <div class="card h-100" id="poll_card_<?= $pollId ?>" data-poll-id="<?= $pollId ?>" data-closed="<?= $isClosed ? 1 : 0 ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <?= poll_status_badge($isClosed) ?>
                        <span class="text-muted fs-8 ms-2">by <?= esc(trim($p['creator_fname'] . ' ' . $p['creator_lname'])) ?> · <?= esc(substr($p['created_at'], 0, 16)) ?></span>
                    </div>
                    <?php if ($canManage): ?>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-icon btn-light" data-bs-toggle="dropdown">
                            <i class="ki-duotone ki-dots-vertical fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (!$isClosed): ?>
                            <li><a class="dropdown-item poll-close-btn" href="#" data-poll-id="<?= $pollId ?>">Close Poll</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger poll-delete-btn" href="#" data-poll-id="<?= $pollId ?>">Delete Poll</a></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>

                <h4 class="fw-bold text-gray-900 mb-1"><?= esc($p['question']) ?></h4>
                <?php if (!empty($p['description'])): ?>
                <div class="text-muted fs-7 mb-4"><?= esc($p['description']) ?></div>
                <?php endif; ?>

                <div class="poll-options-area" data-loaded="0">
                    <div class="text-muted fs-7">Loading…</div>
                </div>

                <div class="text-muted fs-8 mt-3 poll-total-votes">—</div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

</div>
</div>

<?php if ($canCreate): ?>
<!--begin::New Poll modal-->
<div class="modal fade" id="newPollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-gray-800 mb-0">New Poll</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-4">
                <form id="new_poll_form">
                    <?= csrf_field() ?>
                    <div class="row g-5">
                        <?php if ($isSuperAdmin): ?>
                        <div class="col-12">
                            <label class="form-label required fw-semibold">School</label>
                            <select name="sch_id" class="form-select">
                                <option value="">— Select school —</option>
                                <?php foreach ($allSchools as $s): ?>
                                <option value="<?= (int) $s['sch_id'] ?>"><?= esc($s['sch_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="col-12">
                            <label class="form-label required fw-semibold">Question</label>
                            <input type="text" name="question" class="form-control" maxlength="500" required />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label required fw-semibold">Options</label>
                            <div id="poll_options_list" class="d-flex flex-column gap-2">
                                <input type="text" name="options[]" class="form-control" placeholder="Option 1" maxlength="255" />
                                <input type="text" name="options[]" class="form-control" placeholder="Option 2" maxlength="255" />
                            </div>
                            <button type="button" id="btn_add_option" class="btn btn-sm btn-light mt-2">
                                <i class="ki-duotone ki-plus fs-4"></i> Add Option
                            </button>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label fw-semibold">Closes At (optional)</label>
                            <input type="datetime-local" name="closes_at" class="form-control" />
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Target Specific Groups (optional)</label>
                            <div class="d-flex flex-wrap gap-4 mt-1">
                                <?php foreach ($audienceRoleCats as $catId => $catLabel): ?>
                                <label class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" name="audience[]" value="<?= (int) $catId ?>" />
                                    <span class="form-check-label"><?= esc($catLabel) ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-muted fs-8 mt-1">Leave all unchecked to make this poll visible to everyone.</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-2">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="btn_create_poll" class="btn btn-primary">Create Poll</button>
            </div>
        </div>
    </div>
</div>
<!--end::New Poll modal-->
<?php endif; ?>

<script>
"use strict";

var POLL_RESULTS_URL = '<?= base_url('poll/results/') ?>';
var POLL_VOTE_URL    = '<?= base_url('poll/vote/') ?>';
var POLL_CLOSE_URL   = '<?= base_url('poll/close/') ?>';
var POLL_DELETE_URL  = '<?= base_url('poll/remove/') ?>';
var POLL_REFRESH_MS  = 5000;

function pollEsc(s) {
    var div = document.createElement('div');
    div.textContent = (s === null || s === undefined) ? '' : String(s);
    return div.innerHTML;
}

function pollRenderOptions(card, data) {
    var area   = card.querySelector('.poll-options-area');
    var closed = data.is_closed;
    card.setAttribute('data-closed', closed ? '1' : '0');

    var html = '';
    data.options.forEach(function (o) {
        var picked = data.user_vote === o.option_id;
        html += '<div class="d-flex align-items-center justify-content-between mb-1">';
        html += '<div class="fw-semibold fs-7' + (picked ? ' text-primary' : '') + '">' + (picked ? '<i class="ki-duotone ki-check-circle fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>' : '') + pollEsc(o.option_text) + '</div>';
        html += '<div class="text-muted fs-8">' + o.vote_count + ' (' + o.percent + '%)</div>';
        html += '</div>';
        html += '<div class="progress mb-3" style="height:8px;">';
        html += '<div class="progress-bar' + (picked ? ' bg-primary' : ' bg-secondary') + '" style="width:' + o.percent + '%"></div>';
        html += '</div>';

        if (!closed) {
            html += '<button type="button" class="btn btn-sm ' + (picked ? 'btn-primary' : 'btn-light-primary') + ' poll-vote-btn mb-3 me-2" data-option-id="' + o.option_id + '">' + (picked ? 'Voted' : 'Vote') + '</button>';
        }
    });

    area.innerHTML = html;
    area.setAttribute('data-loaded', '1');
    card.querySelector('.poll-total-votes').textContent = data.total_votes + ' vote' + (data.total_votes === 1 ? '' : 's');
}

function pollRefresh(card) {
    var pollId = card.getAttribute('data-poll-id');
    $.ajax({
        url: POLL_RESULTS_URL + pollId, type: 'GET',
        success: function (response) {
            if (response.success) {
                pollRenderOptions(card, response);
            }
        }
    });
}

function pollRefreshAll() {
    document.querySelectorAll('.poll-options-area').forEach(function (area) {
        pollRefresh(area.closest('[data-poll-id]'));
    });
}

pollRefreshAll();
setInterval(pollRefreshAll, POLL_REFRESH_MS);

document.getElementById('kt_app_content').addEventListener('click', function (e) {
    var voteBtn = e.target.closest('.poll-vote-btn');
    if (voteBtn) {
        var card   = voteBtn.closest('[data-poll-id]');
        var pollId = card.getAttribute('data-poll-id');
        voteBtn.setAttribute('disabled', 'disabled');
        $.ajax({
            url: POLL_VOTE_URL + pollId, type: 'POST',
            data: { option_id: voteBtn.getAttribute('data-option-id') },
            success: function (response) {
                if (response.success) {
                    pollRefresh(card);
                } else {
                    Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
                    voteBtn.removeAttribute('disabled');
                }
            },
            error: function () {
                Swal.fire({ title: 'Error', text: 'An unexpected error occurred.', icon: 'error' });
                voteBtn.removeAttribute('disabled');
            }
        });
        return;
    }

    var closeBtn = e.target.closest('.poll-close-btn');
    if (closeBtn) {
        e.preventDefault();
        var pollId = closeBtn.getAttribute('data-poll-id');
        Swal.fire({ title: 'Close this poll?', text: 'No further votes will be accepted.', icon: 'question', showCancelButton: true, confirmButtonText: 'Close Poll' })
            .then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: POLL_CLOSE_URL + pollId, type: 'POST',
                    success: function (response) {
                        if (response.success) {
                            window.location.reload();
                        } else {
                            Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
                        }
                    }
                });
            });
        return;
    }

    var deleteBtn = e.target.closest('.poll-delete-btn');
    if (deleteBtn) {
        e.preventDefault();
        var pollId = deleteBtn.getAttribute('data-poll-id');
        Swal.fire({ title: 'Delete this poll?', text: 'This cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', confirmButtonColor: '#f1416c' })
            .then(function (result) {
                if (!result.isConfirmed) return;
                $.ajax({
                    url: POLL_DELETE_URL + pollId, type: 'POST',
                    success: function (response) {
                        if (response.success) {
                            document.getElementById('poll_card_' + pollId).closest('.col-12').remove();
                        } else {
                            Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
                        }
                    }
                });
            });
        return;
    }
});

<?php if ($canCreate): ?>
document.getElementById('btn_add_option').addEventListener('click', function () {
    var list  = document.getElementById('poll_options_list');
    var count = list.querySelectorAll('input').length + 1;
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.className = 'form-control';
    input.maxLength = 255;
    input.placeholder = 'Option ' + count;
    list.appendChild(input);
});

document.getElementById('btn_create_poll').addEventListener('click', function () {
    var btn = this;
    var form = document.getElementById('new_poll_form');
    var question = form.querySelector('[name="question"]').value.trim();
    var options = Array.prototype.slice.call(form.querySelectorAll('[name="options[]"]'))
        .map(function (i) { return i.value.trim(); })
        .filter(function (v) { return v !== ''; });

    if (!question || options.length < 2) {
        Swal.fire({ title: 'Missing information', text: 'Please provide a question and at least two options.', icon: 'warning' });
        return;
    }

    var formData = new FormData(form);

    btn.setAttribute('data-kt-indicator', 'on');
    btn.disabled = true;

    $.ajax({
        url: '<?= base_url('poll/store') ?>', type: 'POST', data: formData, processData: false, contentType: false,
        success: function (response) {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            if (response.success) {
                Swal.fire({ title: 'Created!', text: response.message, icon: 'success', timer: 1800, showConfirmButton: false })
                    .then(function () { window.location.href = response.redirect; });
            } else {
                Swal.fire({ title: 'Error', text: response.message, icon: 'error' });
            }
        },
        error: function () {
            btn.removeAttribute('data-kt-indicator');
            btn.disabled = false;
            Swal.fire({ title: 'Error', text: 'An unexpected error occurred.', icon: 'error' });
        }
    });
});
<?php endif; ?>
</script>
