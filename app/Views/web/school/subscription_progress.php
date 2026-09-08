<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Get Started</span>
        <h1 class="mt-3 text-white">School Account Registration</h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;">Here's the status of your submission.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-check-circle-fill fs-3"></i>
                        <div><?= session()->getFlashdata('success') ?></div>
                    </div>
                    <div class="text-center mb-5">
                        <a href="<?= site_url('/') ?>" class="btn-brand">Return to Homepage</a>
                    </div>
                <?php elseif (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                    <div class="text-center mb-5">
                        <a href="<?= site_url('account/subscribe') ?>" class="btn-brand">Try Again</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-info-circle-fill fs-3"></i>
                        <div>No submission found. Please complete the registration form.</div>
                    </div>
                    <div class="text-center mb-5">
                        <a href="<?= site_url('account/subscribe') ?>" class="btn-brand">Go to Registration Form</a>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
                <div class="mt-4">
                    <h4 class="text-center mb-2">What Happens Next?</h4>
                    <p class="text-center text-muted mb-4">Whatever your subscription status, here's the process our team follows to get your school live on Navuli.</p>
                    <div class="row gy-4 text-center">
                        <div class="col-md-3">
                            <div class="value-card">
                                <div class="icon"><i class="bi bi-1-circle"></i></div>
                                <h3 class="h6 mb-1">Verify &amp; Process Subscription</h3>
                                <p class="small text-muted mb-0">The Navuli admin team reviews your school and subscription details for accuracy.</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="value-card">
                                <div class="icon"><i class="bi bi-2-circle"></i></div>
                                <h3 class="h6 mb-1">Process Payment</h3>
                                <p class="small text-muted mb-0">For paid plans, your payment is confirmed against the payment method you selected.</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="value-card">
                                <div class="icon"><i class="bi bi-3-circle"></i></div>
                                <h3 class="h6 mb-1">Activate Subscription</h3>
                                <p class="small text-muted mb-0">Your subscription is marked active and your school account is unlocked.</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="value-card">
                                <div class="icon"><i class="bi bi-4-circle"></i></div>
                                <h3 class="h6 mb-1">Onboarding &amp; Configuration</h3>
                                <p class="small text-muted mb-0">We help you set up levels, streams and staff so your school can start using Navuli.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
