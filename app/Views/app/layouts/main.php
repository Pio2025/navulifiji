<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
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
		
		<!--meta name="csrf-token-name" content="<?= csrf_token() ?>">
        <meta name="csrf-token" content="<?= csrf_hash() ?>"-->
		
		<link rel="canonical" href="http://navulifiji.com" />
		<link rel="shortcut icon" href="<?php echo base_url(); ?>app/assets/media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="<?php echo base_url(); ?>app/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>app/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="<?php echo base_url(); ?>app/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url(); ?>app/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		
		<!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
		
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-7EX8DDTPFX"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-7EX8DDTPFX');
        </script>

		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
		
		<style>
		    /* Custom margin classes */
            .ml-20 { margin-left: 20px !important; }
            .mr-20 { margin-right: 20px !important; }
            .mx-20 { margin-left: 20px !important; margin-right: 20px !important; }
            .pl-20 { padding-left: 20px !important; }
            .pr-20 { padding-right: 20px !important; }
            .px-20 { padding-left: 20px !important; padding-right: 20px !important; }

            /* ── Chat photo grid ── */
            .chat-photo-grid {
                display: grid;
                gap: 3px;
                border-radius: 10px;
                overflow: hidden;
                max-width: 260px;
                cursor: pointer;
                user-select: none;
            }
            .chat-photo-grid.grid-1 { grid-template-columns: 1fr; max-width: 240px; }
            .chat-photo-grid.grid-2 { grid-template-columns: 1fr 1fr; }
            .chat-photo-grid.grid-3 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
            .chat-photo-grid.grid-3 .chat-photo-cell:first-child { grid-row: span 2; }
            .chat-photo-grid.grid-4 { grid-template-columns: 1fr 1fr; }
            .chat-photo-cell {
                position: relative;
                aspect-ratio: 1 / 1;
                overflow: hidden;
                background: #1a1a1a;
            }
            .chat-photo-grid.grid-1 .chat-photo-cell { aspect-ratio: 4 / 3; }
            .chat-photo-cell img {
                width: 100%; height: 100%;
                object-fit: cover;
                display: block;
                transition: transform .18s ease;
            }
            .chat-photo-cell:hover img { transform: scale(1.04); }
            .chat-photo-more {
                position: absolute; inset: 0;
                background: rgba(0,0,0,.55);
                color: #fff;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.5rem; font-weight: 700;
                pointer-events: none;
            }

            /* ── Typing indicator bubble ── */
            .chat-typing-indicator { padding: 0 0 8px 0; }
            .chat-typing-bubble {
                display: inline-flex; align-items: center; gap: 5px;
                padding: 10px 14px;
                background: #f1f1f4;
                border-radius: 18px 18px 18px 4px;
            }
            .typing-dot {
                width: 8px; height: 8px;
                background: #a1a5b7;
                border-radius: 50%;
                display: inline-block;
                animation: typingBounce 1.2s infinite;
            }
            .typing-dot:nth-child(2) { animation-delay: .15s; }
            .typing-dot:nth-child(3) { animation-delay: .30s; }
            @keyframes typingBounce {
                0%, 60%, 100% { transform: translateY(0); opacity: .5; }
                30%            { transform: translateY(-6px); opacity: 1; }
            }

            /* ── Message action button ── */
            .chat-msg-action {
                opacity: 0;
                transition: opacity .15s, transform .1s;
                width: 30px; height: 30px; padding: 0;
                border: none; border-radius: 50%; flex-shrink: 0;
                display: flex; align-items: center; justify-content: center;
                background: #fff;
                box-shadow: 0 1px 6px rgba(0,0,0,.18);
                cursor: pointer;
                line-height: 1;
            }
            .chat-msg-row:hover .chat-msg-action { opacity: 1; }
            .chat-msg-action:hover  { background: #f5f5f5; transform: scale(1.08); }
            .chat-msg-action:focus  { opacity: 1; outline: 2px solid #009ef7; outline-offset: 2px; }
            .chat-msg-action:active { transform: scale(.94); }
            .chat-msg-action .cdm-dots {
                font-size: 17px; font-weight: 800; color: #5e6278;
                letter-spacing: 0; line-height: 1;
                pointer-events: none; user-select: none;
            }

            /* ── Clickable chat header (avatar / name / status open the dropdown) ── */
            .chat-header-clickable { cursor: pointer; }

            /* ── Message action context menu ── */
            #chat_del_menu {
                position: fixed; z-index: 9990;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 6px 28px rgba(0,0,0,.18);
                min-width: 220px;
                overflow: hidden;
                padding: 4px 0;
            }
            #chat_del_menu .cdm-item {
                display: flex; align-items: center; gap: 10px;
                padding: 11px 16px; cursor: pointer;
                font-size: .84rem; font-weight: 600;
                color: #3f4254; transition: background .1s;
                user-select: none;
            }
            #chat_del_menu .cdm-item:hover { background: #f9f9f9; }
            #chat_del_menu .cdm-item.danger { color: #f1416c; }
            #chat_del_menu .cdm-item .cdm-icon { font-size: 1rem; width: 20px; text-align: center; flex-shrink: 0; }
            #chat_del_menu .cdm-sep { height: 1px; background: #f1f1f4; margin: 3px 0; }

            /* ── Deleted message placeholder ── */
            .chat-deleted-msg {
                font-style: italic; color: #a1a5b7; font-size: .8rem;
                display: flex; align-items: center; gap: 6px;
            }

            /* ── Reaction trigger button (matches .chat-msg-action) ── */
            .chat-reaction-trigger {
                opacity: 0;
                transition: opacity .15s, transform .1s;
                width: 30px; height: 30px; padding: 0;
                border: none; border-radius: 50%; flex-shrink: 0;
                display: flex; align-items: center; justify-content: center;
                background: #fff;
                box-shadow: 0 1px 6px rgba(0,0,0,.18);
                cursor: pointer;
                font-size: 15px;
                line-height: 1;
            }
            .chat-msg-row:hover .chat-reaction-trigger { opacity: 1; }
            .chat-reaction-trigger:hover  { background: #f5f5f5; transform: scale(1.08); }
            .chat-reaction-trigger:focus  { opacity: 1; outline: 2px solid #009ef7; outline-offset: 2px; }

            /* ── Reaction pills under a message bubble ── */
            .chat-reaction-row { display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px; }
            .chat-reaction-pill {
                display: inline-flex; align-items: center; gap: 4px;
                padding: 1px 8px; border-radius: 999px;
                background: #f1f1f4; border: 1px solid #e4e6ef;
                font-size: .76rem; cursor: pointer; user-select: none;
                transition: background .12s, border-color .12s;
            }
            .chat-reaction-pill:hover { background: #e4e6ef; }
            .chat-reaction-pill.mine  { background: #eef6ff; border-color: #bfdbfe; }
            .chat-reaction-pill .crp-count { color: #5e6278; font-weight: 600; }

            /* ── Reaction quick-bar (👍❤️😂😮😢🙏 + more) ── */
            #chat_reaction_bar {
                position: fixed; z-index: 9991;
                display: flex; align-items: center; gap: 2px;
                background: #fff; border-radius: 999px;
                box-shadow: 0 6px 28px rgba(0,0,0,.18);
                padding: 5px 7px;
            }
            #chat_reaction_bar .crb-emoji {
                width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
                font-size: 18px; border-radius: 50%; cursor: pointer; transition: transform .1s, background .1s;
            }
            #chat_reaction_bar .crb-emoji:hover { background: #f1f1f4; transform: scale(1.18); }
            #chat_reaction_bar .crb-more { font-size: 13px; color: #5e6278; }

            /* ── Emoji palette popup (composer + "more" reactions) ── */
            #chat_emoji_popup {
                position: fixed; z-index: 9992;
                width: 280px; max-height: 320px; overflow-y: auto;
                background: #fff; border-radius: 12px;
                box-shadow: 0 6px 28px rgba(0,0,0,.18);
                padding: 10px;
            }
            #chat_emoji_popup .cep-cat-label {
                font-size: .72rem; font-weight: 700; color: #a1a5b7;
                text-transform: uppercase; letter-spacing: .03em;
                margin: 8px 2px 4px;
            }
            #chat_emoji_popup .cep-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
            #chat_emoji_popup .cep-emoji {
                display: flex; align-items: center; justify-content: center;
                font-size: 19px; height: 32px; border-radius: 8px; cursor: pointer;
                transition: background .1s, transform .1s;
            }
            #chat_emoji_popup .cep-emoji:hover { background: #f1f1f4; transform: scale(1.15); }

            /* ── Composer emoji button ── */
            [data-kt-element="emoji"] { font-size: 18px; line-height: 1; }

            /* ── Lightbox ── */
            #navuli_lightbox {
                position: fixed; inset: 0; z-index: 99999;
                background: rgba(0,0,0,.93);
                display: none; align-items: center; justify-content: center;
                flex-direction: column;
            }
            #navuli_lightbox.lb-open { display: flex; }
            #lb_img_wrap {
                display: flex; align-items: center; justify-content: center;
                flex: 1; width: 100%; padding: 60px 80px 50px;
                box-sizing: border-box;
            }
            #lb_img {
                max-width: 100%; max-height: 80vh;
                object-fit: contain;
                border-radius: 6px;
                box-shadow: 0 8px 40px rgba(0,0,0,.6);
                transition: opacity .15s;
            }
            .lb-btn {
                position: fixed;
                background: rgba(255,255,255,.12);
                border: none; border-radius: 50%;
                color: #fff; cursor: pointer;
                width: 48px; height: 48px;
                display: flex; align-items: center; justify-content: center;
                font-size: 1.4rem; font-weight: 300;
                transition: background .15s;
                z-index: 100001;
            }
            .lb-btn:hover { background: rgba(255,255,255,.25); }
            #lb_close { top: 16px; right: 20px; font-size: 1.2rem; }
            #lb_prev  { left: 14px; top: 50%; transform: translateY(-50%); }
            #lb_next  { right: 14px; top: 50%; transform: translateY(-50%); }
            #lb_footer {
                position: fixed; bottom: 0; left: 0; right: 0;
                padding: 12px 20px;
                background: linear-gradient(transparent, rgba(0,0,0,.7));
                display: flex; align-items: center; justify-content: center;
                gap: 12px; color: #fff; font-size: .85rem;
            }
            #lb_counter { font-weight: 600; letter-spacing: .05em; }
            #lb_name    { color: rgba(255,255,255,.55); max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
		</style>
		
		<!--begin::Javascript-->
		<script>var hostUrl = "<?php echo base_url(); ?>app/assets/";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="<?php echo base_url(); ?>app/assets/plugins/global/plugins.bundle.js"></script>
		<script src="<?php echo base_url(); ?>app/assets/js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="<?php echo base_url(); ?>app/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		<script src="<?php echo base_url(); ?>app/assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="<?php echo base_url(); ?>app/assets/js/widgets.bundle.js"></script>
		<script src="<?php echo base_url(); ?>app/assets/js/custom/widgets.js"></script>
		<!--begin::Socket.IO + chat config-->
		<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
		<script>
			window.NAVULI_BASE_URL   = "<?= rtrim(base_url(), '/') ?>";
			window.NAVULI_SOCKET_URL = "<?= getenv('CHAT_SOCKET_URL') ?: 'http://localhost:3000' ?>";
			window.NAVULI_MY_NAME    = "<?= esc(trim(session('fname') . ' ' . session('name')), 'js') ?>";
			window.NAVULI_MY_PHOTO   = "<?php $__p = session('photo'); echo $__p ? esc(base_url('uploads/profilePhoto/' . $__p), 'js') : ''; ?>";
		</script>
		<!--end::Socket.IO + chat config-->
		<script src="<?php echo base_url(); ?>app/assets/js/custom/apps/chat/chat.js?v=<?= filemtime(FCPATH . 'app/assets/js/custom/apps/chat/chat.js') ?>"></script>
		<script src="<?php echo base_url(); ?>app/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="<?php echo base_url(); ?>app/assets/js/custom/utilities/modals/create-app.js"></script>
		<script src="<?php echo base_url(); ?>app/assets/js/custom/utilities/modals/new-target.js"></script>
		<script src="<?php echo base_url(); ?>app/assets/js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		
		<!-- jsPDF for datatable export to PDF -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
        
		<!--end::Javascript-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
					<!--begin::Header container-->
					<div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
						<!--begin::Sidebar mobile toggle-->
						<div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
							<div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
								<i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</div>
						</div>
						<!--end::Sidebar mobile toggle-->
						<!--begin::Mobile logo-->
						<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
							<a href="<?php echo base_url(); ?>school/dashboard" class="d-lg-none">
								<img alt="Logo" src="<?php echo base_url(); ?>navuli_logo_white_icon.png" class="h-30px" />
							</a>
						</div>
						<!--end::Mobile logo-->
						<!--begin::Header wrapper-->
						<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
							<!--begin::Menu wrapper-->
							<div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
								<!--begin::Menu-->
								<div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
									<!--begin:Menu item-->
									
									<!--begin:Menu item-->
									<div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
										<!--begin:Menu link-->
										<span class="menu-link">
											<span class="menu-title">Help</span>
											<span class="menu-arrow d-lg-none"></span>
										</span>
										<!--end:Menu link-->
										<!--begin:Menu sub-->
										<div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-200px">
											<!--begin:Menu item-->
											<div class="menu-item">
												<!--begin:Menu link-->
												<a class="menu-link" href="<?php echo base_url(); ?>school/guide" title="Check out the complete documentation" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
													<span class="menu-icon">
														<i class="ki-duotone ki-abstract-26 fs-2">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
													</span>
													<span class="menu-title">User Guide</span>
												</a>
												<!--end:Menu link-->
											</div>
											<!--end:Menu item-->
										</div>
										<!--end:Menu sub-->
									</div>
									<!--end:Menu item-->
								</div>
								<!--end::Menu-->
							</div>
							<!--end::Menu wrapper-->
							<!--begin::Navbar-->
							<div class="app-navbar flex-shrink-0">
								<!--begin::Activities-->
								<div class="app-navbar-item ms-1 ms-md-4">
									<!--begin::Drawer toggle-->
									<div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative" id="kt_activities_toggle">
										<i class="ki-duotone ki-messages fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
										</i>
										<span id="navuli_chat_badge"
										      class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-sm bg-danger d-none"
										      style="font-size:9px;min-width:16px;height:16px;line-height:16px;padding:0 3px;">
										</span>
									</div>
									<!--end::Drawer toggle-->
								</div>
								<!--end::Activities-->
								<!--begin::Notifications-->
								<div class="app-navbar-item ms-1 ms-md-4">
									<!--begin::Menu wrapper-->
									<div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" id="kt_menu_item_wow">
										<i class="ki-duotone ki-bell fs-2">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
										<span id="notif-badge" class="position-absolute top-0 start-100 translate-middle badge badge-circle badge-danger fs-10 min-w-15px h-15px" style="display:none;"></span>
									</div>
									<!--begin::Menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
										<!--begin::Heading-->
										<div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('<?php echo base_url(); ?>app/assets/media/misc/menu-header-bg.jpg')">
											<h3 class="text-white fw-semibold px-9 mt-10 mb-6">
												Notifications
												<span id="notif-unread-label" class="fs-8 opacity-75 ps-3" style="display:none;"></span>
											</h3>
											<ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
												<li class="nav-item">
													<a class="nav-link text-white opacity-75 opacity-state-100 pb-4 active" data-bs-toggle="tab" href="#notif_tab_activity">Activity</a>
												</li>
												<li class="nav-item">
													<a class="nav-link text-white opacity-75 opacity-state-100 pb-4" data-bs-toggle="tab" href="#notif_tab_alert">Alert</a>
												</li>
											</ul>
										</div>
										<!--end::Heading-->
										<!--begin::Tab content-->
										<div class="tab-content">
											<!--begin::Activity tab-->
											<div class="tab-pane fade show active" id="notif_tab_activity" role="tabpanel">
												<div class="scroll-y mh-325px my-5 px-8" id="notif-activity-list">
													<div class="text-center py-8 text-muted fs-8">
														<span class="spinner-border spinner-border-sm"></span>
													</div>
												</div>
												<div class="py-3 text-center border-top">
													<a href="<?php echo base_url('user/notification'); ?>?type=Activity" class="btn btn-color-gray-600 btn-active-color-primary">View All
														<i class="ki-duotone ki-arrow-right fs-5"><span class="path1"></span><span class="path2"></span></i>
													</a>
												</div>
											</div>
											<!--end::Activity tab-->
											<!--begin::Alert tab-->
											<div class="tab-pane fade" id="notif_tab_alert" role="tabpanel">
												<div class="scroll-y mh-325px my-5 px-8" id="notif-alert-list">
													<div class="text-center py-8 text-muted fs-8">
														<span class="spinner-border spinner-border-sm"></span>
													</div>
												</div>
												<div class="py-3 text-center border-top">
													<a href="<?php echo base_url('user/notification'); ?>?type=Alert" class="btn btn-color-gray-600 btn-active-color-primary">View All
														<i class="ki-duotone ki-arrow-right fs-5"><span class="path1"></span><span class="path2"></span></i>
													</a>
												</div>
											</div>
											<!--end::Alert tab-->
										</div>
										<!--end::Tab content-->
									</div>
									<!--end::Menu-->
									<!--end::Menu wrapper-->
								</div>
								<!--end::Notifications-->

								<script>
								(function () {
									const NOTIF_URL      = '<?php echo base_url("user/getNotifications"); ?>';
									const MARK_READ_URL  = '<?php echo base_url("user/markNotificationsRead"); ?>';
									const CI_TOKEN       = '<?php echo csrf_hash(); ?>';

									let loaded = false;

									function renderItem(n) {
										const unread = n.status === 'Unread'
											? '<span class="bullet bullet-dot bg-danger h-6px w-6px ms-1"></span>'
											: '';
										return `<div class="d-flex flex-stack py-3">
											<div class="d-flex align-items-center">
												<div class="symbol symbol-35px me-4">
													<span class="symbol-label bg-light-${n.theme}">${n.icon}</span>
												</div>
												<div class="mb-0 me-2">
													<span class="fw-bold text-gray-800 fs-7">${n.title}${unread}</span>
													<div class="text-gray-500 fs-8">${n.desc}</div>
												</div>
											</div>
											<span class="badge badge-light fs-9 text-nowrap">${n.age}</span>
										</div>`;
									}

									function renderList(items, containerId) {
										const el = document.getElementById(containerId);
										if (!el) return;
										if (!items || !items.length) {
											el.innerHTML = '<div class="text-center text-muted fs-8 py-8">No entries found.</div>';
											return;
										}
										el.innerHTML = items.map(renderItem).join('');
									}

									function loadNotifications() {
										if (loaded) return;
										loaded = true;
										fetch(NOTIF_URL)
											.then(r => r.json())
											.then(data => {
												if (!data.success) return;
												renderList(data.activities, 'notif-activity-list');
												renderList(data.alerts,     'notif-alert-list');

												const badge = document.getElementById('notif-badge');
												const label = document.getElementById('notif-unread-label');
												if (data.unread_count > 0) {
													badge.textContent  = data.unread_count > 99 ? '99+' : data.unread_count;
													badge.style.display = '';
													label.textContent   = data.unread_count + ' unread';
													label.style.display = '';
												} else {
													badge.style.display = 'none';
													label.style.display = 'none';
												}
											})
											.catch(() => {});
									}

									function markRead() {
										fetch(MARK_READ_URL, {
											method: 'POST',
											headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CI_TOKEN },
										})
										.then(() => {
											document.getElementById('notif-badge').style.display = 'none';
											document.getElementById('notif-unread-label').style.display = 'none';
										})
										.catch(() => {});
									}

									// Load on first open; mark read when dropdown closes
									const trigger = document.getElementById('kt_menu_item_wow');
									const menu    = document.getElementById('kt_menu_notifications');
									if (trigger) {
										trigger.addEventListener('click', loadNotifications);
									}
									if (menu) {
										menu.addEventListener('kt.menu.dropdown.hide', markRead);
									}

									// Also load badge on page load (fast count-only not worth a separate endpoint)
									loadNotifications();
								})();
								</script>
								<!--begin::Theme mode-->
								<div class="app-navbar-item ms-1 ms-md-4">
									<!--begin::Menu toggle-->
									<a href="#" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px" data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<i class="ki-duotone ki-night-day theme-light-show fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
											<span class="path3"></span>
											<span class="path4"></span>
											<span class="path5"></span>
											<span class="path6"></span>
											<span class="path7"></span>
											<span class="path8"></span>
											<span class="path9"></span>
											<span class="path10"></span>
										</i>
										<i class="ki-duotone ki-moon theme-dark-show fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</a>
									<!--begin::Menu toggle-->
									<!--begin::Menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-duotone ki-night-day fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
														<span class="path5"></span>
														<span class="path6"></span>
														<span class="path7"></span>
														<span class="path8"></span>
														<span class="path9"></span>
														<span class="path10"></span>
													</i>
												</span>
												<span class="menu-title">Light</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-duotone ki-moon fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</span>
												<span class="menu-title">Dark</span>
											</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-3 my-0">
											<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
												<span class="menu-icon" data-kt-element="icon">
													<i class="ki-duotone ki-screen fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
													</i>
												</span>
												<span class="menu-title">System</span>
											</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::Menu-->
								</div>
								<!--end::Theme mode-->
								<!--begin::User menu-->
								<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
									<!--begin::Menu wrapper-->
									<div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
										<img src="<?php echo base_url(); ?>uploads/profilePhoto/<?php echo session()->get('photo'); ?>" class="rounded-3" alt="user" />
									</div>
									<!--begin::User account menu-->
									<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
										<!--begin::Menu item-->
										<div class="menu-item px-3">
											<div class="menu-content d-flex align-items-center px-3">
												<!--begin::Avatar-->
												<div class="symbol symbol-50px me-5">
													<img alt="Logo" src="<?php echo base_url(); ?>uploads/profilePhoto/<?php echo session()->get('photo'); ?>" />
												</div>
												<!--end::Avatar-->
												<!--begin::Username-->
												<div class="d-flex flex-column">
													<div class="fw-bold fs-5"><?php echo esc(session()->get('name')); ?></div>
													<span class="badge badge-light-success fw-bold fs-8 px-2 py-1"><?php echo esc(session()->get('roleName')); ?></span>
												</div>
												<!--end::Username-->
											</div>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator my-2"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="account/overview.html" class="menu-link px-5">School Profile</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
											<a href="#" class="menu-link px-5">
												<span class="menu-title">School Subscription</span>
												<span class="menu-arrow"></span>
											</a>
											<!--begin::Menu sub-->
											<div class="menu-sub menu-sub-dropdown w-175px py-4">
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="account/referrals.html" class="menu-link px-5">Referrals</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="account/billing.html" class="menu-link px-5">Billing</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="account/statements.html" class="menu-link px-5">Payments</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="account/statements.html" class="menu-link d-flex flex-stack px-5">Statements 
													<span class="ms-2 lh-0" data-bs-toggle="tooltip" title="View your statements">
														<i class="ki-duotone ki-information-5 fs-5">
															<span class="path1"></span>
															<span class="path2"></span>
															<span class="path3"></span>
														</i>
													</span></a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu separator-->
												<div class="separator my-2"></div>
												<!--end::Menu separator-->
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<div class="menu-content px-3">
														<label class="form-check form-switch form-check-custom form-check-solid">
															<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
															<span class="form-check-label text-muted fs-7">Notifications</span>
														</label>
													</div>
												</div>
												<!--end::Menu item-->
											</div>
											<!--end::Menu sub-->
										</div>
										<!--end::Menu item-->
										<!--begin::Menu separator-->
										<div class="separator my-2"></div>
										<!--end::Menu separator-->
										<!--begin::Menu item-->
										<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
											<a href="#" class="menu-link px-5">
												<span class="menu-title position-relative">Mode 
												<span class="ms-5 position-absolute translate-middle-y top-50 end-0">
													<i class="ki-duotone ki-night-day theme-light-show fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
														<span class="path3"></span>
														<span class="path4"></span>
														<span class="path5"></span>
														<span class="path6"></span>
														<span class="path7"></span>
														<span class="path8"></span>
														<span class="path9"></span>
														<span class="path10"></span>
													</i>
													<i class="ki-duotone ki-moon theme-dark-show fs-2">
														<span class="path1"></span>
														<span class="path2"></span>
													</i>
												</span></span>
											</a>
											<!--begin::Menu-->
											<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
												<!--begin::Menu item-->
												<div class="menu-item px-3 my-0">
													<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
														<span class="menu-icon" data-kt-element="icon">
															<i class="ki-duotone ki-night-day fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
																<span class="path4"></span>
																<span class="path5"></span>
																<span class="path6"></span>
																<span class="path7"></span>
																<span class="path8"></span>
																<span class="path9"></span>
																<span class="path10"></span>
															</i>
														</span>
														<span class="menu-title">Light</span>
													</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3 my-0">
													<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
														<span class="menu-icon" data-kt-element="icon">
															<i class="ki-duotone ki-moon fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
															</i>
														</span>
														<span class="menu-title">Dark</span>
													</a>
												</div>
												<!--end::Menu item-->
												<!--begin::Menu item-->
												<div class="menu-item px-3 my-0">
													<a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
														<span class="menu-icon" data-kt-element="icon">
															<i class="ki-duotone ki-screen fs-2">
																<span class="path1"></span>
																<span class="path2"></span>
																<span class="path3"></span>
																<span class="path4"></span>
															</i>
														</span>
														<span class="menu-title">System</span>
													</a>
												</div>
												<!--end::Menu item-->
											</div>
											<!--end::Menu-->
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
											<a href="#" class="menu-link px-5">
												<span class="menu-title position-relative">Language 
												<span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">English 
												<img class="w-15px h-15px rounded-1 ms-2" src="<?php echo base_url(); ?>app/assets/media/flags/united-states.svg" alt="" /></span></span>
											</a>
											<!--begin::Menu sub-->
											<div class="menu-sub menu-sub-dropdown w-175px py-4">
												<!--begin::Menu item-->
												<div class="menu-item px-3">
													<a href="account/settings.html" class="menu-link d-flex px-5 active">
													<span class="symbol symbol-20px me-4">
														<img class="rounded-1" src="<?php echo base_url(); ?>app/assets/media/flags/united-states.svg" alt="" />
													</span>English</a>
												</div>
												<!--end::Menu item-->
											</div>
											<!--end::Menu sub-->
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5 my-1">
											<a href="account/settings.html" class="menu-link px-5">Account Settings</a>
										</div>
										<!--end::Menu item-->
										<!--begin::Menu item-->
										<div class="menu-item px-5">
											<a href="<?php echo base_url(); ?>auth/logout" class="menu-link px-5">Sign Out</a>
										</div>
										<!--end::Menu item-->
									</div>
									<!--end::User account menu-->
									<!--end::Menu wrapper-->
								</div>
								<!--end::User menu-->
								<!--begin::Header menu toggle-->
								<div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
									<div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
										<i class="ki-duotone ki-element-4 fs-1">
											<span class="path1"></span>
											<span class="path2"></span>
										</i>
									</div>
								</div>
								<!--end::Header menu toggle-->
								<!--begin::Aside toggle-->
								<!--end::Header menu toggle-->
							</div>
							<!--end::Navbar-->
						</div>
						<!--end::Header wrapper-->
					</div>
					<!--end::Header container-->
				</div>
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Sidebar-->
					<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
						<!--begin::Logo-->
						<div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
							<!--begin::Logo image-->
							<a href="<?php echo base_url(); ?>school/dashboard">
								<img alt="Logo" src="<?php echo base_url(); ?>navuli_logo_white.png" class="h-50px app-sidebar-logo-default" />
								<img alt="Logo" src="<?php echo base_url(); ?>navuli_logo_white_icon.png" class="h-30px app-sidebar-logo-minimize" />
							</a>
							<!--end::Logo image-->
							<!--begin::Sidebar toggle-->
							<!--begin::Minimized sidebar setup:
            if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") { 
                1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
            }
        -->
							<div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
								<i class="ki-duotone ki-black-left-line fs-3 rotate-180">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</div>
							<!--end::Sidebar toggle-->
						</div>
						<!--end::Logo-->
						<!--begin::sidebar menu-->
						<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
						    
						    
						    
						    
						    
							<!--begin::Menu wrapper-->
							<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
								<!--begin::Scroll wrapper-->
								<div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
									<!--begin::Menu-->
									<div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
										
										
										
										
										
										
										
										
										<!--begin:Menu item-->
										<div class="menu-item pt-5">
											<!--begin:Menu content-->
											<div class="menu-content">
												<span class="menu-heading fw-bold text-uppercase fs-7">Modules</span>
											</div>
											<!--end:Menu content-->
										</div>
										<!--end:Menu item-->

                                        <?php
                                        // Option 1: Using the helper (if you created it)
                                        // echo generate_navigation_menu();

                                        // Option 2: Using the Navigation library (recommended)
                                        $navigation = new \App\Libraries\Navigation();
                                        echo $navigation->generateMenu();
                                        ?>
									</div>
									<!--end::Menu-->
								</div>
								<!--end::Scroll wrapper-->
							</div>
							<!--end::Menu wrapper-->
							
							
							
							
							
							
							
						</div>
						<!--end::sidebar menu-->
					</div>
					<!--end::Sidebar-->
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
						
						<!-- Begin: Load View -->
					
						<?php 
							if(isset($_view) && $_view){
								echo view($_view);
							}
						?> 
						<!-- End: Load View -->
						
							
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<div id="kt_app_footer" class="app-footer">
							<!--begin::Footer container-->
							<div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
								<!--begin::Copyright-->
								<div class="text-gray-900 order-2 order-md-1">
									<span class="text-muted fw-semibold me-1">2024&copy;</span>
									<a href="https://keenthemes.com" target="_blank" class="text-gray-800 text-hover-primary">Navuli</a>
								</div>
								<!--end::Copyright-->
							</div>
							<!--end::Footer container-->
						</div>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<!--begin::Drawers-->
		<!--begin::Activities drawer-->
		<div id="kt_activities"
		     class="bg-body d-flex flex-column"
		     style="width:300px;"
		     data-kt-drawer="true"
		     data-kt-drawer-name="activities"
		     data-kt-drawer-activate="true"
		     data-kt-drawer-overlay="true"
		     data-kt-drawer-width="300px"
		     data-kt-drawer-direction="end"
		     data-kt-drawer-toggle="#kt_activities_toggle"
		     data-kt-drawer-close="#kt_activities_close">

			<!--begin::Header-->
			<div class="d-flex align-items-center justify-content-between px-5 py-4 border-bottom border-gray-200" id="kt_activities_header">
				<div class="d-flex align-items-center gap-2">
					<i class="ki-duotone ki-people fs-3 text-primary">
						<span class="path1"></span><span class="path2"></span>
						<span class="path3"></span><span class="path4"></span><span class="path5"></span>
					</i>
					<h6 class="fw-bold text-gray-900 mb-0 fs-6">Users</h6>
					<span id="ucl_online_count" class="badge badge-light-success fs-9 fw-semibold ms-1" style="display:none;"></span>
				</div>
				<button type="button" class="btn btn-sm btn-icon btn-active-light-primary" id="kt_activities_close">
					<i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
				</button>
			</div>
			<!--end::Header-->

			<!--begin::Search-->
			<div class="px-4 py-3 border-bottom border-gray-200">
				<div class="position-relative">
					<i class="ki-duotone ki-magnifier fs-4 text-gray-400 position-absolute top-50 translate-middle-y ms-3">
						<span class="path1"></span><span class="path2"></span>
					</i>
					<input type="text" id="ucl_search" class="form-control form-control-sm ps-10" placeholder="Search users..." autocomplete="off" />
				</div>
			</div>
			<!--end::Search-->

			<!--begin::User list (scrollable)-->
			<div id="ucl_list" class="flex-grow-1 overflow-auto px-2 py-2">
				<!--rows injected here, sentinel stays at bottom-->
				<div id="ucl_loading" class="text-center py-5">
					<span class="spinner-border spinner-border-sm text-primary"></span>
					<div class="text-muted fs-8 mt-2">Loading users...</div>
				</div>
				<div id="ucl_sentinel" style="height:1px;"></div>
			</div>
			<!--end::User list-->

		</div>
		<!--end::Activities drawer-->

		<script>
		(function () {
			'use strict';

			const PHOTO_BASE    = '<?= base_url('uploads/profilePhoto/') ?>';
			const LIST_URL      = '<?= base_url('user/chatUserList') ?>';
			const MESSAGE_BASE  = '<?= base_url('message/') ?>';

			let page              = 1;
			let loading           = false;
			let hasMore           = true;
			let searchTimer       = null;
			let drawerCurrentUser = null;

			const listEl     = document.getElementById('ucl_list');
			const loadingEl  = document.getElementById('ucl_loading');
			const sentinelEl = document.getElementById('ucl_sentinel');
			const searchEl   = document.getElementById('ucl_search');
			const countEl    = document.getElementById('ucl_online_count');

			function esc(str) {
				return String(str ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
			}

			function dotColor(status) {
				return status === 'Online' ? '#50cd89' : '#a1a5b7';
			}

			// Per-user unread counts for the popup list
			const uclUnread = {};

			function setUnreadBadge(userId, count) {
				userId = String(userId);
				uclUnread[userId] = count;
				const row = listEl.querySelector(`.ucl-row[data-user-id="${userId}"]`);
				if (!row) return;
				const badge = row.querySelector('.ucl-unread-badge');
				if (!badge) return;
				if (count > 0) {
					badge.textContent   = count > 99 ? '99+' : String(count);
					badge.style.display = 'flex';
				} else {
					badge.textContent   = '';
					badge.style.display = 'none';
				}
			}

			function loadUnreadCounts() {
				fetch('<?= base_url('chat/unread-per-user') ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
					.then(r => r.json())
					.then(data => {
						if (!data.success) return;
						Object.entries(data.counts).forEach(([uid, cnt]) => setUnreadBadge(uid, cnt));
					})
					.catch(() => {});
			}

			function buildRow(u) {
				const initials = ((u.fname || '').charAt(0) + (u.lname || '').charAt(0)).toUpperCase();
				const avatar   = u.profile_photo
					? `<img src="${PHOTO_BASE}${u.profile_photo}" class="rounded-circle" style="width:42px;height:42px;object-fit:cover;" alt="">`
					: `<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary bg-light-primary fs-7" style="width:42px;height:42px;flex-shrink:0;">${initials}</div>`;
				const dot = `<span style="position:absolute;bottom:1px;right:1px;width:11px;height:11px;border-radius:50%;background:${dotColor(u.online_status)};border:2px solid #fff;"></span>`;

				const row = document.createElement('div');
				row.className            = 'ucl-row d-flex align-items-center gap-3 px-3 py-2 rounded cursor-pointer';
				row.style.cssText        = 'transition:background .15s;';
				row.dataset.userId       = u.user_id;
				row.dataset.ktChatUserId = u.user_id;
				row.dataset.userName     = `${u.fname} ${u.lname}`;
				row.dataset.userPhoto    = u.profile_photo ? `${PHOTO_BASE}${u.profile_photo}` : '';
				row.dataset.userStatus   = u.online_status;

				const cnt         = uclUnread[String(u.user_id)] || 0;
				const badgeDisplay = cnt > 0 ? 'flex' : 'none';
				const badgeText    = cnt > 99 ? '99+' : (cnt > 0 ? String(cnt) : '');

				row.innerHTML = `
					<div style="position:relative;flex-shrink:0;">${avatar}${dot}</div>
					<div style="min-width:0;flex:1;">
						<div class="fw-semibold text-gray-800 fs-7 text-truncate">${esc(u.fname)} ${esc(u.lname)}</div>
						<div class="fs-9 ${u.online_status === 'Online' ? 'text-success' : 'text-muted'}">${esc(u.online_status)}</div>
					</div>
					<span class="ucl-unread-badge" style="display:${badgeDisplay};align-items:center;justify-content:center;flex-shrink:0;min-width:20px;height:20px;border-radius:10px;background:#f1416c;color:#fff;font-size:10px;font-weight:700;line-height:1;padding:0 5px;">${badgeText}</span>`;
				row.addEventListener('mouseenter', () => row.style.background = 'var(--bs-gray-100)');
				row.addEventListener('mouseleave', () => row.style.background = '');
				row.addEventListener('click', () => openChat(u, row));
				return row;
			}

			function openChat(u, row) {
				drawerCurrentUser = u;
				const initials = ((u.fname || '').charAt(0) + (u.lname || '').charAt(0)).toUpperCase();
				const isOnline = u.online_status === 'Online';

				const avatarHTML = u.profile_photo
					? `<img src="${PHOTO_BASE}${esc(u.profile_photo)}" class="rounded-circle" style="width:45px;height:45px;object-fit:cover;" alt="">`
					: `<div class="symbol-label bg-light-primary rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary fs-5" style="width:45px;height:45px;">${initials}</div>`;

				// Update drawer header
				document.getElementById('kt_drawer_chat_avatar').innerHTML     = avatarHTML;
				document.getElementById('kt_drawer_chat_name').textContent     = `${u.fname} ${u.lname}`;
				document.getElementById('kt_drawer_chat_status_dot').className =
					`badge badge-circle w-10px h-10px me-1 ${isOnline ? 'bg-success' : 'bg-secondary'}`;
				document.getElementById('kt_drawer_chat_status_text').textContent = isOnline ? 'Online' : 'Offline';

				// Switch drawers
				const activitiesDrawer = KTDrawer.getInstance(document.getElementById('kt_activities'));
				if (activitiesDrawer) activitiesDrawer.hide();

				const chatDrawerEl = document.getElementById('kt_drawer_chat');
				const chatDrawer   = chatDrawerEl && KTDrawer.getInstance(chatDrawerEl);
				if (chatDrawer) {
					chatDrawer.show();
				} else if (chatDrawerEl) {
					// Fallback: force-add the Metronic drawer-on class directly
					chatDrawerEl.classList.add('drawer-on');
					document.body.classList.add('drawer-on');
				}

				// Clear unread badge for this user
				setUnreadBadge(u.user_id, 0);

				// Load message history and join socket room
				if (window.NavuliChat) NavuliChat.openConversation(u.user_id, row);
			}

			// Expose globally so other pages can open the chat drawer directly
			window.openChat = openChat;

			// "Open in Message" — navigate to the full Message page with the active conversation
			document.addEventListener('click', function (e) {
				const el = e.target.closest('[data-kt-element="open-in-message"]');
				if (!el) return;
				e.preventDefault();
				if (!drawerCurrentUser) return;
				const u    = drawerCurrentUser;
				const name = `${u.fname || ''} ${u.lname || ''}`.trim();
				const photo = u.profile_photo ? (PHOTO_BASE + u.profile_photo) : '';
				window.location.href = MESSAGE_BASE + u.user_id
					+ '?name='  + encodeURIComponent(name)
					+ '&photo=' + encodeURIComponent(photo);
			});

			function fetchPage() {
				if (loading || !hasMore) return;
				loading = true;
				loadingEl.style.display = 'block';

				const url = `${LIST_URL}?page=${page}&search=${encodeURIComponent(searchEl.value.trim())}`;
				fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
					.then(r => r.json())
					.then(data => {
						if (!data.success) return;
						// Insert rows before loading indicator (loading+sentinel stay at bottom)
						data.users.forEach(u => listEl.insertBefore(buildRow(u), loadingEl));
						hasMore = data.hasMore;
						page    = data.nextPage;
						if (page === 2 && countEl) {
							countEl.textContent   = data.onlineCount > 0 ? data.onlineCount + ' online' : '';
							countEl.style.display = data.onlineCount > 0 ? '' : 'none';
						}
						loadUnreadCounts();
					})
					.catch(() => {/* silent */})
					.finally(() => {
						loadingEl.style.display = 'none';
						loading = false;
					});
			}

			// IntersectionObserver on the sentinel (sits below rows at bottom of list)
			// Fires when user scrolls sentinel into viewport → load next page
			const observer = new IntersectionObserver(entries => {
				if (entries[0].isIntersecting) fetchPage();
			}, { root: null, rootMargin: '0px', threshold: 0 });
			observer.observe(sentinelEl);

			// Debounced search: reset list and reload from page 1
			searchEl.addEventListener('input', () => {
				clearTimeout(searchTimer);
				searchTimer = setTimeout(() => {
					page    = 1;
					hasMore = true;
					listEl.querySelectorAll('.ucl-row').forEach(el => el.remove());
					if (countEl) { countEl.textContent = ''; countEl.style.display = 'none'; }
					fetchPage();
				}, 350);
			});

			// Drawer open: fetch if list is still empty (fallback for IntersectionObserver)
			document.getElementById('kt_activities_toggle')?.addEventListener('click', () => {
				if (!listEl.querySelector('.ucl-row') && !loading) fetchPage();
			});

			// Pre-load in background on page load so data is ready when drawer opens
			fetchPage();

			// Live update: a user we're allowed to see just connected and isn't rendered yet
			// (e.g. they're beyond the currently-loaded page) -- add them at the top without reload.
			document.addEventListener('navuli:userOnline', e => {
				const u = e.detail;
				if (!u || !u.user_id) return;
				if (listEl.querySelector(`.ucl-row[data-user-id="${u.user_id}"]`)) return;
				const term = searchEl.value.trim().toLowerCase();
				if (term && !`${u.fname} ${u.lname}`.toLowerCase().includes(term)) return;
				listEl.insertBefore(buildRow(u), listEl.firstChild);
			});

			// Real-time unread badge updates from the chat module
			document.addEventListener('navuli:unreadBadge', e => {
				const { userId, count, action } = e.detail || {};
				if (!userId) return;
				if (action === 'increment') {
					const current = uclUnread[String(userId)] || 0;
					setUnreadBadge(userId, current + 1);
				} else {
					setUnreadBadge(userId, count || 0);
				}
			});

		}());
		</script>

		<!--begin::Chat drawer-->
		<div id="kt_drawer_chat"
		     class="bg-body"
		     data-kt-drawer="true"
		     data-kt-drawer-name="chat"
		     data-kt-drawer-activate="true"
		     data-kt-drawer-overlay="true"
		     data-kt-drawer-width="{default:'300px', 'md': '500px'}"
		     data-kt-drawer-direction="end"
		     data-kt-drawer-close="#kt_drawer_chat_close">
			<!--begin::Messenger-->
			<div class="card w-100 rounded-0 border-0 d-flex flex-column h-100" id="kt_drawer_chat_messenger">
				<!--begin::Card header-->
				<div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
					<div class="card-title flex-grow-1">
						<div class="d-flex align-items-center">
							<div class="symbol symbol-45px symbol-circle me-3 position-relative chat-header-clickable" id="kt_drawer_chat_avatar">
								<!-- populated dynamically -->
							</div>
							<div class="d-flex flex-column flex-grow-1">
								<span class="fs-5 fw-bold text-gray-900 lh-1 chat-header-clickable" id="kt_drawer_chat_name">–</span>
								<div class="mt-1 d-flex align-items-center justify-content-between">
									<div class="d-flex align-items-center chat-header-clickable">
										<span id="kt_drawer_chat_status_dot" class="badge badge-circle w-10px h-10px me-1 bg-secondary"></span>
										<span class="fs-7 fw-semibold text-muted" id="kt_drawer_chat_status_text">Offline</span>
									</div>
									<div class="btn-group">
										<button type="button" class="btn btn-sm btn-icon btn-active-light-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="More options">
											<i class="ki-duotone ki-dots-vertical fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
										</button>
										<ul class="dropdown-menu dropdown-menu-end">
											<li><a class="dropdown-item" href="#" data-kt-element="block-toggle">Block</a></li>
											<li><a class="dropdown-item" href="#" data-kt-element="chat-transcript">Chat Transcript</a></li>
											<li><a class="dropdown-item" href="#" data-kt-element="open-in-message">Open in Message</a></li>
											<li><hr class="dropdown-divider"></li>
											<li><a class="dropdown-item text-danger" href="#" data-kt-element="clear-conversation">Clear Conversation</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="card-toolbar gap-1">
						<button type="button" class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_chat_voice_call" title="Voice call">
							<i class="fa-solid fa-phone fs-6"></i>
						</button>
						<button type="button" class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_chat_video_call" title="Video call">
							<i class="fa-solid fa-video fs-6"></i>
						</button>
						<div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_chat_minimize" title="Minimize">
							<i class="ki-duotone ki-minus fs-2"><span class="path1"></span></i>
						</div>
						<div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_chat_close">
							<i class="ki-duotone ki-cross-square fs-2"><span class="path1"></span><span class="path2"></span></i>
						</div>
					</div>
				</div>
				<!--end::Card header-->

				<!--begin::Card body-->
				<div class="card-body" id="kt_drawer_chat_messenger_body">
					<div class="scroll-y me-n5 pe-5"
					     data-kt-element="messages"
					     data-kt-scroll="true"
					     data-kt-scroll-activate="true"
					     data-kt-scroll-height="auto"
					     data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
					     data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body"
					     data-kt-scroll-offset="0px">

						<!--begin::Message out template (my sent messages)-->
						<div class="d-none" data-kt-element="template-out">
							<div class="d-flex justify-content-end mb-6 chat-msg-row">
								<div class="d-flex flex-column align-items-end">
									<div class="d-flex align-items-center mb-1 gap-2">
										<span class="text-muted fs-8" data-kt-element="message-time">Just now</span>
										<div class="symbol symbol-30px symbol-circle">
											<?php if (!empty(session('photo'))): ?>
											<img alt="Me" src="<?= base_url('uploads/profilePhoto/' . session('photo')) ?>" style="object-fit:cover;">
											<?php else: ?>
											<div class="symbol-label bg-light-primary fs-8 fw-bold text-primary"><?= esc(session('initial') ?? '?') ?></div>
											<?php endif; ?>
										</div>
									</div>
									<div class="d-flex align-items-center gap-1">
										<button class="chat-msg-action" type="button" title="More options">
											<span class="cdm-dots">⋯</span>
										</button>
										<button class="chat-reaction-trigger" type="button" title="React">😊</button>
										<div class="px-4 py-3 rounded bg-light-primary text-dark fw-semibold mw-lg-380px text-end" data-kt-element="message-text"></div>
									</div>
									<div class="chat-reaction-row justify-content-end" data-kt-element="reaction-row"></div>
								</div>
							</div>
						</div>
						<!--end::Message out template-->

						<!--begin::Message in template (their incoming messages)-->
						<div class="d-none" data-kt-element="template-in">
							<div class="d-flex justify-content-start mb-6 chat-msg-row">
								<div class="d-flex flex-column align-items-start">
									<div class="d-flex align-items-center mb-1 gap-2">
										<div class="symbol symbol-30px symbol-circle">
											<img alt="" src="<?= base_url('app/assets/media/avatars/blank.png') ?>" style="width:30px;height:30px;object-fit:cover;border-radius:50%;">
										</div>
										<span class="fw-semibold fs-8 text-gray-700" data-kt-element="message-sender-name">–</span>
										<span class="text-muted fs-8" data-kt-element="message-time">Just now</span>
									</div>
									<div class="d-flex align-items-center gap-1">
										<div class="px-4 py-3 rounded bg-light-info text-dark fw-semibold mw-lg-380px text-start" data-kt-element="message-text"></div>
										<button class="chat-reaction-trigger" type="button" title="React">😊</button>
										<button class="chat-msg-action" type="button" title="More options">
											<span class="cdm-dots">⋯</span>
										</button>
									</div>
									<div class="chat-reaction-row justify-content-start" data-kt-element="reaction-row"></div>
								</div>
							</div>
						</div>
						<!--end::Message in template-->

						<!--begin::Typing indicator bubble (hidden, injected by JS)-->
						<div class="d-none chat-typing-indicator" data-kt-element="typing-bubble">
							<div class="d-flex align-items-end gap-2">
								<div class="symbol symbol-30px symbol-circle flex-shrink-0">
									<div class="symbol-label bg-light-info fw-bold text-info fs-9" data-kt-element="typing-initial">?</div>
								</div>
								<div class="chat-typing-bubble">
									<span class="typing-dot"></span>
									<span class="typing-dot"></span>
									<span class="typing-dot"></span>
								</div>
							</div>
						</div>
						<!--end::Typing indicator bubble-->

						<!--begin::Empty state-->
						<div class="text-center py-10" id="kt_drawer_chat_empty">
							<i class="ki-duotone ki-message-text-2 fs-3x text-muted mb-3 d-block">
								<span class="path1"></span><span class="path2"></span><span class="path3"></span>
							</i>
							<span class="text-muted fs-6">No messages yet. Say hello!</span>
						</div>
						<!--end::Empty state-->

					</div>
				</div>
				<!--end::Card body-->

				<!--begin::Card footer-->
				<div class="card-footer pt-4" id="kt_drawer_chat_messenger_footer">
					<!--begin::Status bar (upload progress)-->
					<div class="min-h-20px mb-1">
						<span data-kt-element="upload-progress" class="d-none text-muted fs-8">
							<span class="spinner-border spinner-border-sm me-1 align-middle"></span>Uploading…
						</span>
					</div>
					<!--end::Status bar-->
					<textarea class="form-control form-control-flush mb-3" rows="1"
					          data-kt-element="input"
					          placeholder="Type a message"></textarea>
					<div class="d-flex flex-stack">
						<div class="d-flex align-items-center me-2">
							<button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button"
							        data-kt-element="attach"
							        title="Attach file (PDF, Word, Excel, PowerPoint, ZIP, TXT — max 10 MB)">
								<i class="ki-duotone ki-paper-clip fs-3"><span class="path1"></span><span class="path2"></span></i>
							</button>
							<input type="file" data-kt-element="file-input" class="d-none"
							       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip">
							<button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button"
							        data-kt-element="photo-attach"
							        title="Send photos (select up to 10 images)">
								<i class="ki-duotone ki-picture fs-3"><span class="path1"></span><span class="path2"></span></i>
							</button>
							<input type="file" data-kt-element="photo-input" class="d-none"
							       accept=".jpg,.jpeg,.png,.gif,.webp" multiple>
							<button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button"
							        data-kt-element="emoji"
							        title="Emoji">😊</button>
						</div>
						<button class="btn btn-primary" type="button" data-kt-element="send">Send</button>
					</div>
				</div>
				<!--end::Card footer-->
			</div>
			<!--end::Messenger-->
		</div>
		<!--end::Chat drawer-->

		<!--begin::Call overlay (voice + video)-->
		<div id="navuli_call_overlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;z-index:10500;background:rgba(0,0,0,.72);align-items:center;justify-content:center;">

			<!--begin::Voice card (shown for voice calls and ringing state)-->
			<div id="navuli_call_card" class="card shadow-xl text-center" style="width:300px;border-radius:20px;overflow:hidden;position:relative;z-index:1;">
				<div class="card-body py-10 px-8">
					<div id="navuli_call_avatar" class="mx-auto mb-5" style="width:88px;height:88px;border-radius:50%;overflow:hidden;"></div>
					<h5 class="fw-bold mb-1 fs-4" id="navuli_call_name"></h5>
					<p class="text-muted fs-7 mb-0" id="navuli_call_status">Calling…</p>
					<div class="d-none mt-2" id="navuli_call_timer">
						<span class="fw-semibold text-success fs-5" id="navuli_call_time">0:00</span>
					</div>
				</div>
				<div class="card-footer bg-transparent border-top-0 pb-8 d-flex justify-content-center gap-5" id="navuli_call_controls"></div>
			</div>
			<!--end::Voice card-->

			<!--begin::Video view (shown for active video calls)-->
			<div id="navuli_video_view" style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;background:#111;overflow:hidden;">
				<!--Remote video fills screen-->
				<video id="navuli_remote_video" autoplay playsinline style="width:100%;height:100%;object-fit:cover;background:#000;"></video>
				<!--Local video PiP-->
				<video id="navuli_local_video" autoplay playsinline muted style="position:absolute;bottom:108px;right:16px;width:110px;height:165px;object-fit:cover;border-radius:14px;border:2px solid rgba(255,255,255,.35);cursor:pointer;background:#222;"></video>
				<!--Top info bar-->
				<div style="position:absolute;top:0;left:0;right:0;padding:22px 20px 48px;background:linear-gradient(rgba(0,0,0,.55),transparent);pointer-events:none;">
					<div class="text-white fw-bold fs-5" id="navuli_video_name"></div>
					<div style="color:rgba(255,255,255,.65);font-size:.78rem;margin-top:2px;" id="navuli_video_status">Connecting…</div>
					<div id="navuli_video_timer" class="d-none" style="color:#50cd89;font-size:.82rem;font-weight:600;margin-top:4px;">
						<span id="navuli_video_time">0:00</span>
					</div>
				</div>
				<!--Bottom controls-->
				<div id="navuli_video_controls" style="position:absolute;bottom:0;left:0;right:0;padding:18px 0 36px;display:flex;align-items:center;justify-content:center;gap:20px;background:linear-gradient(transparent,rgba(0,0,0,.6));"></div>
			</div>
			<!--end::Video view-->
		</div>
		<audio id="navuli_call_local_audio" autoplay muted></audio>
		<audio id="navuli_call_remote_audio" autoplay></audio>
		<!--end::Call overlay-->

		<!--begin::Incoming call notification-->
		<div id="navuli_incoming_call" class="d-none position-fixed card shadow-lg" style="z-index:10499;bottom:90px;right:16px;width:272px;border-radius:16px;">
			<div class="card-body p-4">
				<div class="d-flex align-items-center gap-3 mb-3">
					<div id="navuli_incoming_avatar" style="flex-shrink:0;width:44px;height:44px;border-radius:50%;overflow:hidden;"></div>
					<div class="min-w-0">
						<div class="fw-bold fs-7 text-truncate" id="navuli_incoming_name">Unknown</div>
						<div class="text-muted fs-9" id="navuli_incoming_type">Incoming voice call…</div>
					</div>
				</div>
				<div class="d-flex gap-2 justify-content-end">
					<button type="button" class="btn btn-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:44px;height:44px;" id="navuli_decline_btn" title="Decline">
						<i class="fa-solid fa-phone-slash fs-6"></i>
					</button>
					<button type="button" class="btn btn-success rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:44px;height:44px;" id="navuli_accept_btn" title="Accept">
						<i class="fa-solid fa-phone fs-6"></i>
					</button>
				</div>
			</div>
		</div>
		<!--end::Incoming call notification-->

		<!--end::Drawers-->

		<!--begin::Lightbox-->
		<div id="navuli_lightbox" role="dialog" aria-modal="true" aria-label="Photo viewer">
			<button id="lb_close" class="lb-btn" title="Close (Esc)">
				<i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
			</button>
			<button id="lb_prev" class="lb-btn" title="Previous (←)">
				<i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
			</button>
			<div id="lb_img_wrap">
				<img id="lb_img" src="" alt="">
			</div>
			<button id="lb_next" class="lb-btn" title="Next (→)">
				<i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span class="path2"></span></i>
			</button>
			<div id="lb_footer">
				<span id="lb_counter">1 / 1</span>
				<span id="lb_name"></span>
			</div>
		</div>
		<!--end::Lightbox-->

		<!--begin::Chat message context menu-->
		<div id="chat_del_menu" class="d-none">
			<div class="cdm-item" id="cdm_copy">
				<span class="cdm-icon">📋</span>Copy text
			</div>
			<div class="cdm-item" id="cdm_share">
				<span class="cdm-icon">↗</span>Share message
			</div>
			<div class="cdm-sep"></div>
			<div class="cdm-item" id="cdm_del_me">
				<span class="cdm-icon">🗑</span>Remove for me
			</div>
			<div class="cdm-sep" id="cdm_everyone_sep"></div>
			<div class="cdm-item danger" id="cdm_del_everyone">
				<span class="cdm-icon">🚫</span>Remove for everyone
			</div>
		</div>
		<!--end::Chat message context menu-->

		<!--begin::Reaction quick-bar-->
		<div id="chat_reaction_bar" class="d-none">
			<span class="crb-emoji" data-emoji="👍">👍</span>
			<span class="crb-emoji" data-emoji="❤️">❤️</span>
			<span class="crb-emoji" data-emoji="😂">😂</span>
			<span class="crb-emoji" data-emoji="😮">😮</span>
			<span class="crb-emoji" data-emoji="😢">😢</span>
			<span class="crb-emoji" data-emoji="🙏">🙏</span>
			<span class="crb-emoji crb-more" id="chat_reaction_more" title="More emoji">➕</span>
		</div>
		<!--end::Reaction quick-bar-->

		<!--begin::Emoji palette popup-->
		<div id="chat_emoji_popup" class="d-none"></div>
		<!--end::Emoji palette popup-->

		<!--begin::Chat bottom dock-->
		<div id="chat_bottom_dock">
			<div id="chat_minimized_bar"></div>
			<button type="button" id="chat_compose_btn" title="New Message">
				<i class="ki-duotone ki-message-edit fs-2 text-gray-700">
					<span class="path1"></span><span class="path2"></span>
				</i>
			</button>
		</div>
		<!--end::Chat bottom dock-->

		<!--begin::User list popup-->
		<div id="ucl_popup" style="display:none;">
			<div class="ucl-popup-header">
				<span class="fw-bold text-gray-800 fs-6">New Message</span>
				<button type="button" id="ucl_popup_close" class="btn btn-xs btn-icon btn-active-light-danger" style="width:24px;height:24px;">
					<i class="ki-duotone ki-cross fs-6"><span class="path1"></span><span class="path2"></span></i>
				</button>
			</div>
			<div class="ucl-popup-search">
				<i class="ki-duotone ki-magnifier fs-5 text-gray-400 position-absolute top-50 translate-middle-y ms-3"><span class="path1"></span><span class="path2"></span></i>
				<input type="text" id="ucl_popup_search" class="form-control form-control-sm ps-10 border-0 bg-light" placeholder="Search users..." autocomplete="off">
			</div>
			<div id="ucl_popup_list">
				<div id="ucl_popup_loading" class="text-center py-4" style="display:none;">
					<span class="spinner-border spinner-border-sm text-primary"></span>
					<div class="text-muted fs-9 mt-1">Loading...</div>
				</div>
				<div id="ucl_popup_empty" class="text-center py-4 text-muted fs-8" style="display:none;">No users found.</div>
				<div id="ucl_popup_sentinel" style="height:1px;"></div>
			</div>
		</div>
		<!--end::User list popup-->

		<style>
		#ucl_popup {
			position: fixed;
			bottom: 99px; /* 33px dock + 52px button + 14px gap */
			right: 70px;
			width: 290px;
			max-height: 380px;
			background: #fff;
			border-radius: 14px;
			box-shadow: 0 8px 32px rgba(0,0,0,.22);
			z-index: 9500;
			display: flex;
			flex-direction: column;
			overflow: hidden;
		}
		.ucl-popup-header {
			display: flex; align-items: center; justify-content: space-between;
			padding: 12px 14px 10px;
			border-bottom: 1px solid #f1f1f4;
			flex-shrink: 0;
		}
		.ucl-popup-search {
			position: relative; padding: 8px 10px;
			border-bottom: 1px solid #f1f1f4; flex-shrink: 0;
		}
		#ucl_popup_list {
			overflow-y: auto; flex: 1;
		}
		.ucl-popup-row {
			display: flex; align-items: center; gap: 12px;
			padding: 8px 14px; cursor: pointer;
			transition: background .12s;
		}
		.ucl-popup-row:hover { background: var(--bs-gray-100); }
		</style>

		<script>
		(function () {
			'use strict';

			const PHOTO_BASE = '<?= base_url('uploads/profilePhoto/') ?>';
			const LIST_URL   = '<?= base_url('user/chatUserList') ?>';

			let pPage = 1, pLoading = false, pHasMore = true, pTimer = null;

			const popup      = document.getElementById('ucl_popup');
			const listEl     = document.getElementById('ucl_popup_list');
			const loadingEl  = document.getElementById('ucl_popup_loading');
			const emptyEl    = document.getElementById('ucl_popup_empty');
			const sentinelEl = document.getElementById('ucl_popup_sentinel');
			const searchEl   = document.getElementById('ucl_popup_search');

			function esc(s) { return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }

			function buildPopupRow(u) {
				const initials = ((u.fname||'').charAt(0)+(u.lname||'').charAt(0)).toUpperCase();
				const avatar   = u.profile_photo
					? `<img src="${PHOTO_BASE}${u.profile_photo}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;flex-shrink:0;" alt="">`
					: `<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary bg-light-primary fs-7" style="width:40px;height:40px;flex-shrink:0;">${initials}</div>`;
				const dot = `<span style="position:absolute;bottom:1px;right:1px;width:10px;height:10px;border-radius:50%;background:${u.online_status==='Online'?'#50cd89':'#a1a5b7'};border:2px solid #fff;"></span>`;

				const row = document.createElement('div');
				row.className = 'ucl-popup-row';
				row.dataset.userId    = u.user_id;
				row.dataset.userName  = `${u.fname} ${u.lname}`;
				row.dataset.userPhoto = u.profile_photo ? `${PHOTO_BASE}${u.profile_photo}` : '';
				row.innerHTML = `
					<div style="position:relative;flex-shrink:0;">${avatar}${dot}</div>
					<div style="min-width:0;flex:1;">
						<div class="fw-semibold text-gray-800 fs-7 text-truncate">${esc(u.fname)} ${esc(u.lname)}</div>
						<div class="fs-9 ${u.online_status==='Online'?'text-success':'text-muted'}">${esc(u.online_status)}</div>
					</div>`;
				row.addEventListener('click', () => {
					closePopup();
					if (window.openChat) window.openChat(u, row);
				});
				return row;
			}

			function fetchPopupPage() {
				if (pLoading || !pHasMore) return;
				pLoading = true;
				loadingEl.style.display = 'block';
				emptyEl.style.display   = 'none';

				const url = `${LIST_URL}?page=${pPage}&search=${encodeURIComponent(searchEl.value.trim())}`;
				fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
					.then(r => r.json())
					.then(data => {
						if (!data.success) return;
						data.users.forEach(u => listEl.insertBefore(buildPopupRow(u), loadingEl));
						pHasMore = data.hasMore;
						pPage    = data.nextPage;
						if (!data.hasMore && !listEl.querySelector('.ucl-popup-row')) emptyEl.style.display = '';
					})
					.catch(() => {})
					.finally(() => { loadingEl.style.display = 'none'; pLoading = false; });
			}

			function openPopup() {
				popup.style.display = 'flex';
				searchEl.value = '';
				listEl.querySelectorAll('.ucl-popup-row').forEach(el => el.remove());
				pPage = 1; pHasMore = true;
				fetchPopupPage();
				setTimeout(() => searchEl.focus(), 50);
			}

			function closePopup() {
				popup.style.display = 'none';
			}

			function togglePopup() {
				popup.style.display === 'none' ? openPopup() : closePopup();
			}

			// Infinite scroll inside popup list
			const obs = new IntersectionObserver(entries => {
				if (entries[0].isIntersecting) fetchPopupPage();
			}, { root: listEl, rootMargin: '0px', threshold: 0 });
			obs.observe(sentinelEl);

			// Debounced search
			searchEl.addEventListener('input', () => {
				clearTimeout(pTimer);
				pTimer = setTimeout(() => {
					listEl.querySelectorAll('.ucl-popup-row').forEach(el => el.remove());
					pPage = 1; pHasMore = true; fetchPopupPage();
				}, 320);
			});

			// Close button
			document.getElementById('ucl_popup_close')?.addEventListener('click', closePopup);

			// Close on outside click
			document.addEventListener('click', e => {
				if (popup.style.display !== 'none' &&
					!popup.contains(e.target) &&
					!document.getElementById('chat_compose_btn')?.contains(e.target)) {
					closePopup();
				}
			});

			// Expose toggle so chat.js compose button can call it
			window.toggleUclPopup = togglePopup;
		}());
		</script>

		<style>
		/* ── Drawers above the dock ── */
		#kt_drawer_chat  { z-index: 9300 !important; }
		#kt_activities   { z-index: 9600 !important; }
		/* ── Bottom dock wrapper ── */
		#chat_bottom_dock {
			position: fixed; bottom: 33px; right: 70px;
			display: flex; flex-direction: row; align-items: flex-end; gap: 8px;
			z-index: 9100;
		}
		/* ── Compose button ── */
		#chat_compose_btn {
			width: 52px; height: 52px; border-radius: 50%;
			background: #fff; color: #3f4254; border: none; cursor: pointer;
			display: flex; align-items: center; justify-content: center;
			box-shadow: 0 2px 10px rgba(0,0,0,.2);
			transition: transform .15s, box-shadow .15s;
			flex-shrink: 0;
		}
		#chat_compose_btn:hover { transform: scale(1.08); box-shadow: 0 4px 16px rgba(0,0,0,.28); }
		/* ── Minimized bar ── */
		#chat_minimized_bar {
			display: flex; flex-direction: row; align-items: flex-end; gap: 8px;
		}
		.chat-mini-item {
			position: relative; cursor: pointer; flex-shrink: 0;
		}
		.chat-mini-avatar {
			width: 52px; height: 52px; border-radius: 50%;
			border: 3px solid #fff;
			box-shadow: 0 2px 10px rgba(0,0,0,.25);
			display: block; object-fit: cover;
			transition: transform .15s;
		}
		.chat-mini-avatar-wrap { position: relative; width: 52px; height: 52px; }
		.chat-mini-item:hover .chat-mini-avatar { transform: scale(1.06); }
		.chat-mini-online-dot {
			position: absolute; bottom: 2px; right: 2px;
			width: 12px; height: 12px; border-radius: 50%;
			border: 2px solid #fff; background: #a1a5b7;
		}
		.chat-mini-close {
			position: absolute; top: -4px; right: -4px;
			width: 20px; height: 20px; border-radius: 50%;
			background: #e4e6ef; border: none; cursor: pointer;
			display: none; align-items: center; justify-content: center;
			font-size: .65rem; color: #5e6278; line-height: 1;
			box-shadow: 0 1px 4px rgba(0,0,0,.2);
			transition: background .1s;
		}
		.chat-mini-close:hover { background: #f1416c; color: #fff; }
		.chat-mini-item:hover .chat-mini-close { display: flex; }
		.chat-mini-label {
			position: absolute; bottom: 58px; left: 50%; transform: translateX(-50%);
			background: rgba(0,0,0,.75); color: #fff; white-space: nowrap;
			padding: 3px 8px; border-radius: 4px; font-size: .72rem;
			pointer-events: none; display: none;
		}
		.chat-mini-item:hover .chat-mini-label { display: block; }
		/* ── Three-dots bar-options button ── */
		.chat-mini-opts {
			opacity: 0; transition: opacity .2s; pointer-events: none;
		}
		#chat_minimized_bar:hover .chat-mini-opts {
			opacity: 1; pointer-events: all;
		}
		.chat-mini-opts .chat-mini-avatar {
			background: #e4e6ef; border-color: #d5d8e0;
			box-shadow: 0 1px 6px rgba(0,0,0,.15);
		}
		.chat-mini-opts:hover .chat-mini-avatar {
			background: #d5d8e0;
		}
		/* ── Overflow popup & bar-options menu ── */
		#chat_mini_overflow_popup, #chat_mini_bar_options_menu {
			position: fixed; background: #fff;
			border-radius: 12px; box-shadow: 0 6px 28px rgba(0,0,0,.18);
			min-width: 200px; overflow: hidden; padding: 6px 0; z-index: 9200;
		}
		.chat-mini-overflow-row:hover, .chat-mini-bar-opt:hover { background: #f9f9f9; }
		.chat-mini-bar-opt {
			display: flex; align-items: center; gap: 10px;
			padding: 10px 16px; cursor: pointer;
			font-size: .84rem; font-weight: 600; color: #3f4254;
		}
		.chat-mini-bar-opt.danger { color: #f1416c; }
		</style>

		<!--begin::Share message modal-->
		<div class="modal fade" id="chat_share_modal" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" style="max-width:360px;">
				<div class="modal-content">
					<div class="modal-header border-0 pb-2 pt-4 px-5">
						<h6 class="modal-title fw-bold text-gray-800 fs-6 mb-0">
							<i class="ki-duotone ki-send fs-4 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
							Share Message
						</h6>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body pt-2 pb-3 px-5">
						<div id="csm_preview" class="bg-light rounded-2 px-3 py-2 mb-4 fs-8 text-gray-600 text-break"
						     style="max-height:54px;overflow:hidden;border-left:3px solid #009ef7;"></div>
						<div class="position-relative mb-3">
							<i class="ki-duotone ki-magnifier fs-5 text-gray-400 position-absolute top-50 translate-middle-y ms-3">
								<span class="path1"></span><span class="path2"></span>
							</i>
							<input type="text" id="csm_search" class="form-control form-control-sm ps-10"
							       placeholder="Search users..." autocomplete="off">
						</div>
						<div id="csm_list" style="max-height:300px;overflow-y:auto;">
							<div id="csm_loading" class="text-center py-4" style="display:none;">
								<span class="spinner-border spinner-border-sm text-primary"></span>
								<div class="text-muted fs-9 mt-1">Loading...</div>
							</div>
							<div id="csm_empty" class="text-center py-4 text-muted fs-8" style="display:none;">No users found.</div>
						</div>
					</div>
					<div class="modal-footer border-0 pt-0 pb-4 px-5">
						<button type="button" class="btn btn-sm btn-light px-5" data-bs-dismiss="modal">Done</button>
					</div>
				</div>
			</div>
		</div>
		<!--end::Share message modal-->

		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Modals-->
		
		<!--end::Modals-->
		
		<!-- ✅ ADD LEAFLET JS HERE (before closing body tag) -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <!--end::Leaflet JS-->
		
    <script>
    // Restore sidebar if a subject detail page collapsed it
    (function () {
        if (sessionStorage.getItem('navuli_restore_sidebar') === '1') {
            sessionStorage.removeItem('navuli_restore_sidebar');
            document.body.removeAttribute('data-kt-app-sidebar-minimize');
            window.dispatchEvent(new Event('resize'));
        }
    })();
    </script>

	</body>
	<!--end::Body-->
</html>

<script>
    $('#add_optional_subject_modal').on('show.bs.modal', function(e) {
        var modal = $(this);
		//get data-id attribute of the clicked element
		var id = $(e.relatedTarget).data('stream-id');
		
		// Split the ID into two parts using hyphen as separator
        var parts = id.split('-');
        
        // Store in separate variables
        var stream = parts[0];
        var level = parts[1];
        
        // ✅ FIX: Use object format for data
        var dataObject = {
            level: level,
            stream: stream
        };
        
        // Debug: Check the values
        console.log('Stream:', stream);
        console.log('Level:', level);
        console.log('Data Object:', dataObject);

		//populate the textbox
		modal.find('input[name="streamID"]').val(stream);
		modal.find('input[name="levelID"]').val(level);
		
		$.ajax({
			url: "<?php echo base_url(); ?>school/get_level_subject",
			type: 'POST',
			data: dataObject, // ✅ Use object instead of string
			beforeSend: function(){
				// Show image container
				modal.find("#loader2").show();
				modal.find(".response2").hide(); // ✅ Fixed selector (class instead of ID)
				modal.find("#alert-optional").hide();
			},
			success: function(response){
				modal.find('.response2').empty();
				modal.find("#alert-optional").show();// ✅ Fixed selector
                modal.find(".response2").show(); // ✅ Fixed selector
                modal.find('.response2').html(response); // ✅ Use html() instead of append()
			},
			complete:function(data){
				// Hide image container
				modal.find("#loader2").hide();
			},
            error: function(xhr, status, error) { // ✅ Add error handling
                console.error('AJAX Error:', error);
                modal.find('.response2').html('<div class="alert alert-danger">Error loading data</div>');
            }
		});
		
		// Remove any existing event handlers to prevent duplicates
        modal.find('form').off('submit');
        modal.off('change', 'input[name="subjects[]"]');
		
		// Form submission validation
        modal.find('form').on('submit', function(e) {
            // Check if at least two subject checkboxes are checked
            var atLeastTwoChecked = modal.find('input[name="subjects[]"]:checked').length > 1;
            
            if (!atLeastTwoChecked) {
                // Prevent form submission
                e.preventDefault();
                
                // Show error message
                showCheckboxError(modal);
                
                // Scroll to error
                modal.find('.modal-body').animate({
                    scrollTop: 0
                }, 500);
            }
        });
        
        // Real-time validation on checkbox change
        modal.on('change', 'input[name="subjects[]"]', function() {
            var checkedCount = modal.find('input[name="subjects[]"]:checked').length;
            
            if (checkedCount > 1) {
                // Condition met - remove error
                removeCheckboxError(modal);
            } else {
                // Condition not met - show error
                showCheckboxError(modal);
            }
        });
        
        function showCheckboxError(currentModal) {
            // Check if error already exists to avoid duplicates
            if (currentModal.find('#checkboxErrorOptional').length === 0) {
                // Add error message
                currentModal.find('.response2').prepend(
                    '<div class="alert alert-danger d-flex align-items-center mb-4" id="checkboxErrorOptional">' +
                    '<i class="ki-duotone ki-information fs-2 text-danger me-2">' +
                    '<span class="path1"></span><span class="path2"></span>' +
                    '</i>' +
                    '<div class="d-flex flex-column">' +
                    '<span class="fw-bold">Validation Error!</span>' +
                    '<span>Please select at least two subjects.</span>' +
                    '</div>' +
                    '</div>'
                );
                
                // Add red border to checkbox container
                currentModal.find('.response2 .row')
                    .addClass('border border-danger rounded p-3 mx-auto')
                    .css('width', 'calc(100% - 5px)');
            }
        }
        
        function removeCheckboxError(currentModal) {
            currentModal.find('#checkboxErrorOptional').remove();
            currentModal.find('.response2 .row').removeClass('border border-danger rounded p-3 mx-auto');
        }
	});

    $('#add_core_subject_modal').on('show.bs.modal', function(e) {
        var modal = $(this);
		//get data-id attribute of the clicked element
		var id = $(e.relatedTarget).data('stream-id');
		
		// Split the ID into two parts using hyphen as separator
        var parts = id.split('-');
        
        // Store in separate variables
        var stream = parts[0];
        var level = parts[1];
        
        // ✅ FIX: Use object format for data
        var dataObject = {
            level: level,
            stream: stream
        };

		//populate the textbox
		modal.find('input[name="streamID"]').val(stream);
		modal.find('input[name="levelID"]').val(level);
		
		$.ajax({
			url: "<?php echo base_url(); ?>school/get_level_subject",
			type: 'POST',
			data: dataObject, // ✅ Use object instead of string
			beforeSend: function(){
				// Show image container
				modal.find("#loader").show();
				modal.find(".response").hide(); // ✅ Fixed selector (class instead of ID)
				modal.find("#alert-core").hide();
			},
			success: function(response){
				modal.find('.response').empty(); // ✅ Fixed selectorFixed selector
                modal.find("#alert-core").show();
                modal.find(".response").show(); // ✅ 
                modal.find('.response').html(response); // ✅ Use html() instead of append()
			},
			complete:function(data){
				// Hide image container
				modal.find("#loader").hide();
			},
            error: function(xhr, status, error) { // ✅ Add error handling
                console.error('AJAX Error:', error);
                modal.find('.response').html('<div class="alert alert-danger">Error loading data</div>');
            }
		});
		
		// Remove any existing event handlers to prevent duplicates
        modal.find('form').off('submit');
        modal.off('change', 'input[name="subjects[]"]');
		
		// Form submission validation
        modal.find('form').on('submit', function(e) {
            // Check if at least one subject checkbox is checked
            var atLeastOneChecked = modal.find('input[name="subjects[]"]:checked').length > 0;
            
            if (!atLeastOneChecked) {
                // Prevent form submission
                e.preventDefault();
                
                // Show error message
                showCheckboxErrorCore(modal);
                
                // Scroll to error
                modal.find('.modal-body').animate({
                    scrollTop: 0
                }, 500);
            }
        });
        
        // Real-time validation on checkbox change
        modal.on('change', 'input[name="subjects[]"]', function() {
            var checkedCount = modal.find('input[name="subjects[]"]:checked').length;
            
            if (checkedCount > 0) {
                // Condition met - remove error
                removeCheckboxErrorCore(modal);
            } else {
                // Condition not met - show error
                showCheckboxErrorCore(modal);
            }
        });
        
        function showCheckboxErrorCore(currentModal) {
            // Check if error already exists to avoid duplicates
            if (currentModal.find('#checkboxErrorCore').length === 0) {
                // Add error message
                currentModal.find('.response').prepend(
                    '<div class="alert alert-danger d-flex align-items-center mb-4" id="checkboxErrorCore">' +
                    '<i class="ki-duotone ki-information fs-2 text-danger me-2">' +
                    '<span class="path1"></span><span class="path2"></span>' +
                    '</i>' +
                    '<div class="d-flex flex-column">' +
                    '<span class="fw-bold">Validation Error!</span>' +
                    '<span>Please select at least one subject.</span>' +
                    '</div>' +
                    '</div>'
                );
                
                // Add red border to checkbox container
                currentModal.find('.response .row')
                    .addClass('border border-danger rounded p-3 mx-auto')
                    .css('width', 'calc(100% - 5px)');
            }
        }
        
        function removeCheckboxErrorCore(currentModal) {
            currentModal.find('#checkboxErrorCore').remove();
            currentModal.find('.response .row').removeClass('border border-danger rounded p-3 mx-auto');
        }
	});
	
	$('#deleteCoreModal').on('show.bs.modal', function(e) {
        var modal = $(this);
		//get data-id attribute of the clicked element
		var id = $(e.relatedTarget).data('sub-id');

		//populate the textbox
		modal.find('input[name="coreSubID"]').val(id);
		
	});
	
	
	$('#deleteOptionalModal').on('show.bs.modal', function(e) {
        var modal = $(this);
		//get data-id attribute of the clicked element
		var id = $(e.relatedTarget).data('sub-id');

		//populate the textbox
		modal.find('input[name="optID"]').val(id);
		
	});
</script>

