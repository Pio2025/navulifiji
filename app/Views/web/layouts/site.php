<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= isset($page_title) ? esc($page_title) . ' | Navuli' : 'Navuli — School Management System for Fiji' ?></title>
    <meta name="description" content="<?= isset($page_description) ? esc($page_description) : 'Navuli is an all-in-one school management system built for Fiji schools — admissions, attendance, exams, timetables, communication and more in one platform.' ?>">
    <meta name="keywords" content="school management system Fiji, Navuli, student information system, school ERP Fiji">

    <link href="<?= base_url('web/assets/img/favicon-32x32.png') ?>" rel="icon">
    <link href="<?= base_url('web/assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link href="<?= base_url('web/assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('web/assets/vendor/bootstrap-icons/bootstrap-icons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('web/assets/vendor/aos/aos.css') ?>" rel="stylesheet">
    <link href="<?= base_url('web/assets/vendor/glightbox/css/glightbox.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('web/assets/vendor/swiper/swiper-bundle.min.css') ?>" rel="stylesheet">

    <link href="<?= base_url('web/assets/css/main.css') ?>" rel="stylesheet">
    <link href="<?= base_url('web/assets/css/theme.css') ?>" rel="stylesheet">
    <?= $extra_head ?? '' ?>
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

            <a href="<?= site_url('/') ?>" class="logo d-flex align-items-center">
                <img src="<?= base_url('web/assets/img/logo-small.png') ?>" alt="Navuli">
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="<?= site_url('/') ?>" class="<?= ($active_page ?? '') === 'home' ? 'active' : '' ?>">Home</a></li>
                    <li><a href="<?= site_url('product') ?>" class="<?= ($active_page ?? '') === 'product' ? 'active' : '' ?>">Product</a></li>
                    <li><a href="<?= site_url('pricing') ?>" class="<?= ($active_page ?? '') === 'pricing' ? 'active' : '' ?>">Pricing</a></li>
                    <li><a href="<?= site_url('resource-center') ?>" class="<?= ($active_page ?? '') === 'resources' ? 'active' : '' ?>">Resources</a></li>
                    <li><a href="<?= site_url('about') ?>" class="<?= ($active_page ?? '') === 'about' ? 'active' : '' ?>">About</a></li>
                    <li><a href="<?= site_url('for-schools') ?>" class="<?= ($active_page ?? '') === 'for-schools' ? 'active' : '' ?>">School</a></li>
                    <li><a href="<?= site_url('contact') ?>" class="<?= ($active_page ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <div class="d-flex align-items-center">
                <a href="<?= site_url('auth/login') ?>" class="d-none d-lg-inline-block me-3" style="font-weight:600;color:var(--heading-color);">Sign In</a>
                <a class="btn-getstarted" href="<?= site_url('account/subscribe') ?>">Get Started Free</a>
            </div>

        </div>
    </header>

    <main class="main">
        <?= view($_view, get_defined_vars()) ?>
    </main>

    <footer id="footer" class="footer dark-background">
        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="<?= site_url('/') ?>" class="logo d-flex align-items-center">
                        <img src="<?= base_url('web/assets/img/logo-white-small.png') ?>" alt="Navuli">
                    </a>
                    <p>Navuli is an all-in-one school management platform built for Fiji — helping schools across the Central, Western, Northern and Eastern divisions manage admissions, attendance, exams, communication and more, in one place.</p>
                    <div class="social-links d-flex mt-4">
                        <a href="https://facebook.com/navuliFiji" aria-label="Facebook" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a>
                        <a href="https://twitter.com/navuliFiji" aria-label="Twitter" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://instagram.com/navuliFiji" aria-label="Instagram" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a>
                        <a href="https://linkedin.com/company/navuliFiji" aria-label="LinkedIn" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Product</h4>
                    <ul>
                        <li><a href="<?= site_url('product') ?>">Features</a></li>
                        <li><a href="<?= site_url('pricing') ?>">Pricing</a></li>
                        <li><a href="<?= site_url('for-schools') ?>">For Schools</a></li>
                        <li><a href="<?= site_url('account/subscribe') ?>">Get Started</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="<?= site_url('about') ?>">About Us</a></li>
                        <li><a href="<?= site_url('resource-center') ?>">Resources</a></li>
                        <li><a href="<?= site_url('contact') ?>">Contact</a></li>
                        <li><a href="<?= site_url('auth/login') ?>">Sign In</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12 footer-contact text-center text-md-start">
                    <h4>Get in Touch</h4>
                    <p><i class="bi bi-geo-alt"></i> Suva, Fiji</p>
                    <p><i class="bi bi-envelope"></i> <a href="mailto:info@navulifiji.com" style="color:inherit;">info@navulifiji.com</a></p>
                    <p><i class="bi bi-telephone"></i> +679 000 0000</p>
                </div>
            </div>
        </div>

        <div class="container copyright text-center">
            <p>&copy; <?= date('Y') ?> <strong class="px-1">Navuli Fiji</strong> All Rights Reserved.</p>
            <div class="credits">Developed by <a href="https://baleicoqe.com" target="_blank" rel="noopener">Pio Baleicoqe</a></div>
        </div>
    </footer>

    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <script src="<?= base_url('web/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('web/assets/vendor/php-email-form/validate.js') ?>"></script>
    <script src="<?= base_url('web/assets/vendor/aos/aos.js') ?>"></script>
    <script src="<?= base_url('web/assets/vendor/glightbox/js/glightbox.min.js') ?>"></script>
    <script src="<?= base_url('web/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') ?>"></script>
    <script src="<?= base_url('web/assets/vendor/isotope-layout/isotope.pkgd.min.js') ?>"></script>
    <script src="<?= base_url('web/assets/vendor/swiper/swiper-bundle.min.js') ?>"></script>
    <script src="<?= base_url('web/assets/vendor/purecounter/purecounter_vanilla.js') ?>"></script>
    <script src="<?= base_url('web/assets/vendor/waypoints/noframework.waypoints.js') ?>"></script>
    <script src="<?= base_url('web/assets/js/main.js') ?>"></script>
    <?= $extra_scripts ?? '' ?>
</body>

</html>
