<!DOCTYPE html>
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
		<link rel="shortcut icon" href="https://navulifiji.com/app/https://navulifiji.com/app/assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--end::Fonts-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="https://navulifiji.com/app/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="https://navulifiji.com/app/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
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
			<!--begin::Wrapper-->
			<div class="d-flex flex-column flex-column-fluid">
				
				<!--begin::Body-->
				<div class="scroll-y flex-column-fluid px-10 py-10" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header_nav" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true" style="background-color:#D5D9E2; --kt-scrollbar-color: #d9d0cc; --kt-scrollbar-hover-color: #d9d0cc">
					<!--begin::Email template-->
					<style>html,body { padding:0; margin:0; font-family: Inter, Helvetica, "sans-serif"; } a:hover { color: #009ef7; }</style>
					<div id="#kt_app_body_content" style="background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:0; width:100%;">
						<div style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
							<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto" style="border-collapse:collapse">
								<tbody>
									<tr>
										<td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
											<!--begin:Email content-->
											<div style="text-align:center; margin:0 15px 34px 15px">
												<!--begin:Logo-->
												<div style="margin-bottom: 10px">
													<a href="https://navulifiji.com" rel="noopener" target="_blank">
														<img alt="Logo" src="https://navulifiji.com/app/assets/media/logos/navuli_logo.png" style="height: 50px" />
													</a>
												</div>
												<!--end:Logo-->
												<!--begin:Media-->
												<div style="margin-bottom: 15px">
													<img alt="Logo" src="https://navulifiji.com/app/assets/media/email/icon-positive-vote-1.svg" />
												</div>
												<!--end:Media-->
												<!--begin:Text-->
												<div style="font-size: 14px; font-weight: 500; margin-bottom: 27px; font-family:Arial,Helvetica,sans-serif;">
													<p style="margin-bottom:9px; color:#181C32; font-size: 22px; font-weight:700">Bula Vinaka <?= $userName ?>, thanks for signing up!</p>
													<p style="margin-bottom:2px; color:#7E8299">To activate your school account, please log in now</p>
													<p style="margin-bottom:2px; color:#7E8299">using your registered email address and password</p>
													<p style="margin-bottom:2px; color:#7E8299">to begin the setup process.</p>
												</div>
												<!--end:Text-->
												<!--begin:Action-->
												<a href='authentication/general/welcome.html' target="_blank" style="background-color:#50cd89; border-radius:6px;display:inline-block; padding:11px 19px; color: #FFFFFF; font-size: 14px; font-weight:500;">Activate Account</a>
												<!--begin:Action-->
											</div>
											<!--end:Email content-->
										</td>
									</tr>
									<tr style="display: flex; justify-content: center; margin:0 60px 35px 60px">
										<td align="start" valign="start" style="padding-bottom: 10px;">
											<p style="color:#181C32; font-size: 18px; font-weight: 600; margin-bottom:13px">What’s next?</p>
											<!--begin::Wrapper-->
											<div style="background: #F9F9F9; border-radius: 12px; padding:35px 30px">
												<!--begin::Item-->
												<div style="display:flex">
													<!--begin::Media-->
													<div style="display: flex; justify-content: center; align-items: center; width:40px; height:40px; margin-right:13px">
														<img alt="Logo" src="https://navulifiji.com/app/assets/media/email/icon-polygon.svg" />
														<span style="position: absolute; color:#50CD89; font-size: 16px; font-weight: 600;">1</span>
													</div>
													<!--end::Media-->
													<!--begin::Block-->
													<div>
														<!--begin::Content-->
														<div>
															<!--begin::Title-->
															<a href="#" style="color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif">Complete your account</a>
															<!--end::Title-->
															<!--begin::Desc-->
															<p style="color:#5E6278; font-size: 13px; font-weight: 500; padding-top:3px; margin:0;font-family:Arial,Helvetica,sans-serif">To ensure your school can fully utilize the Navuli Fiji platform, please complete your account setup. Full access is granted only after the initial configuration of your school's departments, educational levels, streams, and subjects is finalized. This one-time process establishes the foundational structure required for all core features to function correctly.</p>
															<!--end::Desc-->
														</div>
														<!--end::Content-->
														<!--begin::Separator-->
														<div class="separator separator-dashed" style="margin:17px 0 15px 0"></div>
														<!--end::Separator-->
													</div>
													<!--end::Block-->
												</div>
												<!--end::Item-->
												<!--begin::Item-->
												<div style="display:flex">
													<!--begin::Media-->
													<div style="display: flex; justify-content: center; align-items: center; width:40px; height:40px; margin-right:13px">
														<img alt="Logo" src="https://navulifiji.com/app/assets/media/email/icon-polygon.svg" />
														<span style="position: absolute; color:#50CD89; font-size: 16px; font-weight: 600;">2</span>
													</div>
													<!--end::Media-->
													<!--begin::Block-->
													<div>
														<!--begin::Content-->
														<div>
															<!--begin::Title-->
															<a href="#" style="color:#181C32; font-size: 14px; font-weight: 600;font-family:Arial,Helvetica,sans-serif">Add User</a>
															<!--end::Title-->
															<!--begin::Desc-->
															<p style="color:#5E6278; font-size: 13px; font-weight: 500; padding-top:3px; margin:0;font-family:Arial,Helvetica,sans-serif">Once your academic structure is fully configured, you can immediately begin adding staff and students to the platform. This will allow you to utilize all of Navuli Fiji's user management and operational features without delay.</p>
															<!--end::Desc-->
														</div>
														<!--end::Content-->
														<!--begin::Separator-->
														<div class="separator separator-dashed" style="margin:17px 0 15px 0"></div>
														<!--end::Separator-->
													</div>
													<!--end::Block-->
												</div>
												<!--end::Item-->
												
											</div>
											<!--end::Wrapper-->
										</td>
									</tr>
									<tr>
										<td align="center" valign="center" style="font-size: 13px; text-align:center; padding: 0 10px 10px 10px; font-weight: 500; color: #A1A5B7; font-family:Arial,Helvetica,sans-serif">
											<p style="color:#181C32; font-size: 16px; font-weight: 600; margin-bottom:9px">Our priority is delivering exceptional value and solutions!</p>
											<p style="margin-bottom:2px">Call our customer care number: +679 9896700</p>
											<p style="margin-bottom:4px">You may reach us at 
											<a href="mailto:info@navulifiji.com" rel="noopener" target="_blank" style="font-weight: 600">info@navulifiji.com</a>.</p>
											<p>We serve Mon-Fri, 8AM-4PM</p>
										</td>
									</tr>
									<tr>
										<td align="center" valign="center" style="text-align:center; padding-bottom: 20px;">
											<a href="#" style="margin-right:10px">
												<img alt="Logo" src="https://navulifiji.com/app/assets/media/email/icon-linkedin.svg" />
											</a>
											<a href="#" style="margin-right:10px">
												<img alt="Logo" src="https://navulifiji.com/app/assets/media/email/icon-dribbble.svg" />
											</a>
											<a href="#" style="margin-right:10px">
												<img alt="Logo" src="https://navulifiji.com/app/assets/media/email/icon-facebook.svg" />
											</a>
											<a href="#">
												<img alt="Logo" src="https://navulifiji.com/app/assets/media/email/icon-twitter.svg" />
											</a>
										</td>
									</tr>
									<tr>
										<td align="center" valign="center" style="font-size: 13px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif">
											<p>&copy; <?= date('Y') ?> Navuli Fiji. 
											<a href="https://navulifiji.com" rel="noopener" target="_blank" style="font-weight: 600;font-family:Arial,Helvetica,sans-serif">Unsubscribe</a>&nbsp; from newsletter.</p>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!--end::Email template-->
				</div>
				<!--end::Body-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Root-->
		<!--begin::Javascript-->
		<script>var hostUrl = "https://navulifiji.com/app/assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="https://navulifiji.com/app/assets/plugins/global/plugins.bundle.js"></script>
		<script src="https://navulifiji.com/app/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>