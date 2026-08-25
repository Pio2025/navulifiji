<section class="page-title dark-background bg-gradient-brand" style="padding:150px 0 70px;">
    <div class="container text-center" data-aos="fade-up">
        <span class="badge-brand-pink">Contact</span>
        <h1 class="mt-3 text-white">We'd love to hear from you</h1>
        <p style="color:rgba(255,255,255,.85); max-width:700px; margin:0 auto;">Questions about Navuli, pricing, or bringing your school onboard — reach out any time.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-5" data-aos="fade-right">
                <h2 class="mb-4">Get in touch</h2>

                <div class="contact-info-item">
                    <div class="icon"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <h3 class="h6 mb-1">Office</h3>
                        <p class="mb-0">Suva, Fiji</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="icon"><i class="bi bi-envelope"></i></div>
                    <div>
                        <h3 class="h6 mb-1">Email</h3>
                        <p class="mb-0"><a href="mailto:info@navulifiji.com">info@navulifiji.com</a></p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="icon"><i class="bi bi-telephone"></i></div>
                    <div>
                        <h3 class="h6 mb-1">Phone</h3>
                        <p class="mb-0">+679 000 0000</p>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="icon"><i class="bi bi-clock"></i></div>
                    <div>
                        <h3 class="h6 mb-1">Hours</h3>
                        <p class="mb-0">Monday – Friday, 8:00am – 5:00pm (Fiji Time)</p>
                    </div>
                </div>

                <div class="social-links d-flex mt-4">
                    <a href="https://facebook.com/navuliFiji" aria-label="Facebook" target="_blank" rel="noopener" style="width:40px;height:40px;border-radius:50%;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;margin-right:10px;color:var(--accent-color);"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com/navuliFiji" aria-label="Twitter" target="_blank" rel="noopener" style="width:40px;height:40px;border-radius:50%;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;margin-right:10px;color:var(--accent-color);"><i class="bi bi-twitter-x"></i></a>
                    <a href="https://instagram.com/navuliFiji" aria-label="Instagram" target="_blank" rel="noopener" style="width:40px;height:40px;border-radius:50%;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;color:var(--accent-color);"><i class="bi bi-instagram"></i></a>
                </div>
            </div>

            <div class="col-lg-7" data-aos="fade-left">
                <form action="<?= site_url('contact/send') ?>" method="post" class="php-email-form">
                    <?= csrf_field() ?>
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="phone" class="form-control" placeholder="Phone Number (optional)">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="school_name" class="form-control" placeholder="School Name (optional)">
                        </div>
                        <div class="col-12">
                            <input type="text" name="subject" class="form-control" placeholder="Subject">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" name="message" rows="6" placeholder="Your Message" required></textarea>
                        </div>
                        <div class="col-12 text-center">
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Thank you! Your message has been sent. We'll be in touch soon.</div>
                            <button type="submit" class="btn-brand">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
