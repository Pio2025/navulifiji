<!--begin::Navbar-->
<div class="card mb-5 mb-xxl-8">
	<div class="card-body pt-9 pb-0">
		<!--begin::Details-->
		<div class="d-flex flex-wrap flex-sm-nowrap">
			<!--begin: Pic-->
			<div class="me-7 mb-4">
				<div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
					<img src="<?php echo base_url(); ?>uploads/school/logo/<?php echo $schLogo; ?>" alt="<?php echo $schLogo; ?>" />
					<div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
				</div>
			</div>
			<!--end::Pic-->
			<!--begin::Info-->
			<div class="flex-grow-1">
				<!--begin::Title-->
				<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
					<!--begin::User-->
					<div class="d-flex flex-column">
						<!--begin::Name-->
						<div class="d-flex align-items-center mb-2">
							<a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1"><?php echo $schName; ?></a>
							<a href="#">
								<i class="ki-duotone ki-verify fs-1 text-success">
									<span class="path1"></span>
									<span class="path2"></span>
								</i>
							</a>
						</div>
						<!--end::Name-->
						<!--begin::Info-->
						<div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
							<a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
							<i class="ki-duotone ki-profile-circle fs-4 me-1">
								<span class="path1"></span>
								<span class="path2"></span>
								<span class="path3"></span>
							</i><?php echo $schCat; ?></a>
							<a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
							<i class="ki-duotone ki-geolocation fs-4 me-1">
								<span class="path1"></span>
								<span class="path2"></span>
							</i><?php echo $schAddress; ?></a>
							<a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
							<i class="ki-duotone ki-sms fs-4">
								<span class="path1"></span>
								<span class="path2"></span>
							</i><?php echo $schEmail; ?></a>
						</div>
						<!--end::Info-->
					</div>
					<!--end::User-->
				
				</div>
				<!--end::Title-->
				<!--begin::Stats-->
				<div class="d-flex flex-wrap flex-stack">
					<!--begin::Wrapper-->
					<div class="d-flex flex-column flex-grow-1 pe-8">
						<!--begin::Stats-->
						<div class="d-flex flex-wrap">
							<!--begin::Stat-->
							<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
								<!--begin::Number-->
								<div class="d-flex align-items-center">
									<i class="ki-duotone ki-arrow-up fs-3 text-success me-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									<div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="4500" data-kt-countup-prefix="">0</div>
								</div>
								<!--end::Number-->
								<!--begin::Label-->
								<div class="fw-semibold fs-6 text-gray-500">Staff</div>
								<!--end::Label-->
							</div>
							<!--end::Stat-->
							<!--begin::Stat-->
							<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
								<!--begin::Number-->
								<div class="d-flex align-items-center">
									<i class="ki-duotone ki-arrow-down fs-3 text-danger me-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									<div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="80">0</div>
								</div>
								<!--end::Number-->
								<!--begin::Label-->
								<div class="fw-semibold fs-6 text-gray-500">Student</div>
								<!--end::Label-->
							</div>
							<!--end::Stat-->
							<!--begin::Stat-->
							<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
								<!--begin::Number-->
								<div class="d-flex align-items-center">
									<i class="ki-duotone ki-arrow-up fs-3 text-success me-2">
										<span class="path1"></span>
										<span class="path2"></span>
									</i>
									<div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="60" data-kt-countup-prefix="%">0</div>
								</div>
								<!--end::Number-->
								<!--begin::Label-->
								<div class="fw-semibold fs-6 text-gray-500">Parents</div>
								<!--end::Label-->
							</div>
							<!--end::Stat-->
						</div>
						<!--end::Stats-->
					</div>
					<!--end::Wrapper-->
					<!--begin::Progress-->
					<div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
						<div class="d-flex justify-content-between w-100 mt-auto mb-2">
							<span class="fw-semibold fs-6 text-gray-500">Profile Completion</span>
							<span class="fw-bold fs-6">100%</span>
						</div>
						<div class="h-5px mx-3 w-100 bg-light mb-3">
							<div class="bg-success rounded h-5px" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<!--end::Progress-->
				</div>
				<!--end::Stats-->
			</div>
			<!--end::Info-->
		</div>
		<!--end::Details-->
		<!--begin::Navs-->
		<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
    	<!--begin::Nav item-->
    	<li class="nav-item mt-2">
    		<a class="nav-link text-active-primary ms-0 me-10 py-5 <?php if(session('active_nav') == 'overview'){echo 'active';} ?>" href="<?php echo base_url(); ?>school/overview">Overview</a>
    	</li>
    	<!--end::Nav item-->
    	<!--begin::Nav item-->
    	<li class="nav-item mt-2">
    		<a class="nav-link text-active-primary ms-0 me-10 py-5 <?php if(session('active_nav') == 'department'){echo 'active';} ?>" href="<?php echo base_url(); ?>school/department">Department</a>
    	</li>
    	<!--end::Nav item-->
    	<!--begin::Nav item-->
    	<li class="nav-item mt-2">
    		<a class="nav-link text-active-primary ms-0 me-10 py-5 <?php if(session('active_nav') == 'level'){echo 'active';} ?>" href="<?php echo base_url(); ?>school/level">Level</a>
    	</li>
    	<!--end::Nav item-->
    	<!--begin::Nav item-->
    	<!--li class="nav-item mt-2">
    		<a class="nav-link text-active-primary ms-0 me-10 py-5 <?php if(session('active_nav') == 'stream'){echo 'active';} ?>" href="<?php echo base_url(); ?>school/stream">Stream</a>
    	</li>
    	<!--end::Nav item-->
    	<!--begin::Nav item-->
    	<li class="nav-item mt-2">
    		<a class="nav-link text-active-primary ms-0 me-10 py-5 <?php if(session('active_nav') == 'subject'){echo 'active';} ?>" href="<?php echo base_url(); ?>school/subject">Subject</a>
    	</li>
    	<!--end::Nav item-->
    	<!--begin::Nav item-->
    	<li class="nav-item mt-2">
    		<a class="nav-link text-active-primary ms-0 me-10 py-5 <?php if(session('active_nav') == 'extracurricular'){echo 'active';} ?>" href="<?php echo base_url(); ?>school/extracurricular">Extra Curricular</a>
    	</li>
    	<!--end::Nav item-->
    </ul>
    <!--begin::Navs-->
	</div>
</div>
<!--end::Navbar-->






