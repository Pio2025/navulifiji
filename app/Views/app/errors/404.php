<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Page Not Found</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">404</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="d-flex flex-column align-items-center justify-content-center text-center" style="min-height:65vh;">

            <!--begin::Number-->
            <div style="font-size:9rem;font-weight:900;line-height:1;color:#e4e6ea;letter-spacing:-4px;user-select:none;">404</div>
            <!--end::Number-->

            <!--begin::Icon-->
            <div class="symbol symbol-75px mb-6 mt-2">
                <div class="symbol-label bg-light-warning">
                    <i class="ki-duotone ki-information-5 fs-2x text-warning">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>
                </div>
            </div>
            <!--end::Icon-->

            <!--begin::Message-->
            <h2 class="fw-bold text-gray-800 fs-1 mb-3">Oops! Page Not Found</h2>
            <p class="text-muted fs-6 mb-8 mw-400px">
                The page you're looking for doesn't exist or may have been moved.
                Check the URL or head back to the dashboard.
            </p>
            <!--end::Message-->

            <!--begin::Actions-->
            <div class="d-flex gap-3 flex-wrap justify-content-center">
                <a href="javascript:history.back()" class="btn btn-light">
                    <i class="ki-duotone ki-arrow-left fs-3 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Go Back
                </a>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-primary">
                    <i class="ki-duotone ki-home fs-3 me-1">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Back to Dashboard
                </a>
            </div>
            <!--end::Actions-->

        </div>

    </div>
</div>
<!--end::Content-->
