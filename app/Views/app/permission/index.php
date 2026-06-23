<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Permission Management</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?php echo base_url(); ?>/dashboard" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Permission Listing</li>
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
						<input type="text" id="kt_permission_search" class="form-control form-control-solid w-250px ps-13" placeholder="Search permissions..." />
					</div>
					<!--end::Search-->
				</div>
				<!--begin::Card toolbar-->
				<div class="card-toolbar">
					<!--begin::Toolbar-->
					<div class="d-flex justify-content-end" data-kt-permission-table-toolbar="base">
						<!--begin::Export-->
						<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
							<i class="ki-duotone ki-exit-up fs-2">
								<span class="path1"></span>
								<span class="path2"></span>
							</i>
							Export
						</button>
						<!--begin::Menu-->
						<div id="kt_datatable_example_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3" data-kt-export="copy">
									Copy to clipboard
								</a>
							</div>
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3" data-kt-export="excel">
									Export as Excel
								</a>
							</div>
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3" data-kt-export="csv">
									Export as CSV
								</a>
							</div>
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3" data-kt-export="pdf">
									Export as PDF
								</a>
							</div>
						</div>
						<!--end::Menu-->
						<!--end::Export-->
						
						<!--begin::Add permission-->
						<a href="<?php echo base_url('permission/add'); ?>" class="btn btn-primary">
							<i class="ki-duotone ki-plus fs-2"></i>
							Add Permission
						</a>
						<!--end::Add permission-->
					</div>
					<!--end::Toolbar-->
				</div>
				<!--end::Card toolbar-->
			</div>
			<!--end::Card header-->
			
			<!--begin::Card body-->
			<div class="card-body py-4">
				<!--begin::Table-->
				<table id="kt_permission_table" class="table align-middle table-row-dashed fs-6 gy-5">
					<thead>
						<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
							<th class="min-w-125px">Module</th>
							<th class="min-w-125px">Permission Name</th>
							<th class="min-w-100px">Code</th>
							<th class="min-w-80px">Show In Nav</th>
							<th class="min-w-80px">Status</th>
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

var KTPermissionsList = function () {
    var table;
    var datatable;
    var filterSearch;

    var initDatatable = function () {
        table = document.querySelector('#kt_permission_table');

        if (!table) {
            return;
        }

        datatable = $(table).DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 500,
            order: [[0, 'asc']], // Order by module name
            stateSave: false,
            
            ajax: {
                url: "<?php echo base_url('permission/getPermissionListing'); ?>",
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
                { data: 0, name: 'module_name' },
                { data: 1, name: 'perm_name' },
                { data: 2, name: 'perm_code' },
                { 
                    data: 3, 
                    name: 'show_in_nav',
                    orderable: true
                },
                { 
                    data: 4, 
                    name: 'perm_status',
                    orderable: true
                },
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
                info: 'Showing _START_ to _END_ of _TOTAL_ permissions',
                infoEmpty: 'No permissions found',
                infoFiltered: '(filtered from _MAX_ total permissions)',
                zeroRecords: 'No matching permissions found',
                emptyTable: 'No permissions available',
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
        filterSearch = document.querySelector('#kt_permission_search');
        if (filterSearch) {
            filterSearch.addEventListener('keyup', function (e) {
                datatable.search(e.target.value).draw();
            });
        }
    };

    var handleDeleteRows = function () {
        table.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('[data-kt-permissions-table-filter="delete_row"]');
            
            if (!deleteButton) return;
            
            e.preventDefault();
            
            const permissionId = deleteButton.getAttribute('data-permission-id');
            const row = deleteButton.closest('tr');
            const permissionName = row.cells[1].innerText.trim();
            
            Swal.fire({
                title: 'Delete Permission?',
                text: `Are you sure you want to delete "${permissionName}"?`,
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    $.ajax({
                        url: '<?php echo base_url('permission/delete'); ?>/' + permissionId,
                        type: 'POST',
                        data: {
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                datatable.row($(row)).remove().draw();
                                
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message,
                                    icon: 'success',
                                    buttonsStyling: false,
                                    confirmButtonText: 'Ok!',
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary"
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error',
                                    buttonsStyling: false,
                                    confirmButtonText: 'Ok!',
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary"
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error: ' + xhr.status,
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'Ok!',
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary"
                                }
                            });
                        }
                    });
                }
            });
        });
    };

    // Export functionality
    var handleExportButtons = function() {
        const exportMenu = document.querySelector('[data-kt-menu="true"]#kt_datatable_example_export_menu');
        
        if (!exportMenu) return;
        
        const exportButtons = exportMenu.querySelectorAll('[data-kt-export]');
        
        exportButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const exportType = this.getAttribute('data-kt-export');
                const exportData = [];
                
                datatable.rows().every(function() {
                    const data = this.data();
                    const showInNav = data[3].includes('Yes') ? 'Yes' : 'No';
                    const status = data[4].includes('Active') ? 'Active' : 'Inactive';
                    
                    exportData.push({
                        'Module': stripHtml(data[0]),
                        'Permission Name': stripHtml(data[1]),
                        'Code': stripHtml(data[2]),
                        'Show In Nav': showInNav,
                        'Status': status
                    });
                });
                
                switch(exportType) {
                    case 'copy':
                        exportToCopy(exportData);
                        break;
                    case 'excel':
                        exportToExcel(exportData);
                        break;
                    case 'csv':
                        exportToCSV(exportData);
                        break;
                    case 'pdf':
                        exportToPDF(exportData);
                        break;
                }
            });
        });
    };

    var stripHtml = function(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    };

    var exportToCopy = function(data) {
        let text = 'Module\tPermission Name\tCode\tShow In Nav\tStatus\n';
        data.forEach(row => {
            text += `${row['Module']}\t${row['Permission Name']}\t${row['Code']}\t${row['Show In Nav']}\t${row['Status']}\n`;
        });
        
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                text: "Data copied to clipboard!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
        });
    };

    var exportToCSV = function(data) {
        let csv = 'Module,Permission Name,Code,Show In Nav,Status\n';
        data.forEach(row => {
            csv += `"${row['Module']}","${row['Permission Name']}","${row['Code']}","${row['Show In Nav']}","${row['Status']}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'permissions_' + new Date().getTime() + '.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showExportSuccess('CSV');
    };

    var exportToExcel = function(data) {
        let html = '<table><thead><tr><th>Module</th><th>Permission Name</th><th>Code</th><th>Show In Nav</th><th>Status</th></tr></thead><tbody>';
        data.forEach(row => {
            html += `<tr><td>${row['Module']}</td><td>${row['Permission Name']}</td><td>${row['Code']}</td><td>${row['Show In Nav']}</td><td>${row['Status']}</td></tr>`;
        });
        html += '</tbody></table>';
        
        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'permissions_' + new Date().getTime() + '.xls';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showExportSuccess('Excel');
    };

    var exportToPDF = function(data) {
        if (typeof window.jspdf === 'undefined') {
            Swal.fire({
                title: 'Library Missing',
                text: 'jsPDF library is required for PDF export.',
                icon: "info",
                buttonsStyling: false,
                confirmButtonText: "Ok!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
            return;
        }
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'pt', 'a4');
        
        const tableData = data.map(row => [
            row['Module'],
            row['Permission Name'],
            row['Code'],
            row['Show In Nav'],
            row['Status']
        ]);
        
        doc.setFontSize(18);
        doc.setTextColor(0, 158, 247);
        doc.text('Permission Management Report', 40, 40);
        
        doc.setFontSize(10);
        doc.setTextColor(126, 130, 153);
        doc.text('Generated: ' + new Date().toLocaleString(), 40, 60);
        
        doc.autoTable({
            head: [['Module', 'Permission Name', 'Code', 'Show In Nav', 'Status']],
            body: tableData,
            startY: 80,
            theme: 'striped',
            headStyles: {
                fillColor: [0, 158, 247],
                textColor: [255, 255, 255],
                fontSize: 11,
                fontStyle: 'bold'
            },
            bodyStyles: {
                fontSize: 10
            },
            alternateRowStyles: {
                fillColor: [249, 249, 249]
            },
            margin: { left: 40, right: 40 }
        });
        
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(9);
            doc.text(
                'Page ' + i + ' of ' + pageCount,
                doc.internal.pageSize.getWidth() / 2,
                doc.internal.pageSize.getHeight() - 20,
                { align: 'center' }
            );
        }
        
        doc.save('permissions_' + new Date().getTime() + '.pdf');
        showExportSuccess('PDF');
    };

    var showExportSuccess = function(type) {
        Swal.fire({
            text: "Data exported successfully as " + type + "!",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok!",
            customClass: {
                confirmButton: "btn fw-bold btn-primary",
            }
        });
    };

    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            handleDeleteRows();
            handleExportButtons();
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    KTPermissionsList.init();
});
</script>
