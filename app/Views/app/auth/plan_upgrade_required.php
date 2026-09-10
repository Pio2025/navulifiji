
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Feature Locked</h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">
					<a href="<?php echo base_url(); ?>/dashboard" class="text-muted text-hover-primary">Home</a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">Plan Upgrade Required</li>
				<!--end::Item-->
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
	</div>
	<!--end::Toolbar container-->
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<!--begin::Content container-->
	<div id="kt_app_content_container" class="app-container container-xxl">

	    <?= $this->include('templates/flash_messages') ?>

		<!--begin::Basic info-->
		<div class="card mb-5 mb-xl-10">
			<div class="card-body p-9">
				<div class="row">
					<div class="col-md-12">
						<!--begin::Alert-->
						<div class="alert alert-warning d-flex align-items-center p-5">
							<!--begin::Icon-->
							<span class="svg-icon svg-icon-2hx svg-icon-warning me-4">
								<i class="ki-duotone ki-crown fs-2hx text-warning">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</span>
							<!--end::Icon-->

							<!--begin::Wrapper-->
							<div class="d-flex flex-column">
								<!--begin::Title-->
								<h4 class="mb-1 text-warning">Feature Locked</h4>
								<!--end::Title-->

								<!--begin::Content-->
								<span><?= esc($upgradeMessage ?? 'This feature is not included in your current plan.') ?></span>
								<!--end::Content-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Alert-->
					</div>
				</div>
			</div>
		</div>
		<!--end::Basic info-->
	</div>
	<!--end::Content container-->
</div>
<!--end::Content-->
