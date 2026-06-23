"use strict";
var KTModalNewTarget = (function () {
    var modal, form, submitBtn, cancelBtn, validator, modalInstance;

    function refreshCSRF(data) {
        if (data.csrfName && data.csrfHash) {
            document.querySelector('meta[name="csrf-token-name"]').setAttribute("content", data.csrfName);
            document.querySelector('meta[name="csrf-token"]').setAttribute("content", data.csrfHash);
        }
    }

    return {
        init: function () {
            modal = document.querySelector("#kt_modal_new_target");
            if (!modal) return;

            modalInstance = new bootstrap.Modal(modal);
            form = document.querySelector("#kt_modal_new_target_form");
            submitBtn = document.querySelector("#kt_modal_new_target_submit");
            cancelBtn = document.querySelector("#kt_modal_new_target_cancel");

            validator = FormValidation.formValidation(form, {
                fields: {
                    sch_email: {
                        validators: {
                            notEmpty: { message: "Email is required" },
                            emailAddress: { message: "Enter a valid email" }
                        }
                    },
                    sch_phone: {
                        validators: {
                            notEmpty: { message: "Phone number is required" },
                            stringLength: {
                                min: 7, max: 7,
                                message: "Phone number must be exactly 7 digits"
                            },
                            digits: { message: "Only digits allowed" }
                        }
                    },
                    sch_address: {
                        validators: {
                            notEmpty: { message: "Address is required" },
                            stringLength: { min: 6, message: "Minimum 6 characters" }
                        }
                    },
                    sch_motto: {
                        validators: {
                            notEmpty: { message: "School motto is required" },
                            stringLength: { min: 6, message: "Minimum 6 characters" }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                    })
                }
            });

            submitBtn.addEventListener("click", function (e) {
                e.preventDefault();

                validator.validate().then(function (status) {
                    if (status === "Valid") {
                        submitBtn.setAttribute("data-kt-indicator", "on");
                        submitBtn.disabled = true;

                        let csrfName = document.querySelector('meta[name="csrf-token-name"]').content;
                        let csrfHash = document.querySelector('meta[name="csrf-token"]').content;

                        var formData = new FormData(form);
                        formData.append(csrfName, csrfHash);

                        fetch(form.action, {
                            method: "POST",
                            body: formData,
                            headers: { "X-Requested-With": "XMLHttpRequest" }
                        })
                            .then(res => res.json())
                            .then(data => {
                                submitBtn.removeAttribute("data-kt-indicator");
                                submitBtn.disabled = false;

                                refreshCSRF(data);

                                if (data.success) {
                                    Swal.fire({
                                        text: data.message,
                                        icon: "success",
                                        confirmButtonText: "OK",
                                        buttonsStyling: false,
                                        customClass: { confirmButton: "btn btn-primary" }
                                    }).then(() => {
                                        modalInstance.hide();
                                        if (data.redirect) {
                                            window.location.href = data.redirect;
                                        } else {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        text: data.message || "Update failed",
                                        icon: "error",
                                        confirmButtonText: "OK",
                                        buttonsStyling: false,
                                        customClass: { confirmButton: "btn btn-primary" }
                                    });
                                }
                            })
                            .catch(error => {
                                submitBtn.removeAttribute("data-kt-indicator");
                                submitBtn.disabled = false;
                                console.error(error);

                                Swal.fire({
                                    text: "An error occurred. Try again.",
                                    icon: "error",
                                    confirmButtonText: "OK",
                                    buttonsStyling: false,
                                    customClass: { confirmButton: "btn btn-primary" }
                                });
                            });
                    } else {
                        // Handle invalid form - show validation errors
                        Swal.fire({
                            text: "Please fix the validation errors in the form",
                            icon: "warning",
                            confirmButtonText: "OK",
                            buttonsStyling: false,
                            customClass: { confirmButton: "btn btn-primary" }
                        });
                    }
                });
            });

            cancelBtn.addEventListener("click", function (e) {
                e.preventDefault();

                Swal.fire({
                    text: "Cancel editing?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light"
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        form.reset();
                        modalInstance.hide();
                    }
                });
            });

            modal.addEventListener("hidden.bs.modal", function () {
                validator.resetForm(true);
            });
        }
    };
})();

KTUtil.onDOMContentLoaded(function () {
    KTModalNewTarget.init();
});