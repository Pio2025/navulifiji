"use strict";

// Next of Kin Management
var KTNextOfKinManagement = (function () {
    // Check if modal exists before initializing
    const modal = document.getElementById("kt_modal_add_next_of_kin");
    const form = document.getElementById("kt_modal_add_next_of_kin_form");
    
    // If modal doesn't exist, return empty init
    if (!modal || !form) {
        console.log("Next of Kin modal not found on this page");
        return {
            init: function() {
                // Do nothing if modal doesn't exist
            }
        };
    }
    
    let validator;
    
    return {
        init: function () {
            // Double check elements exist
            if (!modal || !form) {
                return;
            }
            
            // Initialize Form Validation
            validator = FormValidation.formValidation(form, {
                fields: {
                    next_of_kin_name: {
                        validators: {
                            notEmpty: {
                                message: "Full name is required"
                            },
                            stringLength: {
                                min: 2,
                                max: 100,
                                message: "Name must be between 2 and 100 characters"
                            }
                        }
                    },
                    next_of_kin_relationship: {
                        validators: {
                            notEmpty: {
                                message: "Relationship is required"
                            }
                        }
                    },
                    next_of_kin_phone: {
                        validators: {
                            notEmpty: {
                                message: "Phone number is required"
                            },
                            stringLength: {
                                min: 7,
                                max: 7,
                                message: "Phone must be exactly 7 digits"
                            },
                            regexp: {
                                regexp: /^[0-9]{7}$/,
                                message: "Phone must contain only numbers"
                            }
                        }
                    },
                    next_of_kin_email: {
                        validators: {
                            emailAddress: {
                                message: "Please enter a valid email address"
                            }
                        }
                    },
                    next_of_kin_address: {
                        validators:{
                            notEmpty: {
                                message: "Next of kin address is required"
                            },
                            stringLength: {
                                min: 3,
                                max: 100,
                                message: "Next of kin address must be between 3 and 100 characters"
                            }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: ""
                    })
                }
            });
            
            // Close button handler
            const closeBtn = modal.querySelector('[data-kt-users-modal-action="close"]');
            if (closeBtn) {
                closeBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    Swal.fire({
                        text: "Are you sure you would like to cancel?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, cancel it!",
                        cancelButtonText: "No, return",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            form.reset();
                            validator.resetForm();
                            
                            // Close modal properly
                            const modalElement = document.getElementById('kt_modal_add_next_of_kin');
                            const modalInstance = bootstrap.Modal.getInstance(modalElement);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                        }
                    });
                });
            }
            
            // Cancel button handler
            const cancelBtn = modal.querySelector('[data-kt-users-modal-action="cancel"]');
            if (cancelBtn) {
                cancelBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    Swal.fire({
                        text: "Are you sure you would like to cancel?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "Yes, cancel it!",
                        cancelButtonText: "No, return",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            form.reset();
                            validator.resetForm();
                            
                            // Close modal properly
                            const modalElement = document.getElementById('kt_modal_add_next_of_kin');
                            const modalInstance = bootstrap.Modal.getInstance(modalElement);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                        }
                    });
                });
            }
            
            // Submit button handler
            const submitButton = modal.querySelector('[data-kt-users-modal-action="submit"]');
            if (submitButton) {
                submitButton.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    if (validator) {
                        validator.validate().then(function (status) {
                            console.log("Validation status:", status);
                            
                            if (status == "Valid") {
                                // Show loading indicator
                                submitButton.setAttribute("data-kt-indicator", "on");
                                submitButton.disabled = true;
                                
                                // Get form data
                                const formData = new FormData(form);
                                
                                // Get base URL from a known element or use window.location
                                const baseUrl = window.location.origin;
                                
                                // Determine if add or edit
                                const nextOfKinId = document.getElementById("next_of_kin_id").value;
                                const url = nextOfKinId ? 
                                    baseUrl + "/nextofkin/update" : 
                                    baseUrl + "/nextofkin/add";
                                
                                // AJAX request
                                $.ajax({
                                    url: url,
                                    type: "POST",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        // Remove loading indicator
                                        submitButton.removeAttribute("data-kt-indicator");
                                        submitButton.disabled = false;
                                        
                                        if (response.success) {
                                            Swal.fire({
                                                text: response.message,
                                                icon: "success",
                                                buttonsStyling: false,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: {
                                                    confirmButton: "btn btn-primary"
                                                }
                                            }).then(function () {
                                                // Reset form
                                                form.reset();
                                                validator.resetForm();
                                                
                                                // Close modal properly
                                                const modalElement = document.getElementById('kt_modal_add_next_of_kin');
                                                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                                                if (modalInstance) {
                                                    modalInstance.hide();
                                                }
                                                
                                                // Reload table
                                                if (nextOfKinId) {
                                                    // Update existing row
                                                    updateTableRow(response.data);
                                                } else {
                                                    // Add new row
                                                    addTableRow(response.data);
                                                }
                                            });
                                        } else {
                                            Swal.fire({
                                                text: response.message || "An error occurred",
                                                icon: "error",
                                                buttonsStyling: false,
                                                confirmButtonText: "Ok, got it!",
                                                customClass: {
                                                    confirmButton: "btn btn-primary"
                                                }
                                            });
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        // Remove loading indicator
                                        submitButton.removeAttribute("data-kt-indicator");
                                        submitButton.disabled = false;
                                        
                                        console.error("AJAX Error:", xhr.responseText);
                                        
                                        Swal.fire({
                                            text: "An error occurred while saving. Please try again.",
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton: "btn btn-primary"
                                            }
                                        });
                                    }
                                });
                            } else {
                                Swal.fire({
                                    text: "Sorry, looks like there are some errors detected, please try again.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        });
                    }
                });
            }
        }
    };
})();

// Open Add Modal
function openAddModal() {
    // Check if user already has 3 next of kin
    const tbody = document.getElementById("next_of_kin_tbody");
    if (!tbody) return;
    
    const currentCount = tbody.querySelectorAll('tr:not(#no_data_row)').length;
    
    if (currentCount >= 3) {
        Swal.fire({
            title: "Limit Reached",
            text: "You can only add up to 3 next of kin contacts per user.",
            icon: "warning",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
        });
        return;
    }
    
    // Reset form
    const form = document.getElementById("kt_modal_add_next_of_kin_form");
    if (form) {
        form.reset();
        document.getElementById("next_of_kin_id").value = "";
        document.getElementById("next_of_kin_modal_title").textContent = "Add Next of Kin";
        
        // Uncheck checkboxes
        document.getElementById("is_primary_contact").checked = false;
        document.getElementById("is_emergency_contact").checked = false;
        document.getElementById("authorized_pickup").checked = false;
    }
}

// Edit Next of Kin
function editNextOfKin(kinId) {
    // Show loading
    Swal.fire({
        title: "Loading...",
        text: "Please wait",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Get base URL
    const baseUrl = window.location.origin;
    
    // Get data via AJAX
    $.ajax({
        url: baseUrl + "/nextofkin/get/" + kinId,
        type: "GET",
        success: function(response) {
            Swal.close();
            
            if (response.success) {
                const data = response.data;
                
                // Populate form
                document.getElementById("next_of_kin_id").value = data.next_of_kin_id;
                document.getElementById("next_of_kin_name").value = data.next_of_kin_name;
                document.getElementById("next_of_kin_relationship").value = data.next_of_kin_relationship;
                document.getElementById("next_of_kin_phone").value = data.next_of_kin_phone;
                document.getElementById("next_of_kin_email").value = data.next_of_kin_email || "";
                document.getElementById("next_of_kin_address").value = data.next_of_kin_address || "";
                
                // Set checkboxes
                document.getElementById("is_primary_contact").checked = data.is_primary_contact == 1;
                document.getElementById("is_emergency_contact").checked = data.is_emergency_contact == 1;
                document.getElementById("authorized_pickup").checked = data.authorized_pickup == 1;
                
                // Change modal title
                document.getElementById("next_of_kin_modal_title").textContent = "Edit Next of Kin";
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById("kt_modal_add_next_of_kin"));
                modal.show();
            } else {
                Swal.fire({
                    text: "Failed to load data",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
        },
        error: function() {
            Swal.close();
            Swal.fire({
                text: "An error occurred",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }
    });
}

// Delete Next of Kin
function deleteNextOfKin(kinId) {
    Swal.fire({
        text: "Are you sure you want to delete this contact?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-active-light"
        }
    }).then(function (result) {
        if (result.value) {
            // Show loading
            Swal.fire({
                title: "Deleting...",
                text: "Please wait",
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Get base URL
            const baseUrl = window.location.origin;
            
            // AJAX delete
            $.ajax({
                url: baseUrl + "/nextofkin/delete/" + kinId,
                type: "POST",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function () {
                            // Remove row from table
                            removeTableRow(kinId);
                        });
                    } else {
                        Swal.fire({
                            text: response.message || "Failed to delete",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        text: "An error occurred",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });
        }
    });
}

// Add row to table
function addTableRow(data) {
    const tbody = document.getElementById("next_of_kin_tbody");
    if (!tbody) return;
    
    // Remove "no data" row if exists
    const noDataRow = document.getElementById("no_data_row");
    if (noDataRow) {
        noDataRow.remove();
    }
    
    // Build badges
    let badges = "";
    if (data.is_primary_contact == 1) {
        badges += '<span class="badge badge-primary badge-sm">Primary</span> ';
    }
    if (data.is_emergency_contact == 1) {
        badges += '<span class="badge badge-danger badge-sm">Emergency</span> ';
    }
    if (data.authorized_pickup == 1) {
        badges += '<span class="badge badge-success badge-sm">Pickup</span>';
    }
    
    // Create new row
    const newRow = `
        <tr id="kin_row_${data.next_of_kin_id}">
            <td class="fw-bold text-gray-800">${escapeHtml(data.next_of_kin_name)}</td>
            <td>${escapeHtml(data.next_of_kin_relationship)}</td>
            <td>${escapeHtml(data.next_of_kin_phone)}</td>
            <td>${escapeHtml(data.next_of_kin_email || '')}</td>
            <td>${badges}</td>
            <td class="text-end">
                <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2" onclick="editNextOfKin(${data.next_of_kin_id})" title="Edit">
                    <i class="ki-duotone ki-pencil fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </button>
                <button type="button" class="btn btn-icon btn-sm btn-light-danger" onclick="deleteNextOfKin(${data.next_of_kin_id})" title="Delete">
                    <i class="ki-duotone ki-trash fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                    </i>
                </button>
            </td>
        </tr>
    `;
    
    tbody.insertAdjacentHTML('beforeend', newRow);
}

// Update row in table
function updateTableRow(data) {
    const row = document.getElementById("kin_row_" + data.next_of_kin_id);
    if (!row) return;
    
    // Build badges
    let badges = "";
    if (data.is_primary_contact == 1) {
        badges += '<span class="badge badge-primary badge-sm">Primary</span> ';
    }
    if (data.is_emergency_contact == 1) {
        badges += '<span class="badge badge-danger badge-sm">Emergency</span> ';
    }
    if (data.authorized_pickup == 1) {
        badges += '<span class="badge badge-success badge-sm">Pickup</span>';
    }
    
    // Update row content
    row.innerHTML = `
        <td class="fw-bold text-gray-800">${escapeHtml(data.next_of_kin_name)}</td>
        <td>${escapeHtml(data.next_of_kin_relationship)}</td>
        <td>${escapeHtml(data.next_of_kin_phone)}</td>
        <td>${escapeHtml(data.next_of_kin_email || '')}</td>
        <td>${badges}</td>
        <td class="text-end">
            <button type="button" class="btn btn-icon btn-sm btn-light-primary me-2" onclick="editNextOfKin(${data.next_of_kin_id})" title="Edit">
                <i class="ki-duotone ki-pencil fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-light-danger" onclick="deleteNextOfKin(${data.next_of_kin_id})" title="Delete">
                <i class="ki-duotone ki-trash fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                </i>
            </button>
        </td>
    `;
}

// Remove row from table
function removeTableRow(kinId) {
    const row = document.getElementById("kin_row_" + kinId);
    if (!row) return;
    
    row.remove();
    
    // Check if table is now empty
    const tbody = document.getElementById("next_of_kin_tbody");
    if (tbody && tbody.children.length === 0) {
        tbody.innerHTML = `
            <tr id="no_data_row">
                <td colspan="6" class="text-center text-muted py-10">
                    <i class="ki-duotone ki-information-5 fs-3x text-primary mb-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                    </i>
                    <div class="fw-bold">No next of kin contacts added yet</div>
                    <div class="text-gray-600 fs-7">Click "Add Contact" button to add emergency contacts</div>
                </td>
            </tr>
        `;
    }
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

// Initialize on DOM ready
KTUtil.onDOMContentLoaded(function () {
    KTNextOfKinManagement.init();
});
