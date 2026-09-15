<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">School Management</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">School Listing</li>
			</ul>
		</div>
	</div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
	<div id="kt_app_content_container" class="app-container container-xxl">
	    
	    <?= $this->include('templates/flash_messages') ?>
	    
		<!--begin::Card-->
		<div class="card">
			<!--begin::Card header-->
			<div class="card-header border-0 pt-6">
				<div class="card-title">
					<!--begin::Search-->
					<div class="d-flex align-items-center position-relative my-1">
						<i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
							<span class="path1"></span>
							<span class="path2"></span>
						</i>
						<input type="text" id="kt_user_search" class="form-control form-control-solid w-250px ps-13" placeholder="Search school..." />
					</div>
					<!--end::Search-->
				</div>
				<!--begin::Card toolbar-->
				<div class="card-toolbar">
					<!--begin::Toolbar-->
					<div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
						<?php if ($canExport ?? false): ?>
						<?= $this->include('templates/import_export_toolbar', ['canExport' => $canExport]) ?>
						<?php endif; ?>

						<!--begin::Manage subscriptions-->
						<a href="<?= base_url('school/subscription') ?>" class="btn btn-light-primary me-3">
							<i class="ki-duotone ki-dollar fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
							Manage Subscriptions
						</a>
						<!--end::Manage subscriptions-->

						<!--begin::Add user-->
						<a href="<?= base_url('school/add') ?>" class="btn btn-primary">
							<i class="ki-duotone ki-plus fs-2"></i>
							Add School
						</a>
						<!--end::Add user-->
					</div>
					<!--end::Toolbar-->
				</div>
				<!--end::Card toolbar-->
			</div>
			<!--end::Card header-->
			
			<!--begin::Card body-->
			<div class="card-body py-4">
				<!--begin::Table-->
				<table id="kt_school_table" class="table align-middle table-row-dashed fs-6 gy-5">
					<thead>
						<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
							<th class="min-w-200px">School</th>
							<!--th class="min-w-150px">Email</th>
							<th class="min-w-125px">Phone</th-->
							<th class="min-w-125px">District</th>
							<th class="min-w-125px">Plan</th>
							<th class="min-w-100px">Status</th>
							<th class="text-end min-w-100px">Actions</th>
						</tr>
					</thead>
					<tbody class="text-gray-600 fw-semibold">
						<!-- DataTables will populate this -->
					</tbody>
				</table>
				<!--end::Table-->
			</div>
			<!--end::Card body-->
		</div>
		<!--end::Card-->
	</div>
</div>
<!--end::Content-->

<!--begin::Custom CSS-->
<style>
.dataTables_wrapper .dataTables_length select {
    padding: 0.5rem 2rem 0.5rem 1rem;
    border: 1px solid #e4e6ef;
    border-radius: 0.475rem;
    background-color: #f9f9f9;
}

.dataTables_wrapper .dataTables_filter input {
    padding: 0.75rem 1rem;
    border: 1px solid #e4e6ef;
    border-radius: 0.475rem;
}

.dataTables_wrapper .dataTables_filter {
    display: none;
}
</style>
<!--end::Custom CSS-->

<script>
"use strict";

var KTUsersList = function () {
    var table;
    var datatable;
    var filterSearch;

    var initDatatable = function () {
        table = document.querySelector('#kt_school_table');

        if (!table) {
            return;
        }

        datatable = $(table).DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 500,
            order: [[0, 'asc']], // Order by user name
            stateSave: false,
            
            ajax: {
                url: "<?= base_url('school/getSchoolListing') ?>",
                type: "POST",
                data: function(d) {
                    d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                    console.log('Sending data:', d);
                    return d;
                },
                error: function(xhr, error, thrown) {
                    console.error('AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                }
            },
            
            columns: [
                { data: 0, name: 'sch_name' },
                //{ data: 1, name: 'sch_email' },
                //{ data: 2, name: 'sch_phone' },
                { data: 1, name: 'district_name' },
                { data: 2, name: 'plan_name' },
                { 
                    data: 3, 
                    name: 'status',
                    orderable: true
                },
                { 
                    data: 4, 
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-end'
                }
            ],
            
            language: {
                processing: '<img src="<?php echo base_url('loader/ajax-loader-2.gif'); ?>" alt="Loading..." />',
                lengthMenu: 'Show _MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ users',
                infoEmpty: 'No users found',
                infoFiltered: '(filtered from _MAX_ total users)',
                zeroRecords: 'No matching users found',
                emptyTable: 'No users available',
                paginate: {
                    first: '<i class="ki-duotone ki-double-left fs-2"></i>',
                    last: '<i class="ki-duotone ki-double-right fs-2"></i>',
                    next: '<i class="ki-duotone ki-right fs-2"></i>',
                    previous: '<i class="ki-duotone ki-left fs-2"></i>'
                }
            },
            
            drawCallback: function(settings) {
                KTMenu.createInstances();
                
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });

        datatable.on('draw', function () {
            handleDeleteRows();
        });
    };

    var handleSearchDatatable = function () {
        filterSearch = document.querySelector('#kt_user_search');
        if (filterSearch) {
            filterSearch.addEventListener('keyup', function (e) {
                datatable.search(e.target.value).draw();
            });
        }
    };

    var handleDeleteRows = function () {
        table.addEventListener('click', function (e) {
            const deleteButton = e.target.closest('[data-kt-schools-table-filter="delete_row"]');

            if (!deleteButton) return;

            e.preventDefault();

            const schId    = deleteButton.getAttribute('data-sch-id');
            const row      = deleteButton.closest('tr');
            const schName  = (row.querySelector('td:first-child a.fw-bold') || row.querySelector('td:first-child .fw-bold'))?.innerText.trim() || 'this school';

            Swal.fire({
                title: 'Delete School?',
                html: `Are you sure you want to permanently delete <strong>${schName}</strong>?<br><span class="text-muted fs-7">This action cannot be undone.</span>`,
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn fw-bold btn-danger',
                    cancelButton:  'btn fw-bold btn-active-light-primary'
                }
            }).then(function (result) {
                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Checking...',
                    text: 'Verifying school configuration',
                    allowOutsideClick: false,
                    didOpen: function () { Swal.showLoading(); }
                });

                $.ajax({
                    url: '<?= base_url('school/delete') ?>/' + schId,
                    type: 'POST',
                    data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            datatable.row($(row)).remove().draw();
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                buttonsStyling: false,
                                confirmButtonText: 'OK',
                                customClass: { confirmButton: 'btn fw-bold btn-primary' }
                            });
                        } else if (response.blocked) {
                            Swal.fire({
                                title: 'Cannot Delete',
                                text: response.message,
                                icon: 'warning',
                                buttonsStyling: false,
                                confirmButtonText: 'OK',
                                customClass: { confirmButton: 'btn fw-bold btn-warning' }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'OK',
                                customClass: { confirmButton: 'btn fw-bold btn-primary' }
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Server error (' + xhr.status + '). Please try again.',
                            icon: 'error',
                            buttonsStyling: false,
                            confirmButtonText: 'OK',
                            customClass: { confirmButton: 'btn fw-bold btn-primary' }
                        });
                    }
                });
            });
        });
    };

    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            handleDeleteRows();

<?php if ($canExport ?? false): ?>
            KTExport.init(datatable, {
                filenamePrefix: 'schools',
                title: 'Schools Report',
                columns: [
                    { header: 'School', index: 0 },
                    { header: 'District', index: 1 },
                    { header: 'Plan', index: 2 },
                    { header: 'Status', index: 3 },
                ]
            });
<?php endif; ?>
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTUsersList.init();
});
</script>
