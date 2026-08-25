<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Pricing</span>
        <h1 class="mt-3 text-white">Simple, local Fiji pricing</h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;">Priced in Fijian dollars, billed monthly. Start free, upgrade as your school grows — no lock-in contracts.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row gy-4 justify-content-center">
            <?php foreach ($plans as $i => $plan): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= 100 + ($i * 50) ?>">
                    <div class="pricing-card <?= $plan['plan_name'] === 'Standard' ? 'featured' : '' ?>">
                        <?php if ($plan['plan_name'] === 'Standard'): ?><span class="plan-badge">Most Popular</span><?php endif; ?>
                        <h3><?= esc($plan['plan_name']) ?></h3>
                        <div class="price">
                            <?= $plan['plan_monthly_cost'] > 0 ? 'FJD $' . number_format($plan['plan_monthly_cost']) : 'Free' ?>
                            <?php if ($plan['plan_monthly_cost'] > 0): ?><span>/ month</span><?php endif; ?>
                        </div>
                        <p class="desc"><?= esc($plan['plan_desc']) ?></p>
                        <ul>
                            <?php if ($plan['plan_name'] === 'Starter'): ?>
                                <li><i class="bi bi-check2"></i> Core admissions &amp; enrolment</li>
                                <li><i class="bi bi-check2"></i> Attendance &amp; classroom basics</li>
                                <li><i class="bi bi-check2"></i> Wall &amp; chat communication</li>
                                <li><i class="bi bi-check2"></i> Limited user accounts</li>
                            <?php elseif ($plan['plan_name'] === 'Standard'): ?>
                                <li><i class="bi bi-check2"></i> Everything in Starter</li>
                                <li><i class="bi bi-check2"></i> Up to 500 user accounts</li>
                                <li><i class="bi bi-check2"></i> Exams, report cards &amp; timetables</li>
                                <li><i class="bi bi-check2"></i> Conduct &amp; discipline management</li>
                                <li><i class="bi bi-check2"></i> Two-factor authentication</li>
                            <?php else: ?>
                                <li><i class="bi bi-check2"></i> Everything in Standard</li>
                                <li><i class="bi bi-check2"></i> Unlimited user accounts</li>
                                <li><i class="bi bi-check2"></i> Full feature access, all modules</li>
                                <li><i class="bi bi-check2"></i> Priority support</li>
                            <?php endif; ?>
                        </ul>
                        <a href="<?= site_url('account/subscribe') ?>" class="btn-brand w-100 justify-content-center">Choose <?= esc($plan['plan_name']) ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="text-center text-muted mt-5" data-aos="fade-up">All prices in FJD, exclusive of VAT. Need something custom for your district or group of schools? <a href="<?= site_url('contact') ?>">Talk to us</a>.</p>
    </div>
</section>

<section class="section light-background">
    <div class="container section-title text-center" data-aos="fade-up">
        <span class="badge-brand-pink">FAQ</span>
        <h2 class="mt-3">Pricing questions</h2>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion" id="pricingFaq" data-aos="fade-up">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">Can I try Navuli before paying?</button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#pricingFaq">
                            <div class="accordion-body">Yes. The Starter plan is free and lets you set up your school, explore the core modules, and decide if Navuli is right for you before upgrading.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">What happens if we grow past our plan's user limit?</button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                            <div class="accordion-body">You can upgrade to the next plan at any time from your account billing page — your data and setup carry over automatically.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">Is pricing the same for Primary, Secondary and TVET schools?</button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                            <div class="accordion-body">Yes — the same plans apply across Pre School, Kindergarten, Primary, Secondary and TVET institutions. <a href="<?= site_url('contact') ?>">Contact us</a> if your school needs a custom arrangement.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">How do we pay?</button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#pricingFaq">
                            <div class="accordion-body">Billing is handled from your school's account settings after sign-up. <a href="<?= site_url('contact') ?>">Get in touch</a> if you'd like to discuss payment options for your school.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
