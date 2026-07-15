<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>404 – Page Not Found | Navuli</title>
    <link rel="shortcut icon" href="<?= base_url() ?>app/assets/media/logos/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="<?= base_url() ?>app/assets/plugins/global/plugins.bundle.css" rel="stylesheet" />
    <link href="<?= base_url() ?>app/assets/css/style.bundle.css" rel="stylesheet" />
</head>
<body id="kt_body" class="app-blank">
<div class="d-flex flex-column flex-root" style="min-height:100vh;">
    <div class="d-flex flex-column flex-center flex-column-fluid text-center p-10">

        <!--begin::Logo-->
        <a href="<?= base_url() ?>" class="mb-10">
            <img src="<?= base_url() ?>app/assets/media/logos/logo-default.svg"
                 alt="Navuli" style="height:40px;" onerror="this.style.display='none'">
        </a>
        <!--end::Logo-->

        <!--begin::404 number-->
        <div style="font-size:8rem;font-weight:900;line-height:1;color:#e4e6ea;letter-spacing:-4px;user-select:none;">404</div>
        <!--end::404 number-->

        <!--begin::Icon-->
        <div class="symbol symbol-75px mb-5 mt-3">
            <div class="symbol-label bg-light-warning">
                <i class="ki-duotone ki-information-5 fs-2x text-warning">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                </i>
            </div>
        </div>
        <!--end::Icon-->

        <!--begin::Message-->
        <h1 class="fw-bold text-gray-800 mb-4" style="font-size:2rem;">Page Not Found</h1>
        <p class="text-muted fs-6 mb-8" style="max-width:400px;margin:0 auto 2rem;">
            Sorry, the page you're looking for doesn't exist or may have been moved.
        </p>
        <!--end::Message-->

        <!--begin::Actions-->
        <div class="d-flex gap-3 justify-content-center">
            <a href="javascript:history.back()" class="btn btn-light">
                <i class="ki-duotone ki-arrow-left fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Go Back
            </a>
            <a href="<?= base_url('auth/login') ?>" class="btn btn-primary">
                <i class="ki-duotone ki-entrance-right fs-3 me-1">
                    <span class="path1"></span><span class="path2"></span>
                </i>
                Go to Login
            </a>
        </div>
        <!--end::Actions-->

        <div class="text-muted fs-8 mt-12">
            &copy; <?= date('Y') ?> Navuli – School Management Information System
        </div>

    </div>
</div>
</body>
</html>
