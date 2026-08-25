<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Resources</span>
        <h1 class="mt-3 text-white">Guides, help &amp; answers</h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;">Everything you need to get your school running smoothly on Navuli.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <a href="<?= site_url('help/index') ?>" class="resource-card">
                    <div class="icon mb-3" style="width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:color-mix(in srgb, var(--accent-color), transparent 88%);color:var(--accent-color);font-size:26px;"><i class="bi bi-life-preserver"></i></div>
                    <h3 class="h5">Help Center</h3>
                    <p class="small mb-0">Step-by-step answers for school admins, teachers, parents and students using Navuli day to day.</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
                <a href="<?= site_url('account/subscribe') ?>" class="resource-card">
                    <div class="icon mb-3" style="width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:color-mix(in srgb, var(--accent-color-2), transparent 88%);color:var(--accent-color-2);font-size:26px;"><i class="bi bi-rocket-takeoff"></i></div>
                    <h3 class="h5">Getting Started Guide</h3>
                    <p class="small mb-0">Set up your school's levels, departments, streams and subjects in a guided wizard on day one.</p>
                </a>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <a href="<?= site_url('contact') ?>" class="resource-card">
                    <div class="icon mb-3" style="width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:color-mix(in srgb, var(--accent-color), transparent 88%);color:var(--accent-color);font-size:26px;"><i class="bi bi-headset"></i></div>
                    <h3 class="h5">Talk to Support</h3>
                    <p class="small mb-0">Can't find what you're after? Reach our team directly and we'll help you sort it out.</p>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section light-background">
    <div class="container section-title text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Frequently Asked Questions</span>
        <h2 class="mt-3">Common questions from Fiji schools</h2>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="accordion" id="resourcesFaq" data-aos="fade-up">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#rfaq1">Does Navuli work for Primary, Secondary and TVET schools?</button>
                        </h2>
                        <div id="rfaq1" class="accordion-collapse collapse show" data-bs-parent="#resourcesFaq">
                            <div class="accordion-body">Yes. Navuli supports Pre School, Kindergarten, Primary, Secondary and Technical and Vocational Education (TVET) institutions, each with their own levels, streams and subject structure.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rfaq2">Can parents use Navuli, or is it staff-only?</button>
                        </h2>
                        <div id="rfaq2" class="accordion-collapse collapse" data-bs-parent="#resourcesFaq">
                            <div class="accordion-body">Parents get their own login, linked directly to their children's records — attendance, conduct, report cards, notices and chat with the school.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rfaq3">Does it work on mobile?</button>
                        </h2>
                        <div id="rfaq3" class="accordion-collapse collapse" data-bs-parent="#resourcesFaq">
                            <div class="accordion-body">Yes — Navuli is fully responsive on any phone, tablet or desktop browser.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rfaq4">How is our school's data kept secure?</button>
                        </h2>
                        <div id="rfaq4" class="accordion-collapse collapse" data-bs-parent="#resourcesFaq">
                            <div class="accordion-body">Accounts can be protected with two-factor authentication (authenticator app or email OTP), and access to each module is controlled by role-based permissions, so staff only see what their role allows.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rfaq5">Who do we contact for support?</button>
                        </h2>
                        <div id="rfaq5" class="accordion-collapse collapse" data-bs-parent="#resourcesFaq">
                            <div class="accordion-body">Reach us any time through the <a href="<?= site_url('contact') ?>">Contact page</a> — we're a Fiji-based team.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-band bg-gradient-brand" data-aos="zoom-in">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="text-white mb-2">Still have questions?</h2>
                    <p class="mb-lg-0" style="color:rgba(255,255,255,.85);">Our team is here to help you get set up.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="<?= site_url('contact') ?>" class="btn-brand-pink">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>
