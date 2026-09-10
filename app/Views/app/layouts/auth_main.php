<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: MetronicProduct Version: 8.2.5
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
	<!--begin::Head-->
	<head>
<base href="../../../" />
		<title>Navuli | School Management Information System</title>
		<meta charset="utf-8" />
		<meta name="description" content="This School Management System is purpose-built to align with the Fiji National Curriculum, integrating key elements like student assessment, lesson planning, and resource management into a single platform." />
		<meta name="keywords" content="navuli, fiji school, fiji education, education fiji, school management system, school management information system, fiji school managemenet information system, elearn fiji, ministry of education fiji" />
		<meta name="google-site-verification" content="Rq9FO3txj3m8uSunynz5FK5fwQfkZJo3Qv93cIGPzc-E" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Navuli - School Management Information System" />
		<meta property="og:url" content="https://navulifiji.com" />
		<meta property="og:site_name" content="Navuli" />
		<link rel="canonical" href="http://navulifiji.com" />
		<link rel="shortcut icon" href="<?php echo base_url(); ?>app/assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="<?php echo base_url(); ?>app/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>app/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->

		<style>
			.navuli-device-mock {
				position: relative;
				padding-bottom: 18px;
			}
			.navuli-laptop-screen {
				background: #ffffff;
				border-radius: 10px 10px 0 0;
				overflow: hidden;
				box-shadow: 0 20px 45px 0 rgba(0, 0, 0, 0.35);
			}
			.navuli-mock-topbar {
				display: flex;
				align-items: center;
				gap: 5px;
				padding: 8px 12px;
				background: #f4f6fa;
				border-bottom: 1px solid #e7ebf3;
			}
			.navuli-mock-dot {
				width: 7px;
				height: 7px;
				border-radius: 50%;
				display: inline-block;
			}
			.navuli-mock-body {
				padding: 18px 18px 22px;
			}
			.navuli-mock-chart {
				height: 70px;
			}
			.navuli-mock-chart span {
				display: inline-block;
				width: 12%;
				background: #009ef7;
				border-radius: 4px 4px 0 0;
				opacity: 0.85;
			}
			.navuli-laptop-hinge {
				height: 8px;
				background: linear-gradient(180deg, #dfe3ea 0%, #b6bccb 100%);
				border-radius: 0 0 3px 3px;
			}
			.navuli-laptop-base {
				height: 10px;
				width: 60%;
				margin: 0 auto;
				background: linear-gradient(180deg, #b6bccb 0%, #949bad 100%);
				border-radius: 0 0 8px 8px;
			}
			.navuli-phone {
				position: absolute;
				right: 2%;
				bottom: -8px;
				width: 22%;
				background: #1a1f2b;
				border-radius: 18px;
				padding: 6px;
				box-shadow: 0 12px 30px 0 rgba(0, 0, 0, 0.4);
			}
			.navuli-phone-screen {
				background: #ffffff;
				border-radius: 12px;
				padding: 12px 8px;
			}
		</style>

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-7EX8DDTPFX"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-7EX8DDTPFX');
        </script>

		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="app-blank">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root" id="kt_app_root">
			<!--begin::Authentication - Sign-in -->
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<!--begin::Body-->
				<div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
					<!--begin::Form-->
					<div class="d-flex flex-center flex-column flex-lg-row-fluid">
						<!--begin::Wrapper-->
						<div class="w-lg-500px p-10">
						    
						    <!-- Display general form errors -->
						    <!--?= $this->include('templates/flash_messages') ?-->
						    
							<!-- Begin: Load View -->
					
                            <?php 
                                if(isset($_view) && $_view){
                                    echo view($_view);
                                }
                            ?> 
                            <!-- End: Load View -->
                            
                            
						</div>
						<!--end::Wrapper-->
					</div>
					<!--end::Form-->
					<!--begin::Footer-->
					<div class="w-lg-500px d-flex flex-stack px-10 mx-auto">
						<!--begin::Languages-->
						<div class="me-10">
							<!--begin::Toggle-->
							<button class="btn btn-flex btn-link btn-color-gray-700 btn-active-color-primary rotate fs-base" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
								<img data-kt-element="current-lang-flag" class="w-20px h-20px rounded me-3" src="<?php echo base_url(); ?>app/assets/media/flags/united-states.svg" alt="" />
								<span data-kt-element="current-lang-name" class="me-1">English</span>
							</button>
							<!--end::Toggle-->
						</div>
						<!--end::Languages-->
						<!--begin::Links-->
						<div class="d-flex fw-semibold text-primary fs-base gap-5">
							<a href="pages/team.html" target="_blank">Terms</a>
							<a href="pages/pricing/column.html" target="_blank">Privacy</a>
							<a href="pages/contact.html" target="_blank">Contact Us</a>
						</div>
						<!--end::Links-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Body-->
				<!--begin::Aside-->
				<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url(<?php echo base_url(); ?>app/assets/media/misc/auth-bg.png)">
					<!--begin::Content-->
					<div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
						<!--begin::Logo-->
						<a href="<?php echo base_url(); ?>" class="mb-0 mb-lg-12">
							<img alt="Logo" src="<?php echo base_url(); ?>app/assets/media/logos/custom-1.png" class="h-60px h-lg-75px" />
						</a>
						<!--end::Logo-->
						<!--begin::Image-->
						<div class="navuli-device-mock d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20">
							<div class="navuli-laptop">
								<div class="navuli-laptop-screen">
									<div class="navuli-mock-topbar">
										<span class="navuli-mock-dot bg-danger"></span>
										<span class="navuli-mock-dot bg-warning"></span>
										<span class="navuli-mock-dot bg-success"></span>
									</div>
									<div class="navuli-mock-body">
										<div class="d-flex align-items-center justify-content-between mb-3">
											<div class="fw-bolder text-gray-800 fs-7">Navuli Dashboard</div>
											<div class="symbol symbol-25px">
												<div class="symbol-label bg-primary text-white fs-9 fw-bold">N</div>
											</div>
										</div>
										<div class="row g-2 mb-3">
											<div class="col-4">
												<div class="bg-light-success rounded-2 text-center py-2">
													<div class="fw-bolder fs-8 text-success">98%</div>
													<div class="fs-9 text-muted">Attendance</div>
												</div>
											</div>
											<div class="col-4">
												<div class="bg-light-info rounded-2 text-center py-2">
													<div class="fw-bolder fs-8 text-info">24</div>
													<div class="fs-9 text-muted">Classes</div>
												</div>
											</div>
											<div class="col-4">
												<div class="bg-light-warning rounded-2 text-center py-2">
													<div class="fw-bolder fs-8 text-warning">312</div>
													<div class="fs-9 text-muted">Students</div>
												</div>
											</div>
										</div>
										<div class="bg-light-primary rounded-2 p-3 d-flex align-items-end gap-2 navuli-mock-chart">
											<span style="height:35%"></span>
											<span style="height:60%"></span>
											<span style="height:45%"></span>
											<span style="height:80%"></span>
											<span style="height:55%"></span>
											<span style="height:70%"></span>
											<span style="height:40%"></span>
										</div>
									</div>
								</div>
							</div>
							<div class="navuli-laptop-hinge"></div>
							<div class="navuli-laptop-base"></div>

							<div class="navuli-phone">
								<div class="navuli-phone-screen">
									<div class="fw-bolder fs-9 text-gray-800 text-center mb-2">Navuli</div>
									<div class="bg-light-primary text-primary rounded-2 fs-9 fw-semibold text-center py-1 mb-1">Timetable</div>
									<div class="bg-light-success text-success rounded-2 fs-9 fw-semibold text-center py-1 mb-1">Attendance</div>
									<div class="bg-light-warning text-warning rounded-2 fs-9 fw-semibold text-center py-1">Assessments</div>
								</div>
							</div>
						</div>
						<!--end::Image-->
						<!--begin::Title-->
						<h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Run Your Whole School From One Place</h1>
						<!--end::Title-->
						<!--begin::Text-->
						<div class="d-none d-lg-block text-white fs-base text-center">Timetables, attendance, lesson planning, assessments
						and parent communication — all in one platform built around
						<span class="opacity-75-hover text-warning fw-bold">the Fiji National Curriculum</span>,
						<br />accessible anytime from your desktop or your phone.</div>
						<!--end::Text-->
					</div>
					<!--end::Content-->
				</div>
				<!--end::Aside-->
			</div>
			<!--end::Authentication - Sign-in-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "<?php echo base_url(); ?>app/assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="<?php echo base_url(); ?>app/assets/plugins/global/plugins.bundle.js"></script>
		<script src="<?php echo base_url(); ?>app/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="<?php echo base_url(); ?>app/assets/js/custom/authentication/sign-in/general.js?v=3"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>
