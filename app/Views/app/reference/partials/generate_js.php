<script>
"use strict";

function handleGenerateReference(formId, generateUrl, btnId) {
    const btn  = document.getElementById(btnId);
    const form = document.getElementById(formId);

    if (!btn || !form) return;

    btn.addEventListener('click', function() {
        // ── Client-side validation ────────────────────────────────────
        const errors = [];

        // Check all required fields
        form.querySelectorAll('[data-required]').forEach(function(el) {
            const label = el.getAttribute('data-label') || 'A required field';
            if (el.type === 'checkbox' || el.type === 'radio') return;
            if (!el.value.trim()) errors.push(label + ' is required.');
        });

        // Check required checkbox groups
        form.querySelectorAll('[data-required-group]').forEach(function(group) {
            const groupName  = group.getAttribute('data-required-group');
            const groupLabel = group.getAttribute('data-group-label') || groupName;
            const checked    = form.querySelectorAll('input[name="' + groupName + '"]:checked');
            if (checked.length === 0) {
                errors.push('Please select at least one option for: ' + groupLabel);
            }
        });

        if (errors.length > 0) {
            Swal.fire({
                title: 'Validation Error',
                html: '<ul class="text-start ps-4 mb-0">' +
                      errors.map(e => '<li class="mb-1">' + e + '</li>').join('') +
                      '</ul>',
                icon: 'warning',
                buttonsStyling: false,
                confirmButtonText: 'Fix Errors',
                customClass: { confirmButton: 'btn btn-warning' }
            });

            // Highlight invalid fields
            form.querySelectorAll('[data-required]').forEach(function(el) {
                if (el.type !== 'checkbox' && el.type !== 'radio' && !el.value.trim()) {
                    el.classList.add('is-invalid');
                    el.addEventListener('input', function() {
                        el.classList.remove('is-invalid');
                    }, { once: true });
                }
            });

            return;
        }
        // ────────────────────────────────────────────────────────────────

        const formData = new FormData(form);
        btn.setAttribute('data-kt-indicator', 'on');
        btn.disabled = true;

        $.ajax({
            url: generateUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                btn.removeAttribute('data-kt-indicator');
                btn.disabled = false;

                if (response.has_existing) {
                    Swal.fire({
                        title: 'Document Already Exists',
                        html: response.message +
                              '<br><br><span class="badge badge-light-warning">Generated: ' +
                              response.existing_date + '</span>',
                        icon: 'warning',
                        showCancelButton: true,
                        showDenyButton: true,
                        buttonsStyling: false,
                        confirmButtonText: '<i class="ki-duotone ki-plus fs-4 me-1"><span class="path1"></span><span class="path2"></span></i> Generate New',
                        denyButtonText: '<i class="ki-duotone ki-eye fs-4 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> View Existing',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            confirmButton: 'btn btn-primary me-2',
                            denyButton:    'btn btn-light-warning me-2',
                            cancelButton:  'btn btn-light',
                        }
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            document.getElementById('force_new').value = '1';
                            btn.click();
                        } else if (result.isDenied) {
                            window.open(response.existing_url, '_blank');
                        }
                    });

                } else if (response.success) {
                    Swal.fire({
                        title: 'Document Generated!',
                        text: response.message,
                        icon: 'success',
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: '<i class="ki-duotone ki-eye fs-4 me-1"></i> View PDF',
                        cancelButtonText: 'Close',
                        customClass: {
                            confirmButton: 'btn btn-success me-2',
                            cancelButton:  'btn btn-light',
                        }
                    }).then(function(result) {
                        if (result.isConfirmed) window.open(response.file_url, '_blank');
                        document.getElementById('force_new').value = '0';
                    });

                } else {
                    Swal.fire({
                        title: 'Failed',
                        text: response.message,
                        icon: 'error',
                        buttonsStyling: false,
                        confirmButtonText: 'Close',
                        customClass: { confirmButton: 'btn btn-danger' }
                    });
                    document.getElementById('force_new').value = '0';
                }
            },
            error: function() {
                btn.removeAttribute('data-kt-indicator');
                btn.disabled = false;
                Swal.fire({
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.',
                    icon: 'error',
                    buttonsStyling: false,
                    confirmButtonText: 'Close',
                    customClass: { confirmButton: 'btn btn-danger' }
                });
            }
        });
    });
}
</script>