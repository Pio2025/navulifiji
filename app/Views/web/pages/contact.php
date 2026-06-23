<!-- Page Header Start -->
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <h1 class="display-1 text-white animated slideInDown">Contact Us</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb text-uppercase mb-0">
                    <li class="breadcrumb-item"><a class="text-white" href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item text-primary active" aria-current="page">Contact</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->
    
    <div class="tapa-banner"></div>
    
    <section id="blog">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="intro">
                        <h6>Contact Us</h6>
                        <h1>Talk To Us Now!</h1>
                        <p class="mx-auto">We believe every great partnership begins with a conversation. If you have questions about our services, need more information, or want to discuss how we can work together, our team is eagerly waiting to connect with you. Your inquiry is the first step toward achieving your goals, and we're committed to making it a smooth and informative journey.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row g-5">
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="d-flex flex-column justify-content-between h-100">
                                <div class="bg-light d-flex align-items-center w-100 p-4 mb-4">
                                    <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-dark" style="width: 55px; height: 55px;">
                                        <i class="fa fa-map-marker-alt text-primary"></i>
                                    </div>
                                    <div class="ms-4">
                                        <p class="mb-2">Address</p>
                                        <h3 class="mb-0">Lot 50, Uca Place, Makoi, Suva</h3>
                                    </div>
                                </div>
                                <div class="bg-light d-flex align-items-center w-100 p-4 mb-4">
                                    <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-dark" style="width: 55px; height: 55px;">
                                        <i class="fa fa-phone-alt text-primary"></i>
                                    </div>
                                    <div class="ms-4">
                                        <p class="mb-2">Call Us Now</p>
                                        <h3 class="mb-0">+679 9925621</h3>
                                    </div>
                                </div>
                                <div class="bg-light d-flex align-items-center w-100 p-4">
                                    <div class="d-flex flex-shrink-0 align-items-center justify-content-center bg-dark" style="width: 55px; height: 55px;">
                                        <i class="fa fa-envelope-open text-primary"></i>
                                    </div>
                                    <div class="ms-4">
                                        <p class="mb-2">Mail Us Now</p>
                                        <h3 class="mb-0">info@brightonholdings.com.fj</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.5s">
                            <p class="mb-4">Fill out the form below and we'll get in touch with you shortly.</p>
                            
                            <div id="alertContainer"></div>
                            
                            <!--form id="contactForm" method="POST" action="<?= site_url('contact/send') ?>"-->
                            <form id="contactForm" method="POST">
                                <?= csrf_field() ?>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name">
                                            <label for="name">Your Name</label>
                                            <div class="invalid-feedback" id="nameError"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Your Email">
                                            <label for="email">Your Email</label>
                                            <div class="invalid-feedback" id="emailError"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
                                            <label for="subject">Subject</label>
                                            <div class="invalid-feedback" id="subjectError"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" style="height: 100px" ></textarea>
                                            <label for="message">Message</label>
                                            <div class="invalid-feedback" id="messageError"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit" id="submitBtn">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                
                var submitBtn = $('#submitBtn');
                var originalText = submitBtn.html();
                
                // Clear previous validation errors
                clearValidationErrors();
                
                // Show loading
                submitBtn.html('<i class="fa fa-spinner fa-spin me-2"></i>Sending...').prop('disabled', true);
                
                $.ajax({
                    url: '<?= site_url('contact/send') ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            showAlert(response.message, 'success');
                            $('#contactForm')[0].reset();
                            // Clear any validation styles on success
                            clearValidationErrors();
                        } else {
                            showAlert(response.message, 'error');
                            if (response.errors) {
                                displayValidationErrors(response.errors);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        showAlert('An error occurred. Please try again.', 'error');
                        
                        // Try to parse validation errors from error response
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.errors) {
                                displayValidationErrors(response.errors);
                            }
                        } catch (e) {
                            // If not JSON, ignore
                        }
                    },
                    complete: function() {
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            });
            
            // Clear validation errors when user starts typing
            $('#contactForm input, #contactForm textarea').on('input', function() {
                var field = $(this);
                field.removeClass('is-invalid');
                field.next('.invalid-feedback').text('').hide();
            });
            
            function displayValidationErrors(errors) {
                $.each(errors, function(field, error) {
                    var fieldElement = $('[name="' + field + '"]');
                    var errorElement = $('#' + field + 'Error');
                    
                    // Add red border to the field
                    fieldElement.addClass('is-invalid');
                    
                    // Show error message
                    if (errorElement.length) {
                        errorElement.text(error).show();
                    } else {
                        // If no specific error element exists, create one
                        fieldElement.after('<div class="invalid-feedback d-block" id="' + field + 'Error">' + error + '</div>');
                    }
                });
            }
            
            function clearValidationErrors() {
                // Remove red borders
                $('.form-control').removeClass('is-invalid');
                
                // Clear error messages
                $('.invalid-feedback').text('').hide();
            }
            
            function showAlert(message, type) {
                var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                var icon = type === 'success' ? '<i class="fa fa-check-circle me-2"></i>' : '<i class="fa fa-exclamation-circle me-2"></i>';
                
                var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show mt-3">' +
                    icon + message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>';
                
                $('#alertContainer').html(alertHtml);
                
                // Auto remove success alerts after 5 seconds
                if (type === 'success') {
                    setTimeout(function() {
                        $('.alert').alert('close');
                    }, 5000);
                }
            }
        });
    </script>

    <style>
        /* Custom styles for validation */
        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        
        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        
        .form-floating > .form-control.is-invalid ~ label {
            color: #dc3545;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
    </style>