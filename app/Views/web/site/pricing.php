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
        <span class="badge-brand-pink">What's Included</span>
        <h2 class="mt-3">Modules by plan</h2>
        <p>Every plan builds on the one before it — see exactly what unlocks at each tier.</p>
    </div>
    <div class="container">
        <?php
            $moduleTiers = [
                'Standard Modules' => [
                    ['bi-speedometer2', 'Multiple Dashboards'],
                    ['bi-clipboard', 'Notice Board'],
                    ['bi-megaphone', 'Announcement Management'],
                    ['bi-calendar-week', 'Timetable'],
                    ['bi-person-plus', 'Student Admission'],
                    ['bi-diagram-3', 'Student Enrolment'],
                    ['bi-chat-dots', 'Messaging System'],
                    ['bi-calendar2-check', 'Student Attendance'],
                    ['bi-journal-bookmark', 'Curriculum Management'],
                    ['bi-pencil-square', 'Examination'],
                    ['bi-people', 'User Management'],
                    ['bi-bar-chart', 'Report Center'],
                    ['bi-person-badge', 'Admin / Teacher Login'],
                    ['bi-person-check', 'Students / Parents Login'],
                    ['bi-person-lines-fill', 'Student Information'],
                    ['bi-file-earmark-text', 'Student Reference'],
                    ['bi-file-earmark-check', 'Parents Reference'],
                    ['bi-award', 'Certificate Generator'],
                    ['bi-person-vcard', 'ID Card Generator'],
                    ['bi-calendar-event', 'School / Events Calendar'],
                    ['bi-journal-text', 'Gradebook'],
                ],
                'Premium Modules' => [
                    ['bi-bookshelf', 'Library'],
                    ['bi-database', 'Data Management'],
                    ['bi-envelope-at', 'Email Integration'],
                    ['bi-bus-front', 'Transportation'],
                    ['bi-box-arrow-in-down', 'Custom Import'],
                    ['bi-grid-3x3-gap', 'School Wall'],
                    ['bi-bar-chart-line', 'Poll'],
                    ['bi-file-earmark-bar-graph', 'Custom Report'],
                    ['bi-palette', 'Theme'],
                    ['bi-briefcase', 'Placement'],
                    ['bi-list-task', 'Task'],
                    ['bi-shield-check', 'Discipline'],
                    ['bi-box-arrow-up', 'Data Export'],
                    ['bi-bell', 'Reminder'],
                    ['bi-clipboard-plus', 'Enquiry & Registration'],
                ],
                'Ultimate Modules' => [
                    ['bi-people-fill', 'Unlimited User Accounts'],
                    ['bi-unlock', 'Full Module Access'],
                    ['bi-headset', 'Priority Support'],
                ],
            ];
        ?>
        <?php foreach ($moduleTiers as $tierName => $modules): ?>
            <div class="mb-5" data-aos="fade-up">
                <h3 class="mb-4"><?= esc($tierName) ?> <span class="text-muted fs-6">(<?= count($modules) ?>)</span></h3>
                <div class="row gy-4">
                    <?php foreach ($modules as [$icon, $label]): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="module-grid-item">
                                <div class="module-icon"><i class="bi <?= $icon ?>"></i></div>
                                <span class="module-label"><?= esc($label) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
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
