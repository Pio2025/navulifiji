<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <span class="badge-brand-pink">For Schools</span>
        <h1 class="mt-3 text-white">For principals, boards &amp; school administrators</h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;">Whatever level your school teaches, Navuli gives your admin team one system to run it all.</p>
    </div>
</section>

<?php if (!empty($schoolCategories)): ?>
<section class="section">
    <div class="container section-title text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Every Level Of Education</span>
        <h2 class="mt-3">One platform, from Pre School to TVET</h2>
    </div>
    <div class="container">
        <div class="row gy-4 justify-content-center">
            <?php
            $icons = [
                'Pre School'      => 'bi-flower1',
                'Kindergarten'    => 'bi-stars',
                'Primary'         => 'bi-mortarboard',
                'Seconday'        => 'bi-building',
                'TVET'            => 'bi-tools',
            ];
            ?>
            <?php foreach ($schoolCategories as $i => $cat): ?>
                <div class="col-6 col-lg-2" data-aos="zoom-in" data-aos-delay="<?= 100 + ($i * 50) ?>">
                    <div class="value-card">
                        <div class="icon"><i class="bi <?= $icons[$cat['sch_cat_initial']] ?? 'bi-mortarboard' ?>"></i></div>
                        <h3 class="h6 mb-0"><?= esc($cat['sch_cat_name']) ?></h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section light-background">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="badge-brand-pink">For School Leadership</span>
                <h2 class="mt-3">Everything a principal needs to see</h2>
                <ul class="list-unstyled mt-4">
                    <li class="d-flex gap-2 mb-3"><i class="bi bi-check2-circle text-primary"></i> Oversee admissions, enrolment and classroom setup across every level your school runs</li>
                    <li class="d-flex gap-2 mb-3"><i class="bi bi-check2-circle text-primary"></i> Review and publish report cards after class-teacher sign-off — with a verifiable link for every report</li>
                    <li class="d-flex gap-2 mb-3"><i class="bi bi-check2-circle text-primary"></i> Track school-wide attendance and conduct trends, and manage student appeals fairly</li>
                    <li class="d-flex gap-2 mb-3"><i class="bi bi-check2-circle text-primary"></i> Control staff access with roles, permissions and two-factor login</li>
                    <li class="d-flex gap-2 mb-3"><i class="bi bi-check2-circle text-primary"></i> Communicate school-wide through announcements, targeted notices and the school Wall</li>
                </ul>
                <a href="<?= site_url('account/subscribe') ?>" class="btn-brand mt-2">Register Your School <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="row g-3">
                    <div class="col-6"><div class="icon-box"><div class="icon"><i class="bi bi-diagram-3"></i></div><h3>Full Oversight</h3><p>See admissions, exams and conduct at a glance.</p></div></div>
                    <div class="col-6"><div class="icon-box"><div class="icon"><i class="bi bi-award"></i></div><h3>Report Sign-off</h3><p>Review and publish report cards school-wide.</p></div></div>
                    <div class="col-6"><div class="icon-box"><div class="icon"><i class="bi bi-people"></i></div><h3>Staff Access</h3><p>Role-based permissions for every staff member.</p></div></div>
                    <div class="col-6"><div class="icon-box"><div class="icon"><i class="bi bi-megaphone"></i></div><h3>Communication</h3><p>Reach every parent and student instantly.</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container section-title text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Getting Started</span>
        <h2 class="mt-3">Onboarding your school takes minutes</h2>
    </div>
    <div class="container">
        <div class="row gy-4 text-center">
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="value-card"><div class="icon"><i class="bi bi-1-circle"></i></div><h3 class="h6">Register your school</h3></div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="150">
                <div class="value-card"><div class="icon"><i class="bi bi-2-circle"></i></div><h3 class="h6">Set up levels &amp; streams</h3></div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="value-card"><div class="icon"><i class="bi bi-3-circle"></i></div><h3 class="h6">Add staff &amp; students</h3></div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="250">
                <div class="value-card"><div class="icon"><i class="bi bi-4-circle"></i></div><h3 class="h6">Start your term</h3></div>
            </div>
        </div>
    </div>
</section>

<section class="section light-background">
    <div class="container">
        <div class="cta-band bg-gradient-brand" data-aos="zoom-in">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="text-white mb-2">Bring your school onto Navuli</h2>
                    <p class="mb-lg-0" style="color:rgba(255,255,255,.85);">Free to start. Priced in FJD. No lock-in contracts.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="<?= site_url('account/subscribe') ?>" class="btn-brand-pink">Get Started Free</a>
                </div>
            </div>
        </div>
    </div>
</section>
