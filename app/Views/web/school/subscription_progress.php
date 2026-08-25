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
                    <div class="text-center">
                        <a href="<?= site_url('/') ?>" class="btn-brand">Return to Homepage</a>
                    </div>
                <?php elseif (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                    <div class="text-center">
                        <a href="<?= site_url('account/subscribe') ?>" class="btn-brand">Try Again</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info d-flex align-items-start gap-3 mb-4">
                        <i class="bi bi-info-circle-fill fs-3"></i>
                        <div>No submission found. Please complete the registration form.</div>
                    </div>
                    <div class="text-center">
                        <a href="<?= site_url('account/subscribe') ?>" class="btn-brand">Go to Registration Form</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
