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
                            <a href="<?= site_url('account/subscribe') ?>?plan=<?= (int) $plan['plan_id'] ?>&package=web" data-plan-id="<?= (int) $plan['plan_id'] ?>" class="btn-brand-outline-pink w-100 justify-content-center mb-3 choose-plan-link">Choose <?= esc($plan['plan_name']) ?></a>
                        <?php endif; ?>
                        <ul>
                            <?php if ($plan['plan_name'] === 'Standard'): ?>
                                <li><i class="bi bi-check2"></i> 21 Standard Modules</li>
                                <li class="feature-disabled"><i class="bi bi-x-circle"></i> 15 Premium Modules</li>
                                <li class="feature-disabled"><i class="bi bi-x-circle"></i> 18 Ultimate Modules</li>
                                <li><i class="bi bi-check2"></i> Unlimited Users</li>
                                <li><i class="bi bi-check2"></i> Online Training</li>
                                <li><i class="bi bi-check2"></i> Automatic Updates</li>
                                <li><i class="bi bi-check2"></i> Email &amp; Phone Support</li>
                                <li><i class="bi bi-check2"></i> Onboarding &amp; Configuration</li>
                            <?php elseif ($plan['plan_name'] === 'Premium'): ?>
                                <li><i class="bi bi-check2"></i> 21 Standard Modules</li>
                                <li><i class="bi bi-check2"></i> 15 Premium Modules</li>
                                <li class="feature-disabled"><i class="bi bi-x-circle"></i> 18 Ultimate Modules</li>
                                <li><i class="bi bi-check2"></i> Unlimited Users</li>
                                <li><i class="bi bi-check2"></i> Online Training</li>
                                <li><i class="bi bi-check2"></i> Automatic Updates</li>
                                <li><i class="bi bi-check2"></i> Email &amp; Phone Support</li>
                                <li><i class="bi bi-check2"></i> Onboarding &amp; Configuration</li>
                            <?php elseif ($plan['plan_name'] === 'Ultimate'): ?>
                                <li><i class="bi bi-check2"></i> 21 Standard Modules</li>
                                <li><i class="bi bi-check2"></i> 15 Premium Modules</li>
                                <li><i class="bi bi-check2"></i> 18 Ultimate Modules</li>
                                <li><i class="bi bi-check2"></i> Unlimited Users</li>
                                <li><i class="bi bi-check2"></i> Online Training</li>
                                <li><i class="bi bi-check2"></i> Automatic Updates</li>
                                <li><i class="bi bi-check2"></i> Email &amp; Phone Support</li>
                                <li><i class="bi bi-check2"></i> Onboarding &amp; Configuration</li>
                            <?php else: ?>
                                <li><i class="bi bi-check2"></i> 21 Standard Modules</li>
                                <li><i class="bi bi-check2"></i> 15 Premium Modules</li>
                                <li><i class="bi bi-check2"></i> 18 Ultimate Modules</li>
                                <li><i class="bi bi-check2"></i> Unlimited Users</li>
                                <li><i class="bi bi-check2"></i> Online Training</li>
                                <li><i class="bi bi-check2"></i> Automatic Updates</li>
                                <li><i class="bi bi-check2"></i> Email &amp; Phone Support</li>
                                <li><i class="bi bi-check2"></i> Onboarding &amp; Configuration</li>
                                <li><i class="bi bi-check2"></i> Product Source Code</li>
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
        <span class="badge-brand-pink">Why It's Worth It</span>
        <h2 class="mt-3">Built to pay for itself</h2>
        <p>Navuli isn't an added expense — it's the paperwork, printing and phone calls your staff no longer have to do. Here's how the numbers work out.</p>
    </div>

    <div class="container">
        <div class="row gy-4 justify-content-center text-center mb-5" data-aos="fade-up">
            <div class="col-lg-4 col-md-4">
                <div class="stats-item">
                    <span><span class="purecounter" data-purecounter-start="0" data-purecounter-end="7" data-purecounter-duration="1">0</span>&lt;</span>
                    <p>FJD per day to run your whole school's admin, records and communication on the Standard plan</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="stats-item">
                    <span>$<span class="purecounter" data-purecounter-start="0" data-purecounter-end="0" data-purecounter-duration="1">0</span></span>
                    <p>extra per teacher, student or parent you add — every plan includes unlimited users</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="stats-item">
                    <span>$<span class="purecounter" data-purecounter-start="0" data-purecounter-end="0" data-purecounter-duration="1">0</span></span>
                    <p>setup fee — onboarding and configuration are included with every plan</p>
                </div>
            </div>
        </div>

        <div class="row gy-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="icon-box">
                    <div class="icon"><i class="bi bi-clock-history"></i></div>
                    <h3>Hours Back Every Week</h3>
                    <p>Attendance registers, report cards, admission files and timetables that used to take hours of manual work now take minutes — for every teacher and admin staff member.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="150">
                <div class="icon-box">
                    <div class="icon"><i class="bi bi-printer"></i></div>
                    <h3>Less Paper, Less Cost</h3>
                    <p>Cut spending on printed registers, ledger books, paper report cards, notice SMS credit and photocopying — most of it moves onto Navuli at no extra charge.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="icon-box">
                    <div class="icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <h3>Fewer Mistakes, Faster Turnaround</h3>
                    <p>Automatic grade calculation, timetable conflict detection and digital records mean fewer errors and no re-doing lost or miscalculated paperwork.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="250">
                <div class="icon-box">
                    <div class="icon"><i class="bi bi-heart"></i></div>
                    <h3>Parents Who Stay Enrolled</h3>
                    <p>Real-time visibility into attendance, results and school news builds the kind of trust that keeps parents choosing — and recommending — your school.</p>
                </div>
            </div>
        </div>

        <p class="text-center text-muted mt-5 mb-0" data-aos="fade-up">Compare that to the cost of one missed enrolment, one reprinted batch of report cards, or a term of SMS credit for notices — for most schools, Navuli pays for itself well within the first month.</p>
    </div>
</section>

<section class="section">
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

<section class="section">
    <div class="container">
        <div class="cta-band bg-gradient-brand text-center" data-aos="zoom-in">
            <h2 class="text-white mb-2">Can't decide which plan is right for you?</h2>
            <p class="mb-4" style="color:rgba(255,255,255,.85); max-width:600px; margin-left:auto; margin-right:auto;">Talk to our team — we'll help you match the right plan to your school's size and needs.</p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="<?= site_url('contact') ?>" class="btn-brand-pink">Book a Demo</a>
                <a href="<?= site_url('contact') ?>" class="btn-outline-white">Connect with Expert</a>
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
    var chooseLinks = document.querySelectorAll('.choose-plan-link');
    var subscribeBaseUrl = '<?= site_url('account/subscribe') ?>';

    function applyPackage(pkg) {
        labels.forEach(function (label) {
            label.classList.toggle('active', label.dataset.package === pkg);
        });
        amounts.forEach(function (el) {
            el.textContent = pkg === 'web_mobile' ? el.dataset.priceBundle : el.dataset.priceWeb;
        });
        chooseLinks.forEach(function (el) {
            el.href = subscribeBaseUrl + '?plan=' + el.dataset.planId + '&package=' + pkg;
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
