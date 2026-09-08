<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Feature</span>
        <h1 class="mt-3 text-white">Every module, in one platform</h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;">From admissions to alumni — explore everything Navuli does for your school, organised by plan. Click any module to see it in detail.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2>The complete school management platform for Fiji</h2>
            <p class="text-muted" style="max-width:700px; margin:0 auto;">Navuli has every module your school will ever need to run day-to-day academic and administrative work efficiently.</p>
        </div>
        <div class="row align-items-center gy-5">
            <div class="col-lg-6" data-aos="fade-right">
                <p>Navuli is a school management platform built for schools across Fiji's Central, Western, Northern and Eastern divisions. It gives every person in your school community — administrators, teachers, non-teaching staff, students and parents — their own login and dashboard, tailored to what they need to see and do.</p>
                <p>The modules below cover student admission and enrolment, timetables, attendance, examinations and gradebooks, right through to fees, transport, the school library and staff records. Navuli also keeps everyone talking — through the School Wall, messaging, notice boards and announcements — so parents and staff stay in the loop without extra phone calls or paperwork.</p>
                <p class="mb-0">Scroll down to see every module available across the Standard, Premium and Ultimate plans.</p>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="feature-mockup-wrap">
                    <div class="feature-mockup">
                        <div class="feature-mockup-bar">
                            <span class="feature-mockup-dot"></span>
                            <span class="feature-mockup-dot"></span>
                            <span class="feature-mockup-dot"></span>
                        </div>
                        <div class="feature-mockup-body">
                            <div class="feature-mockup-sidebar">
                                <span></span><span></span><span></span><span></span><span></span>
                            </div>
                            <div class="feature-mockup-main">
                                <div class="bar accent"></div>
                                <div class="bar" style="width:90%"></div>
                                <div class="bar" style="width:75%"></div>
                                <div class="feature-mockup-row-cards">
                                    <div></div><div></div><div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="feature-float-icon" style="top:-16px; left:8%; color:var(--accent-color);"><i class="bi bi-calendar-week"></i></div>
                    <div class="feature-float-icon" style="top:22%; right:-16px; color:var(--accent-color-2);"><i class="bi bi-people"></i></div>
                    <div class="feature-float-icon" style="bottom:14%; left:-16px; color:var(--accent-color-2);"><i class="bi bi-bus-front"></i></div>
                    <div class="feature-float-icon" style="bottom:-16px; right:12%; color:var(--accent-color);"><i class="bi bi-mortarboard"></i></div>
                </div>
            </div>
        </div>
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
