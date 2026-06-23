"use strict";

// Helper function to force close modal
function forceCloseModal(modalId) {
    const modalElement = document.getElementById(modalId);
    
    if (modalElement) {
        // Try to get existing instance
        let modalInstance = bootstrap.Modal.getInstance(modalElement);
        
        // If no instance, create one and immediately hide it
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalElement);
        }
        
        // Hide the modal
        modalInstance.hide();
    }
    
    // Force cleanup after a delay
    setTimeout(function() {
        // Remove all backdrops
        const backdrops = document.querySelectorAll('.modal-backdrop');
        backdrops.forEach(backdrop => backdrop.remove());
        
        // Clean up body
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Remove modal-open from html
        document.documentElement.classList.remove('modal-open');
    }, 300);
}

// =============================================================================
// UPDATE EMAIL MANAGEMENT
// =============================================================================
var KTUpdateEmail = (function () {
    const modal = document.getElementById("kt_modal_update_email");
    const form = document.getElementById("kt_modal_update_email_form");
    
    if (!modal || !form) {
        return { init: function() {} };
    }
    
    let validator;
    
    return {
        init: function () {
            validator = FormValidation.formValidation(form, {
                fields: {
                    new_email: {
                        validators: {
                            notEmpty: { message: "Email address is required" },
                            emailAddress: { message: "Please enter a valid email address" }
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
            
            // Close button with confirmation
            const closeBtn = modal.querySelector('[data-kt-users-modal-action="close"]');
            if (closeBtn) {
                closeBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: "Are you sure?",
                        html: "Your changes will not be saved!",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonhtml: "Yes, discard changes",
                        cancelButtonhtml: "No, keep editing",
                        customClass: {
                            confirmButton: "btn btn-danger",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            form.reset();
                            validator.resetForm();
                            forceCloseModal('kt_modal_update_email');
                        }
                    });
                });
            }
            
            // Cancel button with confirmation
            const cancelBtn = modal.querySelector('[data-kt-users-modal-action="cancel"]');
            if (cancelBtn) {
                cancelBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: "Are you sure?",
                        html: "Your changes will not be saved!",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonhtml: "Yes, discard changes",
                        cancelButtonhtml: "No, keep editing",
                        customClass: {
                            confirmButton: "btn btn-danger",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            form.reset();
                            validator.resetForm();
                            forceCloseModal('kt_modal_update_email');
                        }
                    });
                });
            }
            
            // Submit button
            const submitButton = modal.querySelector('[data-kt-users-modal-action="submit"]');
            if (submitButton) {
                submitButton.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    if (validator) {
                        validator.validate().then(function (status) {
                            if (status == "Valid") {
                                submitButton.setAttribute("data-kt-indicator", "on");
                                submitButton.disabled = true;
                                
                                const formData = new FormData(form);
                                const baseUrl = window.location.origin;
                                
                                $.ajax({
                                    url: baseUrl + "/user/updateEmail",
                                    type: "POST",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        submitButton.removeAttribute("data-kt-indicator");
                                        submitButton.disabled = false;
                                        
                                        if (response.success) {
                                            Swal.fire({
                                                title: "Success!",
                                                html: response.message || "Email updated successfully!",
                                                icon: "success",
                                                buttonsStyling: false,
                                                confirmButtonhtml: "Great!",
                                                customClass: { 
                                                    confirmButton: "btn btn-success"
                                                }
                                            }).then(function () {
                                                forceCloseModal('kt_modal_update_email');
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                title: "Oops!",
                                                html: response.message || "Failed to update email",
                                                icon: "error",
                                                buttonsStyling: false,
                                                confirmButtonhtml: "Try Again",
                                                customClass: { 
                                                    confirmButton: "btn btn-danger"
                                                }
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        submitButton.removeAttribute("data-kt-indicator");
                                        submitButton.disabled = false;
                                        
                                        let errorMessage = "An error occurred while updating email";
                                        try {
                                            const errorResponse = JSON.parse(xhr.responseText);
                                            if (errorResponse.message) errorMessage = errorResponse.message;
                                        } catch (e) {}
                                        
                                        Swal.fire({
                                            title: "Error!",
                                            html: errorMessage,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonhtml: "Close",
                                            customClass: { 
                                                confirmButton: "btn btn-danger"
                                            }
                                        });
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Validation Error",
                                    html: "Please fill all required fields correctly.",
                                    icon: "warning",
                                    buttonsStyling: false,
                                    confirmButtonhtml: "Got it",
                                    customClass: { 
                                        confirmButton: "btn btn-warning"
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

// =============================================================================
// UPDATE PASSWORD MANAGEMENT
// =============================================================================
var KTUpdatePassword = (function () {
    const modal = document.getElementById("kt_modal_update_password");
    const form = document.getElementById("kt_modal_update_password_form");
    
    if (!modal || !form) {
        return { init: function() {} };
    }
    
    let validator;
    
    return {
        init: function () {
            validator = FormValidation.formValidation(form, {
                fields: {
                    current_password: {
                        validators: {
                            notEmpty: { message: "Current password is required" }
                        }
                    },
                    new_password: {
                        validators: {
                            notEmpty: { message: "New password is required" },
                            stringLength: {
                                min: 8,
                                message: "Password must be at least 8 characters long"
                            },
                            regexp: {
                                regexp: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]/,
                                message: "Password must contain uppercase, lowercase, number, and special character"
                            }
                        }
                    },
                    confirm_new_password: {
                        validators: {
                            notEmpty: { message: "Please confirm the new password" },
                            identical: {
                                compare: function() {
                                    return form.querySelector('[name="new_password"]').value;
                                },
                                message: "Passwords do not match"
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
            
            // Close button with confirmation
            const closeBtn = modal.querySelector('[data-kt-users-modal-action="close"]');
            if (closeBtn) {
                closeBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: "Discard Changes?",
                        html: "Password changes will not be saved!",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonhtml: "Yes, discard",
                        cancelButtonhtml: "No, keep editing",
                        customClass: {
                            confirmButton: "btn btn-danger",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            form.reset();
                            validator.resetForm();
                            forceCloseModal('kt_modal_update_password');
                        }
                    });
                });
            }
            
            // Cancel button with confirmation
            const cancelBtn = modal.querySelector('[data-kt-users-modal-action="cancel"]');
            if (cancelBtn) {
                cancelBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: "Discard Changes?",
                        html: "Password changes will not be saved!",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonhtml: "Yes, discard",
                        cancelButtonhtml: "No, keep editing",
                        customClass: {
                            confirmButton: "btn btn-danger",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            form.reset();
                            validator.resetForm();
                            forceCloseModal('kt_modal_update_password');
                        }
                    });
                });
            }
            
            // Submit button
            const submitButton = modal.querySelector('[data-kt-users-modal-action="submit"]');
            if (submitButton) {
                submitButton.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    if (validator) {
                        validator.validate().then(function (status) {
                            if (status == "Valid") {
                                submitButton.setAttribute("data-kt-indicator", "on");
                                submitButton.disabled = true;
                                
                                const formData = new FormData(form);
                                const baseUrl = window.location.origin;
                                
                                $.ajax({
                                    url: baseUrl + "/user/updatePassword",
                                    type: "POST",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        submitButton.removeAttribute("data-kt-indicator");
                                        submitButton.disabled = false;
                                        
                                        if (response.success) {
                                            Swal.fire({
                                                title: "Password Updated!",
                                                html: response.message || "Your password has been updated successfully!",
                                                icon: "success",
                                                buttonsStyling: false,
                                                confirmButtonhtml: "Excellent!",
                                                customClass: { 
                                                    confirmButton: "btn btn-success"
                                                }
                                            }).then(function () {
                                                form.reset();
                                                validator.resetForm();
                                                forceCloseModal('kt_modal_update_password');
                                            });
                                        } else {
                                            Swal.fire({
                                                title: "Update Failed",
                                                html: response.message || "Failed to update password",
                                                icon: "error",
                                                buttonsStyling: false,
                                                confirmButtonhtml: "Try Again",
                                                customClass: { 
                                                    confirmButton: "btn btn-danger"
                                                }
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        submitButton.removeAttribute("data-kt-indicator");
                                        submitButton.disabled = false;
                                        
                                        let errorMessage = "An error occurred while updating password";
                                        try {
                                            const errorResponse = JSON.parse(xhr.responseText);
                                            if (errorResponse.message) errorMessage = errorResponse.message;
                                        } catch (e) {}
                                        
                                        Swal.fire({
                                            title: "Error!",
                                            html: errorMessage,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonhtml: "Close",
                                            customClass: { 
                                                confirmButton: "btn btn-danger"
                                            }
                                        });
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Validation Error",
                                    html: "Please check your password requirements and try again.",
                                    icon: "warning",
                                    buttonsStyling: false,
                                    confirmButtonhtml: "Got it",
                                    customClass: { 
                                        confirmButton: "btn btn-warning"
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

// =============================================================================
// UPDATE ROLE MANAGEMENT
// =============================================================================
var KTUpdateRole = (function () {
    const modal = document.getElementById("kt_modal_update_role");
    const form = document.getElementById("kt_modal_update_role_form");
    
    if (!modal || !form) {
        return { init: function() {} };
    }
    
    let validator;
    
    return {
        init: function () {
            validator = FormValidation.formValidation(form, {
                fields: {
                    role_id: {  // ✅ FIXED: Changed from user_role to role_id
                        validators: {
                            notEmpty: { message: "Please select a role" }
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
            
            // Close button with confirmation
            const closeBtn = modal.querySelector('[data-kt-users-modal-action="close"]');
            if (closeBtn) {
                closeBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: "Cancel Role Change?",
                        html: "The user's role will not be updated.",
                        icon: "question",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonhtml: "Yes, cancel",
                        cancelButtonhtml: "No, go back",
                        customClass: {
                            confirmButton: "btn btn-danger",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            forceCloseModal('kt_modal_update_role');
                        }
                    });
                });
            }
            
            // Cancel button with confirmation
            const cancelBtn = modal.querySelector('[data-kt-users-modal-action="cancel"]');
            if (cancelBtn) {
                cancelBtn.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: "Cancel Role Change?",
                        html: "The user's role will not be updated.",
                        icon: "question",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonhtml: "Yes, cancel",
                        cancelButtonhtml: "No, go back",
                        customClass: {
                            confirmButton: "btn btn-danger",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            forceCloseModal('kt_modal_update_role');
                        }
                    });
                });
            }
            
            // Submit button
            const submitButton = modal.querySelector('[data-kt-users-modal-action="submit"]');
            if (submitButton) {
                submitButton.addEventListener("click", (e) => {
                    e.preventDefault();
                    
                    if (validator) {
                        validator.validate().then(function (status) {
                            if (status == "Valid") {
                                // Get selected role name for confirmation
                                const selectedRole = form.querySelector('input[name="role_id"]:checked');  // ✅ FIXED: Changed to role_id
                                const roleName = selectedRole ? selectedRole.nextElementSibling.querySelector('.fw-bold').textContent : 'this role';
                                
                                // Confirm role change
                                Swal.fire({
                                    title: "Confirm Role Change",
                                    html: `Are you sure you want to change this user's role to <strong>${roleName}</strong>?`,
                                    icon: "question",
                                    showCancelButton: true,
                                    buttonsStyling: false,
                                    confirmButtonhtml: "Yes, update role",
                                    cancelButtonhtml: "Cancel",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                        cancelButton: "btn btn-light"
                                    }
                                }).then(function (result) {
                                    if (result.value) {
                                        submitButton.setAttribute("data-kt-indicator", "on");
                                        submitButton.disabled = true;
                                        
                                        const formData = new FormData(form);
                                        const baseUrl = window.location.origin;
                                        
                                        $.ajax({
                                            url: baseUrl + "/user/updateRole",
                                            type: "POST",
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(response) {
                                                submitButton.removeAttribute("data-kt-indicator");
                                                submitButton.disabled = false;
                                                
                                                if (response.success) {
                                                    Swal.fire({
                                                        title: "Role Updated!",
                                                        html: response.message || "User role updated successfully!",
                                                        icon: "success",
                                                        buttonsStyling: false,
                                                        confirmButtonhtml: "Perfect!",
                                                        customClass: { 
                                                            confirmButton: "btn btn-success"
                                                        }
                                                    }).then(function () {
                                                        forceCloseModal('kt_modal_update_role');
                                                        location.reload();
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        title: "Update Failed",
                                                        html: response.message || "Failed to update role",
                                                        icon: "error",
                                                        buttonsStyling: false,
                                                        confirmButtonhtml: "Try Again",
                                                        customClass: { 
                                                            confirmButton: "btn btn-danger"
                                                        }
                                                    });
                                                }
                                            },
                                            error: function(xhr) {
                                                submitButton.removeAttribute("data-kt-indicator");
                                                submitButton.disabled = false;
                                                
                                                let errorMessage = "An error occurred while updating role";
                                                try {
                                                    const errorResponse = JSON.parse(xhr.responseText);
                                                    if (errorResponse.message) errorMessage = errorResponse.message;
                                                } catch (e) {}
                                                
                                                Swal.fire({
                                                    title: "Error!",
                                                    html: errorMessage,
                                                    icon: "error",
                                                    buttonsStyling: false,
                                                    confirmButtonhtml: "Close",
                                                    customClass: { 
                                                        confirmButton: "btn btn-danger"
                                                    }
                                                });
                                            }
                                        });
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "No Role Selected",
                                    html: "Please select a role for the user.",
                                    icon: "warning",
                                    buttonsStyling: false,
                                    confirmButtonhtml: "Got it",
                                    customClass: { 
                                        confirmButton: "btn btn-warning"
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

// Initialize all on DOM ready
KTUtil.onDOMContentLoaded(function () {
    KTUpdateEmail.init();
    KTUpdatePassword.init();
    KTUpdateRole.init();
});
