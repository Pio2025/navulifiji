<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Feature</span>
        <h1 class="mt-3 text-white">Every module, in one platform</h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;">From admissions to alumni — explore everything Navuli does for your school, organised by plan. Click any module to see it in detail.</p>
    </div>
</section>

<?php $tierIndex = 0; ?>
<?php foreach ($tiers as $tierName => $modules): ?>
    <section class="section <?= $tierIndex % 2 === 1 ? 'light-background' : '' ?>">
        <?php if ($tierIndex === 0): ?>
            <div class="container section-title text-center" data-aos="fade-up">
                <span class="badge-brand-pink">What's Included</span>
                <h2 class="mt-3">Modules by plan</h2>
                <p>Every plan builds on the one before it — see exactly what unlocks at each tier.</p>
            </div>
        <?php endif; ?>
        <div class="container">
            <div class="mb-5" data-aos="fade-up">
                <h3 class="mb-4"><?= esc($tierName) ?> <span class="text-muted fs-6">(<?= count($modules) ?>)</span></h3>
                <div class="row gy-4">
                    <?php foreach ($modules as $slug => $module): ?>
                        <div class="col-lg-4 col-md-6">
                            <a href="<?= site_url('feature/' . $slug) ?>" class="module-grid-item">
                                <div class="module-icon"><i class="bi <?= esc($module['icon']) ?>"></i></div>
                                <span class="module-label"><?= esc($module['label']) ?></span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php $tierIndex++; ?>
<?php endforeach; ?>

<section class="section <?= $tierIndex % 2 === 1 ? 'light-background' : '' ?>">
    <div class="container">
        <div class="cta-band bg-gradient-brand text-center" data-aos="zoom-in">
            <h2 class="text-white mb-2">Ready to see it in your school?</h2>
            <p class="mb-4" style="color:rgba(255,255,255,.85); max-width:600px; margin-left:auto; margin-right:auto;">Book a live demo, or compare plans to find the right fit for your school's size and needs.</p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="<?= site_url('contact') ?>" class="btn-brand-pink">Book a Demo</a>
                <a href="<?= site_url('pricing') ?>" class="btn-outline-white">View Pricing</a>
            </div>
        </div>
    </div>
</section>
