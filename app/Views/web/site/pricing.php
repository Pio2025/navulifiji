<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Pricing</span>
        <h1 class="mt-3 text-white">Simple, local Fiji pricing</h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;">Priced in Fijian dollars, billed monthly. Pick the plan that fits your school, upgrade any time — no lock-in contracts.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="package-toggle-wrap">
                <span class="package-toggle-label active" data-package="web">Web App</span>
                <label class="package-switch">
                    <input type="checkbox" id="packageSwitch">
                    <span class="package-switch-slider"></span>
                </label>
                <span class="package-toggle-label" data-package="web_mobile">Both Web &amp; Mobile App</span>
            </div>
        </div>

        <div class="row gy-4 justify-content-center">
            <?php foreach ($plans as $i => $plan): ?>
                <?php
                    $isCustomQuote = $plan['plan_monthly_cost'] === null;
                    $priceWeb = $plan['plan_monthly_cost'] > 0 ? 'FJD $' . number_format($plan['plan_monthly_cost']) : 'Free';
                    $bundleCost = $plan['plan_monthly_cost_web_n_mobile'] ?? null;
                    $priceBundle = $bundleCost > 0 ? 'FJD $' . number_format($bundleCost) : 'Free';
                ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= 100 + ($i * 50) ?>">
                    <div class="pricing-card <?= $plan['plan_name'] === 'Ultimate' ? 'featured' : '' ?>">
                        <?php if ($plan['plan_name'] === 'Ultimate'): ?><span class="plan-badge">Most Popular</span><?php endif; ?>
                        <h3><?= esc($plan['plan_name']) ?></h3>
                        <?php if ($isCustomQuote): ?>
                            <a href="<?= site_url('contact') ?>" class="btn-brand w-100 justify-content-center mt-3 mb-2">Contact Sales</a>
                            <a href="<?= site_url('contact') ?>" class="btn-brand-outline-pink w-100 justify-content-center mb-3">Talk to Sales</a>
                        <?php else: ?>
                            <div class="price">
                                <span class="price-amount" data-price-web="<?= esc($priceWeb) ?>" data-price-bundle="<?= esc($priceBundle) ?>"><?= $priceWeb ?></span>
                                <?php if ($plan['plan_monthly_cost'] > 0): ?><span>/ month</span><?php endif; ?>
                            </div>
                            <?php if ($plan['plan_monthly_cost'] > 0): ?><div class="price-note">VAT inclusive</div><?php endif; ?>
                            <a href="<?= site_url('account/subscribe') ?>?plan=<?= (int) $plan['plan_id'] ?>" class="btn-brand-outline-pink w-100 justify-content-center mb-3">Choose <?= esc($plan['plan_name']) ?></a>
                        <?php endif; ?>
                        <p class="desc"><?= esc($plan['plan_desc']) ?></p>
                        <ul>
                            <?php if ($plan['plan_name'] === 'Standard'): ?>
                                <li><i class="bi bi-check2"></i> Core admissions &amp; enrolment</li>
                                <li><i class="bi bi-check2"></i> Attendance &amp; classroom basics</li>
                                <li><i class="bi bi-check2"></i> Wall &amp; chat communication</li>
                                <li><i class="bi bi-check2"></i> Limited user accounts</li>
                            <?php elseif ($plan['plan_name'] === 'Premium'): ?>
                                <li><i class="bi bi-check2"></i> Everything in Standard</li>
                                <li><i class="bi bi-check2"></i> Up to 500 user accounts</li>
                                <li><i class="bi bi-check2"></i> Exams, report cards &amp; timetables</li>
                                <li><i class="bi bi-check2"></i> Conduct &amp; discipline management</li>
                                <li><i class="bi bi-check2"></i> Two-factor authentication</li>
                            <?php elseif ($plan['plan_name'] === 'Ultimate'): ?>
                                <li><i class="bi bi-check2"></i> Everything in Premium</li>
                                <li><i class="bi bi-check2"></i> Unlimited user accounts</li>
                                <li><i class="bi bi-check2"></i> Full feature access, all modules</li>
                                <li><i class="bi bi-check2"></i> Priority support</li>
                            <?php else: ?>
                                <li><i class="bi bi-check2"></i> Everything in Ultimate</li>
                                <li><i class="bi bi-check2"></i> Multi-school / district licensing</li>
                                <li><i class="bi bi-check2"></i> Dedicated onboarding &amp; account manager</li>
                                <li><i class="bi bi-check2"></i> Custom integrations, built to order</li>
                            <?php endif; ?>
                        </ul>
                        <a href="<?= site_url('contact') ?>" class="btn-brand w-100 justify-content-center mt-auto">Contact Us</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="text-center text-muted mt-5" data-aos="fade-up">All prices in FJD, inclusive of VAT. Managing a district or group of schools? Our Enterprise plan above is tailored and quoted individually — <a href="<?= site_url('contact') ?>">talk to us</a>.</p>
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
                            <div class="accordion-body">We don't currently offer a free trial, but our team is happy to walk you through a live demo before you commit — <a href="<?= site_url('contact') ?>">get in touch</a> to arrange one.</div>
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

<script>
(function () {
    var toggle = document.getElementById('packageSwitch');
    if (!toggle) return;

    var labels = document.querySelectorAll('.package-toggle-label');
    var amounts = document.querySelectorAll('.price-amount');

    function applyPackage(pkg) {
        labels.forEach(function (label) {
            label.classList.toggle('active', label.dataset.package === pkg);
        });
        amounts.forEach(function (el) {
            el.textContent = pkg === 'web_mobile' ? el.dataset.priceBundle : el.dataset.priceWeb;
        });
    }

    toggle.addEventListener('change', function () {
        applyPackage(this.checked ? 'web_mobile' : 'web');
    });

    labels.forEach(function (label) {
        label.addEventListener('click', function () {
            var pkg = this.dataset.package;
            toggle.checked = pkg === 'web_mobile';
            applyPackage(pkg);
        });
    });
})();
</script>
