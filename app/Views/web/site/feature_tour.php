<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <a href="<?= site_url('pricing') ?>" style="color:rgba(255,255,255,.85); text-decoration:none; font-size:14px;"><i class="bi bi-arrow-left"></i> Back to Pricing</a>
        <div class="mt-3">
            <span class="badge-brand-pink"><?= esc($tour['tier']) ?></span>
        </div>
        <div class="d-flex align-items-center justify-content-center mt-3 mb-2">
            <div style="width:64px; height:64px; border-radius:14px; background:rgba(255,255,255,.12); border:1.5px solid rgba(255,255,255,.35); display:flex; align-items:center; justify-content:center; font-size:30px; color:#fff;">
                <i class="bi <?= esc($tour['icon']) ?>"></i>
            </div>
        </div>
        <h1 class="text-white"><?= esc($tour['label']) ?></h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;"><?= esc($tour['description']) ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="container section-title text-center" data-aos="fade-up">
                    <span class="badge-brand-pink">What You Get</span>
                    <h2 class="mt-3">Inside <?= esc($tour['label']) ?></h2>
                </div>
                <div class="row gy-4">
                    <?php foreach ($tour['highlights'] as $highlight): ?>
                        <div class="col-md-4">
                            <div class="icon-box text-center h-100">
                                <div class="icon mx-auto"><i class="bi bi-check2-circle"></i></div>
                                <p class="mb-0"><?= esc($highlight) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<section class="section light-background">
    <div class="container">
        <div class="container section-title text-center" data-aos="fade-up">
            <span class="badge-brand-pink"><?= esc($tour['tier']) ?></span>
            <h2 class="mt-3">More in this plan</h2>
        </div>
        <div class="row gy-4">
            <?php foreach ($related as $otherSlug => $otherTour): ?>
                <div class="col-lg-4 col-md-6">
                    <a href="<?= site_url('feature-tour/' . $otherSlug) ?>" class="module-grid-item">
                        <div class="module-icon"><i class="bi <?= esc($otherTour['icon']) ?>"></i></div>
                        <span class="module-label"><?= esc($otherTour['label']) ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="cta-band bg-gradient-brand text-center" data-aos="zoom-in">
            <h2 class="text-white mb-2">See <?= esc($tour['label']) ?> in your school</h2>
            <p class="mb-4" style="color:rgba(255,255,255,.85); max-width:600px; margin-left:auto; margin-right:auto;">Book a demo and we'll walk you through this feature, live, on your own data.</p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="<?= site_url('contact') ?>" class="btn-brand-pink">Book a Demo</a>
                <a href="<?= site_url('pricing') ?>" class="btn-outline-white">View Pricing</a>
            </div>
        </div>
    </div>
</section>
