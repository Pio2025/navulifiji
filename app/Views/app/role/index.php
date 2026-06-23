<!--begin::Toolbar-->
<!--
    REQUIRED LIBRARIES FOR PDF EXPORT:
    ==================================
    The PDF export functionality requires DataTables Buttons extension with pdfmake.
    These are already included in Metronic's datatables.bundle.js
    
    If PDF export doesn't work, ensure these are loaded in your main.php:
    1. app/assets/plugins/custom/datatables/datatables.bundle.js (already included)
    
    The bundle includes:
    - DataTables Buttons
    - JSZip (for Excel export)
    - pdfMake (for PDF export)
    - vfs_fonts.js (for PDF fonts)
-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
		<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
			<h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Role Management</h1>
			<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
				<li class="breadcrumb-item text-muted">
					<a href="<?php echo base_url(); ?>/dashboard" class="text-muted text-hover-primary">Home</a>
				</li>
				<li class="breadcrumb-item">
					<span class="bullet bg-gray-500 w-5px h-2px"></span>
				</li>
				<li class="breadcrumb-item text-muted">Role Listing</li>
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
						<input type="text" id="kt_role_search" class="form-control form-control-solid w-250px ps-13" placeholder="Search roles..." />
					</div>
					<!--end::Search-->
				</div>
				<!--begin::Card toolbar-->
				<div class="card-toolbar">
					<!--begin::Toolbar-->
					<div class="d-flex justify-content-end" data-kt-role-table-toolbar="base">
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
							<!--begin::Menu item-->
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3" data-kt-export="copy">
									Copy to clipboard
								</a>
							</div>
							<!--end::Menu item-->
							<!--begin::Menu item-->
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3" data-kt-export="excel">
									Export as Excel
								</a>
							</div>
							<!--end::Menu item-->
							<!--begin::Menu item-->
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3" data-kt-export="csv">
									Export as CSV
								</a>
							</div>
							<!--end::Menu item-->
							<!--begin::Menu item-->
							<div class="menu-item px-3">
								<a href="#" class="menu-link px-3" data-kt-export="pdf">
									Export as PDF
								</a>
							</div>
							<!--end::Menu item-->
						</div>
						<!--end::Menu-->
						<!--end::Export-->
						
						<!--begin::Add role-->
						<a href="<?php echo base_url('role/add'); ?>" class="btn btn-primary">
							<i class="ki-duotone ki-plus fs-2"></i>
							Add Role
						</a>
						<!--end::Add role-->
					</div>
					<!--end::Toolbar-->
				</div>
				<!--end::Card toolbar-->
			</div>
			<!--end::Card header-->
			
			<!--begin::Card body-->
			<div class="card-body py-4">
				<!--begin::Table-->
				<table id="kt_role_table" class="table align-middle table-row-dashed fs-6 gy-5">
					<thead>
						<tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
							<th class="min-w-125px">Role Name</th>
							<th class="min-w-125px">Description</th>
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

<!--begin::Custom CSS for DataTables Metronic Integration-->
<style>
/* Square avatar with rounded corners */
.symbol.symbol-50px .symbol-label.rounded {
    border-radius: 0.475rem !important;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Ensure DataTables controls match Metronic theme */
.dataTables_wrapper .dataTables_length select {
    padding: 0.5rem 2rem 0.5rem 1rem;
    border: 1px solid #e4e6ef;
    border-radius: 0.475rem;
    background-color: #f9f9f9;
    font-size: 1rem;
    font-weight: 500;
    color: #181c32;
}

.dataTables_wrapper .dataTables_filter input {
    padding: 0.75rem 1rem;
    border: 1px solid #e4e6ef;
    border-radius: 0.475rem;
    margin-left: 0.5rem;
}

.dataTables_wrapper .dataTables_info {
    padding-top: 1rem;
    font-size: 0.925rem;
    color: #7e8299;
}

.dataTables_wrapper .dataTables_paginate {
    padding-top: 0.5rem;
}

/* Make pagination buttons match Metronic style */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem;
    margin: 0 0.25rem;
    border-radius: 0.475rem;
    border: 0;
    background: transparent;
    color: #7e8299;
    font-weight: 500;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f9f9f9;
    color: #009ef7;
    border: 0;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #009ef7;
    color: #fff;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Processing indicator */
.dataTables_wrapper .dataTables_processing {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid #e4e6ef;
    border-radius: 0.475rem;
    padding: 2rem;
    box-shadow: 0px 0px 50px 0px rgba(82, 63, 105, 0.15);
}

.dataTables_wrapper .dataTables_processing img {
    width: 64px;
    height: 64px;
}

/* Table sorting indicators */
table.dataTable thead .sorting:before,
table.dataTable thead .sorting_asc:before,
table.dataTable thead .sorting_desc:before,
table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after {
    opacity: 0.3;
    font-family: "Ki Duotone";
}

/* Hide default DataTables search - we're using custom */
.dataTables_wrapper .dataTables_filter {
    display: none;
}
</style>
<!--end::Custom CSS-->

<script>
"use strict";

var KTRolesList = function () {
    var table;
    var datatable;
    var filterSearch;

    var initDatatable = function () {
        table = document.querySelector('#kt_role_table');

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
                url: "<?php echo base_url('index.php/role/getRoleListing'); ?>", // Added index.php explicitly
                type: "POST",
                data: function(d) {
                    // Add CSRF token for CodeIgniter 4
                    d.<?= csrf_token() ?> = '<?= csrf_hash() ?>';
                    console.log('Sending data:', d);
                    return d;
                },
                error: function(xhr, error, thrown) {
                    console.error('=== AJAX ERROR DETAILS ===');
                    console.error('Error:', error);
                    console.error('Status:', xhr.status);
                    console.error('Response:', xhr.responseText);
                    console.error('Thrown:', thrown);
                    console.error('URL:', this.url);
                    console.error('========================');
                }
            },
            
            columns: [
                { data: 0, name: 'role_name' },
                { 
                    data: 1, 
                    name: 'role_rank',
                    render: function(data, type, row) {
                        if (!data) {
                            return '<span class="badge badge-light-secondary">N/A</span>';
                        }
                        return data;
                    }
                },
                { 
                    data: 3, 
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-end'
                }
            ],
            
            columnDefs: [
                {
                    targets: 0,
                    render: function(data, type, row) {
                        // Get role_rank from row[2] or use '#' as fallback
                        const roleRank = row[2] || '#';
                        
                        return `
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label fs-2 fw-bold bg-light-primary text-primary rounded">
                                        ${roleRank}
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 text-hover-primary fw-bold">${data}</span>
                                </div>
                            </div>
                        `;
                    }
                }
            ],
            
            language: {
                processing: '<img src="<?php echo base_url('loader/ajax-loader-2.gif'); ?>" alt="Loading..." />',
                lengthMenu: 'Show _MENU_',
                info: 'Showing _START_ to _END_ of _TOTAL_ roles',
                infoEmpty: 'No roles found',
                infoFiltered: '(filtered from _MAX_ total roles)',
                zeroRecords: 'No matching roles found',
                emptyTable: 'No roles available',
                paginate: {
                    first: '<i class="ki-duotone ki-double-left fs-2"></i>',
                    last: '<i class="ki-duotone ki-double-right fs-2"></i>',
                    next: '<i class="ki-duotone ki-right fs-2"></i>',
                    previous: '<i class="ki-duotone ki-left fs-2"></i>'
                }
            },
            
            drawCallback: function(settings) {
                // Reinitialize Metronic menu components
                KTMenu.createInstances();
                
                // Reinitialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });

        // Re-init functions on datatable re-draws
        datatable.on('draw', function () {
            handleDeleteRows();
        });
    };

    var handleSearchDatatable = function () {
        filterSearch = document.querySelector('#kt_role_search');
        if (filterSearch) {
            filterSearch.addEventListener('keyup', function (e) {
                datatable.search(e.target.value).draw();
            });
        }
    };

    var handleDeleteRows = function () {
        table.addEventListener('click', function(e) {
            const deleteButton = e.target.closest('[data-kt-roles-table-filter="delete_row"]');
            
            if (!deleteButton) return;
            
            e.preventDefault();
            
            const roleId = deleteButton.getAttribute('data-role-id');
            const row = deleteButton.closest('tr');
            const roleName = row.querySelector('td:first-child').innerText.trim();
            
            console.log('Role ID:', roleId); // DEBUG
            console.log('Role Name:', roleName); // DEBUG
            
            Swal.fire({
                title: 'Delete Role?',
                text: `Are you sure you want to delete "${roleName}"?`,
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
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // AJAX request with detailed logging
                    $.ajax({
                        url: '<?php echo base_url('role/delete'); ?>/' + roleId,
                        type: 'POST',
                        data: {
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('Success response:', response); // DEBUG
                            
                            if (response.success) {
                                datatable.row($(row)).remove().draw();
                                
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message || "Role deleted successfully!",
                                    icon: 'success',
                                    buttonsStyling: false,
                                    confirmButtonText: 'Ok, got it!',
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary"
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message || "Failed to delete role.",
                                    icon: 'error',
                                    buttonsStyling: false,
                                    confirmButtonText: 'Ok, got it!',
                                    customClass: {
                                        confirmButton: "btn fw-bold btn-primary"
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error Details:'); // DEBUG
                            console.error('Status:', status); // DEBUG
                            console.error('Error:', error); // DEBUG
                            console.error('Response:', xhr.responseText); // DEBUG
                            console.error('Status Code:', xhr.status); // DEBUG
                            
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error: ' + xhr.status + ' - ' + error,
                                icon: 'error',
                                buttonsStyling: false,
                                confirmButtonText: 'Ok, got it!',
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

    // Export buttons functionality
    var handleExportButtons = function() {
        // Export menu dropdown
        const exportMenu = document.querySelector('[data-kt-menu="true"]#kt_datatable_example_export_menu');
        
        if (!exportMenu) return;
        
        const exportButtons = exportMenu.querySelectorAll('[data-kt-export]');
        
        exportButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const exportType = this.getAttribute('data-kt-export');
                const exportData = [];
                
                // Get all data from DataTable
                datatable.rows().every(function() {
                    const data = this.data();
                    exportData.push({
                        'Role Name': data[0],
                        'Description': data[1] || 'N/A'
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

    // Export to clipboard
    var exportToCopy = function(data) {
        let text = 'Role Name\tDescription\n';
        data.forEach(row => {
            text += `${row['Role Name']}\t${row['Description']}\n`;
        });
        
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                text: "Data copied to clipboard!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                }
            });
        });
    };

    // Export to CSV
    var exportToCSV = function(data) {
        let csv = 'Role Name,Description\n';
        data.forEach(row => {
            csv += `"${row['Role Name']}","${row['Description']}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'roles_' + new Date().getTime() + '.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showExportSuccess('CSV');
    };

    // Export to Excel (using HTML table method)
    var exportToExcel = function(data) {
        let html = '<table><thead><tr><th>Role Name</th><th>Description</th></tr></thead><tbody>';
        data.forEach(row => {
            html += `<tr><td>${row['Role Name']}</td><td>${row['Description']}</td></tr>`;
        });
        html += '</tbody></table>';
        
        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'roles_' + new Date().getTime() + '.xls';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showExportSuccess('Excel');
    };

    // Export to PDF with Logo aligned to table's right edge
    var exportToPDF = function(data) {
        if (typeof window.jspdf === 'undefined') {
            Swal.fire({
                title: 'Library Missing',
                text: 'jsPDF library is required.',
                icon: "info",
                buttonsStyling: false,
                confirmButtonText: "Ok!",
                customClass: { confirmButton: "btn fw-bold btn-primary" }
            });
            return;
        }
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'pt', 'a4');
        
        // Get clean data
        const tableData = [];
        datatable.rows().every(function() {
            const rowData = this.data();
            tableData.push([
                stripHtml(rowData[0]),
                rowData[1] || 'N/A'
            ]);
        });
        
        // Logo setup
        const logoUrl = '<?= base_url("navuli_logo_small_color.png") ?>';
        
        const img = new Image();
        img.src = logoUrl;
        
        img.onload = function() {
            // Get page dimensions
            const pageWidth = doc.internal.pageSize.getWidth();
            const rightMargin = 0; // Same as table margin
            
            // Calculate logo position to align with table's right edge
            const logoX = pageWidth - rightMargin - img.width;
            
            // Add logo (auto-sized, aligned to table's right edge)
            doc.addImage(img, 'PNG', logoX, 25);
            
            // Title on the left
            doc.setFontSize(20);
            doc.setTextColor(0, 158, 247);
            doc.text('Role Management Report', 40, 50);
            
            // Subtitle
            doc.setFontSize(10);
            doc.setTextColor(126, 130, 153);
            doc.text('Generated on: ' + new Date().toLocaleString(), 40, 70);
            
            
            // Subtitle
            doc.setFontSize(8);
            doc.setTextColor(126, 130, 153);
            doc.text('Navuli Fiji - School Management Information System', 40, 90);
            
            // Horizontal line separator (from left margin to right margin)
            doc.setDrawColor(228, 230, 239);
            doc.setLineWidth(1);
            doc.line(40, 105, pageWidth - 40, 105);
            
            // Table
            doc.autoTable({
                head: [['Role Name', 'Description']],
                body: tableData,
                startY: 120,
                theme: 'striped',
                headStyles: {
                    fillColor: [0, 158, 247],
                    textColor: [255, 255, 255],
                    fontSize: 11,
                    fontStyle: 'bold',
                    halign: 'left'
                },
                bodyStyles: {
                    fontSize: 10,
                    textColor: [24, 28, 50]
                },
                alternateRowStyles: {
                    fillColor: [249, 249, 249]
                },
                columnStyles: {
                    0: { cellWidth: 150 },
                    1: { cellWidth: 'auto' }
                },
                margin: { left: 40, right: 40 }, // Table margins
                styles: {
                    overflow: 'linebreak',
                    cellPadding: 8,
                    lineColor: [228, 230, 239],
                    lineWidth: 0.5
                }
            });
            
            // Footer with page numbers
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                
                doc.setFontSize(9);
                doc.setTextColor(126, 130, 153);
                doc.text(
                    'Page ' + i + ' of ' + pageCount,
                    pageWidth / 2,
                    doc.internal.pageSize.getHeight() - 20,
                    { align: 'center' }
                );
            }
            
            doc.save('role_management_report_' + new Date().getTime() + '.pdf');
            showExportSuccess('PDF');
        };
        
        img.onerror = function() {
            console.warn('Logo failed to load, generating PDF without logo');
            generatePDFWithoutLogo(doc, tableData);
        };
    };
    
    // Fallback function if logo fails to load
    var generatePDFWithoutLogo = function(doc, tableData) {
        // Title
        doc.setFontSize(18);
        doc.setTextColor(0, 158, 247);
        doc.text('Role Management Report', 40, 40);
        
        // Subtitle
        doc.setFontSize(10);
        doc.setTextColor(126, 130, 153);
        doc.text('Generated: ' + new Date().toLocaleString(), 40, 60);
        
        // Table
        doc.autoTable({
            head: [['Role Name', 'Description']],
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
            columnStyles: {
                0: { cellWidth: 150 },
                1: { cellWidth: 'auto' }
            },
            margin: { left: 40, right: 40 }
        });
        
        // Page numbers
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
        
        doc.save('roles_' + new Date().getTime() + '.pdf');
        showExportSuccess('PDF');
    };
    
    // Helper function to strip HTML
    var stripHtml = function(html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    };

    // Show export success message
    var showExportSuccess = function(type) {
        Swal.fire({
            text: "Data exported successfully as " + type + "!",
            icon: "success",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
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

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTRolesList.init();
});
</script>
