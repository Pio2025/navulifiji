<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Subscription Management</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">
					<a href="<?= base_url('school') ?>" class="text-muted text-hover-primary">School Listing</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Subscriptions</li>
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
						<input type="text" id="kt_subscription_search" class="form-control form-control-solid w-250px ps-13" placeholder="Search subscriptions..." />
					</div>
					<!--end::Search-->
				</div>
			</div>
			<!--end::Card header-->

			<!--begin::Card body-->
			<div class="card-body py-4">
				<!--begin::Table-->
				<table id="kt_subscription_table" class="table align-middle table-row-dashed fs-6 gy-5">
					<thead>
						<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
							<th class="min-w-200px">School</th>
							<th class="min-w-125px">Plan</th>
							<th class="min-w-110px">Start Date</th>
							<th class="min-w-110px">Expiry Date</th>
							<th class="min-w-125px">Status</th>
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

var KTSubscriptionsList = function () {
    var table;
    var datatable;
    var filterSearch;

    var initDatatable = function () {
        table = document.querySelector('#kt_subscription_table');

        if (!table) {
            return;
        }

        datatable = $(table).DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 500,
            order: [[0, 'asc']],
            stateSave: false,

            ajax: {
                url: "<?= base_url('school/subscription/getListing') ?>",
                type: "POST",
                data: function(d) {
                    d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                    return d;
                },
                error: function(xhr, error, thrown) {
                    console.error('AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                }
            },

            columns: [
                { data: 0, name: 'sch_name' },
                { data: 1, name: 'plan_name' },
                { data: 2, name: 'subscription_start_date' },
                { data: 3, name: 'subscription_end_date' },
                { data: 4, name: 'subscription_status' },
                {
                    data: 5,
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-end'
                }
            ],

            language: {
                processing: '<img src="<?php echo base_url('loader/ajax-loader-2.gif'); ?>" alt="Loading..." />',
                lengthMenu: 'Show _MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ subscriptions',
                infoEmpty: 'No subscriptions found',
                infoFiltered: '(filtered from _MAX_ total subscriptions)',
                zeroRecords: 'No matching subscriptions found',
                emptyTable: 'No subscriptions available',
                paginate: {
                    first: '<i class="ki-duotone ki-double-left fs-2"></i>',
                    last: '<i class="ki-duotone ki-double-right fs-2"></i>',
                    next: '<i class="ki-duotone ki-right fs-2"></i>',
                    previous: '<i class="ki-duotone ki-left fs-2"></i>'
                }
            },

            drawCallback: function(settings) {
                KTMenu.createInstances();
            }
        });

        datatable.on('draw', function () {
            handleDeleteRows();
        });
    };

    var handleSearchDatatable = function () {
        filterSearch = document.querySelector('#kt_subscription_search');
        if (filterSearch) {
            filterSearch.addEventListener('keyup', function (e) {
                datatable.search(e.target.value).draw();
            });
        }
    };

    var handleDeleteRows = function () {
        table.addEventListener('click', function (e) {
            const deleteButton = e.target.closest('[data-kt-subscription-table-filter="delete_row"]');

            if (!deleteButton) return;

            e.preventDefault();

            const subId   = deleteButton.getAttribute('data-sub-id');
            const row     = deleteButton.closest('tr');
            const schName = (row.querySelector('td:first-child a.fw-bold') || row.querySelector('td:first-child .fw-bold'))?.innerText.trim() || 'this subscription';

            Swal.fire({
                title: 'Delete Subscription?',
                html: `Are you sure you want to permanently delete the subscription for <strong>${schName}</strong>?<br><span class="text-muted fs-7">This action cannot be undone.</span>`,
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

                $.ajax({
                    url: '<?= base_url('school/subscription/delete') ?>/' + subId,
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
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTSubscriptionsList.init();
});
</script>
