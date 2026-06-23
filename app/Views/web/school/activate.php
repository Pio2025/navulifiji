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
<base href="../../" />
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
		
		<!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-7EX8DDTPFX"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-7EX8DDTPFX');
        </script>
        
        <!-- Custom styles to fix background issue -->
        <style>
            /* Ensure full height layout */
            html, body, #kt_body, #kt_app_root {
                min-height: 100vh;
            }
            
            /* Background image setup */
            body { 
                background-image: url('<?php echo base_url(); ?>app/assets/media/auth/bg5.jpg'); 
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
            } 
            
            [data-bs-theme="dark"] body { 
                background-image: url('<?php echo base_url(); ?>app/assets/media/auth/bg5-dark.jpg'); 
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
            }
            
            /* Main container adjustments */
            .d-flex.flex-column.flex-center.flex-column-fluid {
                min-height: 100vh;
                width: 100%;
                padding: 20px 0;
            }
            
            /* Center content properly */
            .d-flex.flex-column.flex-center.text-center.p-10 {
                width: 100%;
            }
            
            /* Card adjustments for responsive height */
            .card.card-flush.w-lg-650px.py-5 {
                margin: auto;
                max-width: 650px;
            }
            
            /* Ensure content doesn't overflow on small screens */
            @media (max-height: 700px) {
                .d-flex.flex-column.flex-center.text-center.p-10 {
                    padding-top: 10px !important;
                    padding-bottom: 10px !important;
                }
                
                .card.card-flush.w-lg-650px.py-5 {
                    padding-top: 1rem !important;
                    padding-bottom: 1rem !important;
                }
                
                .mb-14 {
                    margin-bottom: 1rem !important;
                }
                
                .mb-0 img {
                    max-height: 200px;
                }
            }
            
            @media (max-width: 767px) {
                .card.card-flush.w-lg-650px.py-5 {
                    width: 95% !important;
                    max-width: 95% !important;
                }
            }
        </style>
        
		<!--end::Global Stylesheets Bundle-->
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
			<!--begin::Page bg image-->
			<!-- Background images now handled by CSS above -->
			<!--end::Page bg image-->
			<!--begin::Authentication - Signup Welcome Message -->
			<div class="d-flex flex-column flex-center flex-column-fluid">
				<!--begin::Content-->
				<div class="d-flex flex-column flex-center text-center p-10">
					<!--begin::Wrapper-->
					<div class="card card-flush w-lg-650px py-5">
						<div class="card-body py-15 py-lg-20">
							<!--begin::Logo-->
							<div class="mb-14">
								<a href="index.html" class="">
									<img alt="Logo" src="<?php echo base_url(); ?>navuli_logo.png" class="h-60px" />
								</a>
							</div>
							<!--end::Logo-->
							<!--begin::Title-->
							<h1 class="fw-bolder text-gray-900 mb-5">Verifying your email</h1>
							<!--end::Title-->
							<!--begin::Action-->
							<div class="fs-6 mb-8">
								<?php if(!empty($success)){ ?>
								    <!--begin::Alert-->
                                        <div class="alert alert-success d-flex align-items-center p-5">
                                            <!--begin::Icon-->
                                            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                                            <!--end::Icon-->
                                        
                                            <!--begin::Wrapper-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Title-->
                                                <!--h4 class="mb-1 text-success">Congratulation!</h4>
                                                <!--end::Title-->
                                        
                                                <!--begin::Content-->
                                                <span><?php echo $success ?></span>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Alert-->    
								<?php }?>
								
								<?php if(!empty($error)){ ?>
								    <!--begin::Alert-->
                                        <div class="alert alert-danger d-flex align-items-center p-5">
                                            <!--begin::Icon-->
                                            <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                                                <i class="ki-duotone ki-shield-cross fs-2hx text-danger">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                </i>
                                            </span>
                                            <!--end::Icon-->
                                        
                                            <!--begin::Wrapper-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Title-->
                                                <!--h4 class="mb-1 text-danger">Opps!</h4>
                                                <!--end::Title-->
                                        
                                                <!--begin::Content-->
                                                <span><?php echo $error ?></span>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Wrapper-->
                                        </div>
                                        <!--end::Alert-->    
								<?php }?>
							</div>
							<!--end::Action-->
							<!--begin::Link-->
							<div class="mb-11">
								<a href="<?php echo base_url(); ?>auth" class="btn btn-sm btn-primary">Login now</a>
							</div>
							<!--end::Link-->
							<!--begin::Illustration-->
							<div class="mb-0">
								<img src="<?php echo base_url(); ?>app/assets/media/auth/please-verify-your-email.png" class="mw-100 mh-300px theme-light-show" alt="" />
								<img src="<?php echo base_url(); ?>app/assets/media/auth/please-verify-your-email-dark.png" class="mw-100 mh-300px theme-dark-show" alt="" />
							</div>
							<!--end::Illustration-->
						</div>
					</div>
					<!--end::Wrapper-->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Authentication - Signup Welcome Message-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "<?php echo base_url(); ?>app/assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="<?php echo base_url(); ?>app/assets/plugins/global/plugins.bundle.js"></script>
		<script src="<?php echo base_url(); ?>app/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>