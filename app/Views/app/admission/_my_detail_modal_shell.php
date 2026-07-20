<!--begin::Admission detail modal-->
<div class="modal fade" id="admissionDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Admission Detail</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="admissionDetailBody">
                <div class="text-center py-10">
                    <span class="spinner-border spinner-border-sm align-middle me-2"></span> Loading...
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::Admission detail modal-->

<script>
function loadAdmissionDetail(admissionId, detailUrlBase) {
    const modalEl = document.getElementById('admissionDetailModal');
    const bodyEl  = document.getElementById('admissionDetailBody');
    const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);

    bodyEl.innerHTML = '<div class="text-center py-10"><span class="spinner-border spinner-border-sm align-middle me-2"></span> Loading...</div>';
    modal.show();

    fetch(detailUrlBase + admissionId)
        .then(r => r.json())
        .then(function (res) {
            if (!res.success) {
                bodyEl.innerHTML = '<div class="alert alert-danger mb-0">' + (res.message || 'Failed to load admission detail.') + '</div>';
                return;
            }
            bodyEl.innerHTML = res.html;
        })
        .catch(function () {
            bodyEl.innerHTML = '<div class="alert alert-danger mb-0">An error occurred loading the admission detail. Please try again.</div>';
        });
}
</script>
