
<!-- Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- Or add custom CSS -->
<style>
.bg-light-danger {
    background-color: rgba(255, 0, 0, 0.05) !important;
}
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<!--begin::Page title-->
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<!--begin::Title-->
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Dashboard</h1>
			<!--end::Title-->
			<!--begin::Breadcrumb-->
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">
					<a href="<?php echo base_url(); ?>dashboard" class="text-muted text-hover-primary">Home</a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="breadcrumb-item text-muted">Dasboard</li>
				<!--end::Item-->
			</ul>
			<!--end::Breadcrumb-->
		</div>
		<!--end::Page title-->
		<!--begin::Actions-->
		<div class="d-flex align-items-center gap-2 gap-lg-3">
			<!--begin::Filter menu-->
			<div class="m-0">
				<!--begin::Menu toggle-->
				<a href="#" class="btn btn-sm btn-flex btn-secondary fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
				<i class="ki-duotone ki-filter fs-6 text-muted me-1">
					<span class="path1"></span>
					<span class="path2"></span>
				</i>Filter</a>
				<!--end::Menu toggle-->
				<!--begin::Menu 1-->
				<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_6606384fa0181">
					<!--begin::Header-->
					<div class="px-7 py-5">
						<div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
					</div>
					<!--end::Header-->
					<!--begin::Menu separator-->
					<div class="separator border-gray-200"></div>
					<!--end::Menu separator-->
					<!--begin::Form-->
					<div class="px-7 py-5">
						<!--begin::Input group-->
						<div class="mb-10">
							<!--begin::Label-->
							<label class="form-label fw-semibold">Status:</label>
							<!--end::Label-->
							<!--begin::Input-->
							<div>
								<select class="form-select form-select-solid" multiple="multiple" data-kt-select2="true" data-close-on-select="false" data-placeholder="Select option" data-dropdown-parent="#kt_menu_6606384fa0181" data-allow-clear="true">
									<option></option>
									<option value="1">Approved</option>
									<option value="2">Pending</option>
									<option value="2">In Process</option>
									<option value="2">Rejected</option>
								</select>
							</div>
							<!--end::Input-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="mb-10">
							<!--begin::Label-->
							<label class="form-label fw-semibold">Member Type:</label>
							<!--end::Label-->
							<!--begin::Options-->
							<div class="d-flex">
								<!--begin::Options-->
								<label class="form-check form-check-sm form-check-custom form-check-solid me-5">
									<input class="form-check-input" type="checkbox" value="1" />
									<span class="form-check-label">Author</span>
								</label>
								<!--end::Options-->
								<!--begin::Options-->
								<label class="form-check form-check-sm form-check-custom form-check-solid">
									<input class="form-check-input" type="checkbox" value="2" checked="checked" />
									<span class="form-check-label">Customer</span>
								</label>
								<!--end::Options-->
							</div>
							<!--end::Options-->
						</div>
						<!--end::Input group-->
						<!--begin::Input group-->
						<div class="mb-10">
							<!--begin::Label-->
							<label class="form-label fw-semibold">Notifications:</label>
							<!--end::Label-->
							<!--begin::Switch-->
							<div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
								<input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked" />
								<label class="form-check-label">Enabled</label>
							</div>
							<!--end::Switch-->
						</div>
						<!--end::Input group-->
						<!--begin::Actions-->
						<div class="d-flex justify-content-end">
							<button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
							<button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
						</div>
						<!--end::Actions-->
					</div>
					<!--end::Form-->
				</div>
				<!--end::Menu 1-->
			</div>
			<!--end::Filter menu-->
			<!--begin::Secondary button-->
			<!--end::Secondary button-->
			<!--begin::Primary button-->
			<a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Create</a>
			<!--end::Primary button-->
		</div>
		<!--end::Actions-->
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
			<!--begin::Card header-->
			<div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
				<!--begin::Card title-->
				<div class="card-title m-0">
					<h3 class="fw-bold m-0">School Account Dashboard</h3>
				</div>
				<!--end::Card title-->
			</div>
			<!--begin::Card header-->
			<!--begin::Content-->
			<div id="kt_account_settings_profile_details" class="collapse show">
				
			</div>
			<!--end::Content-->
		</div>
		<!--end::Basic info-->
	</div>
	<!--end::Content container-->
</div>
<!--end::Content-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    

</script>
