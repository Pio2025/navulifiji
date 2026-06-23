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
                    const userName = stripHtml(data[0]);
                    const email = stripHtml(data[1]);
                    const phone = stripHtml(data[2]);
                    const role = stripHtml(data[3]);
                    const district = stripHtml(data[4]);
                    const status = data[5].includes('Active') ? 'Active' : 'Inactive';
                    
                    exportData.push({
                        'User': userName,
                        'Email': email,
                        'Phone': phone,
                        'Role': role,
                        'District': district,
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
        let text = 'User\tEmail\tPhone\tRole\tDistrict\tStatus\n';
        data.forEach(row => {
            text += `${row['User']}\t${row['Email']}\t${row['Phone']}\t${row['Role']}\t${row['District']}\t${row['Status']}\n`;
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
        let csv = 'User,Email,Phone,Role,District,Status\n';
        data.forEach(row => {
            csv += `"${row['User']}","${row['Email']}","${row['Phone']}","${row['Role']}","${row['District']}","${row['Status']}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'users_' + new Date().getTime() + '.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showExportSuccess('CSV');
    };

    var exportToExcel = function(data) {
        let html = '<table><thead><tr><th>User</th><th>Email</th><th>Phone</th><th>Role</th><th>District</th><th>Status</th></tr></thead><tbody>';
        data.forEach(row => {
            html += `<tr><td>${row['User']}</td><td>${row['Email']}</td><td>${row['Phone']}</td><td>${row['Role']}</td><td>${row['District']}</td><td>${row['Status']}</td></tr>`;
        });
        html += '</tbody></table>';
        
        const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'users_' + new Date().getTime() + '.xls';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showExportSuccess('Excel');
    };

    // COMPLETE WORKING VERSION - Replace all PDF export functions

var drawInitialsAvatar = function(doc, x, y, size, initials) {
    doc.setFillColor(224, 242, 254);
    doc.circle(x + size/2, y + size/2, size/2, 'F');
    
    doc.setDrawColor(0, 158, 247);
    doc.setLineWidth(1);
    doc.circle(x + size/2, y + size/2, size/2, 'S');
    
    doc.setFontSize(14);
    doc.setTextColor(0, 158, 247);
    doc.setFont(undefined, 'bold');
    
    const textWidth = doc.getTextWidth(initials);
    const textX = x + (size - textWidth) / 2;
    const textY = y + size / 2 + 5;
    
    doc.text(initials, textX, textY);
};

var prepareDataWithImages = function(data, callback) {
    const enrichedData = [];
    let processed = 0;
    
    if (data.length === 0) {
        callback(enrichedData);
        return;
    }
    
    datatable.rows().every(function(index) {
        const rowNode = this.node();
        const rowData = data[index];
        
        const userCell = rowNode.querySelector('td:first-child');
        const photoImg = userCell ? userCell.querySelector('img') : null;
        const nameElement = userCell ? userCell.querySelector('.fw-bold') : null;
        const genderElement = userCell ? userCell.querySelector('.text-muted') : null;
        
        const fullName = nameElement ? nameElement.textContent.trim() : rowData['User'];
        const gender = genderElement ? genderElement.textContent.trim() : '';
        
        const nameParts = fullName.split(' ').filter(p => p.length > 0);
        const initials = nameParts.length >= 2 
            ? (nameParts[0][0] + nameParts[nameParts.length - 1][0]).toUpperCase()
            : fullName.substring(0, 2).toUpperCase();
        
        const enrichedRow = {
            fullName: fullName,
            gender: gender,
            initials: initials,
            email: rowData['Email'] || '',
            phone: rowData['Phone'] || '',
            role: rowData['Role'] || '',
            district: rowData['District'] || '',
            status: rowData['Status'],
            hasPhoto: false,
            photoData: null
        };
        
        if (photoImg && photoImg.src && 
            photoImg.src.startsWith('http') && 
            !photoImg.src.includes('blank') &&
            !photoImg.src.includes('placeholder')) {
            
            const img = new Image();
            img.crossOrigin = 'Anonymous';
            
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const size = 80;
                canvas.width = size;
                canvas.height = size;
                const ctx = canvas.getContext('2d');
                
                ctx.fillStyle = '#FFFFFF';
                ctx.fillRect(0, 0, size, size);
                
                ctx.beginPath();
                ctx.arc(size/2, size/2, size/2, 0, Math.PI * 2);
                ctx.closePath();
                ctx.clip();
                
                const scale = Math.max(size / img.width, size / img.height);
                const scaledWidth = img.width * scale;
                const scaledHeight = img.height * scale;
                const offsetX = (size - scaledWidth) / 2;
                const offsetY = (size - scaledHeight) / 2;
                
                ctx.drawImage(img, offsetX, offsetY, scaledWidth, scaledHeight);
                
                try {
                    enrichedRow.hasPhoto = true;
                    enrichedRow.photoData = canvas.toDataURL('image/jpeg', 0.9);
                } catch(e) {
                    enrichedRow.hasPhoto = false;
                }
                
                processed++;
                enrichedData[index] = enrichedRow;
                
                if (processed === data.length) {
                    callback(enrichedData.filter(row => row !== undefined));
                }
            };
            
            img.onerror = function() {
                enrichedRow.hasPhoto = false;
                processed++;
                enrichedData[index] = enrichedRow;
                
                if (processed === data.length) {
                    callback(enrichedData.filter(row => row !== undefined));
                }
            };
            
            img.src = photoImg.src;
        } else {
            processed++;
            enrichedData[index] = enrichedRow;
            
            if (processed === data.length) {
                callback(enrichedData.filter(row => row !== undefined));
            }
        }
    });
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
    
    Swal.fire({
        title: 'Generating PDF...',
        text: 'Please wait while we prepare your document',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'pt', 'a4');
    
    prepareDataWithImages(data, function(enrichedData) {
        // Load logo
        const logoUrl = '<?= base_url("navuli_logo_small_color.png") ?>';
        const logoImg = new Image();
        logoImg.crossOrigin = 'Anonymous';
        
        logoImg.onload = function() {
            generatePDFContent(doc, enrichedData, logoImg);
        };
        
        logoImg.onerror = function() {
            generatePDFContent(doc, enrichedData, null);
        };
        
        logoImg.src = logoUrl;
    });
};

var generatePDFContent = function(doc, enrichedData, logoImg) {
    // Header
    doc.setFontSize(20);
    doc.setTextColor(0, 158, 247);
    doc.setFont(undefined, 'bold');
    doc.text('User Management Report', 40, 45);
    
    doc.setFontSize(10);
    doc.setTextColor(126, 130, 153);
    doc.setFont(undefined, 'normal');
    doc.text('Generated: ' + new Date().toLocaleString(), 40, 65);
    doc.text('Total Users: ' + enrichedData.length, 40, 78);
    
    // Add logo
    if (logoImg) {
        try {
            const pageWidth = doc.internal.pageSize.getWidth();
            const logoHeight = 50;
            const logoWidth = (logoImg.width / logoImg.height) * logoHeight;
            const logoX = pageWidth - logoWidth - 40; // 40pt margin to align with table
            const logoY = 25;
            
            doc.addImage(logoImg, 'PNG', logoX, logoY, logoWidth, logoHeight);
        } catch(e) {
            console.error('Error adding logo:', e);
        }
    }
    
    // Table data
    const tableData = enrichedData.map(row => [
        row.fullName,
        row.email,
        row.phone,
        row.role,
        row.district,
        row.status
    ]);
    
    doc.autoTable({
        head: [['User', 'Email', 'Phone', 'Role', 'District', 'Status']],
        body: tableData,
        startY: 90,
        theme: 'grid',
        headStyles: {
            fillColor: [46, 49, 146],
            textColor: [255, 255, 255],
            fontSize: 11,
            fontStyle: 'bold',
            halign: 'left',
            cellPadding: 8,
            lineWidth: 0.5,
            lineColor: [255, 255, 255]
        },
        bodyStyles: {
            fontSize: 10,
            cellPadding: { top: 10, right: 8, bottom: 10, left: 8 },
            minCellHeight: 50,
            valign: 'middle',
            lineWidth: 0.5,
            lineColor: [229, 231, 235]
        },
        alternateRowStyles: {
            fillColor: [249, 249, 249]
        },
        columnStyles: {
            0: { cellWidth: 200, overflow: 'visible' },
            1: { cellWidth: 'auto' },
            2: { cellWidth: 85 },
            3: { cellWidth: 'auto' },
            4: { cellWidth: 'auto' },
            5: { cellWidth: 85, halign: 'center' }
        },
        tableWidth: 'auto',
        margin: { left: 40, right: 40 },
        styles: {
            lineWidth: 0.5,
            lineColor: [229, 231, 235]
        },
        willDrawCell: function(data) {
            if (data.column.index === 0 && data.cell.section === 'body') {
                data.cell.text = [];
            }
            if (data.column.index === 5 && data.cell.section === 'body') {
                data.cell.text = [];
            }
        },
        didDrawCell: function(data) {
            if (data.column.index === 0 && data.cell.section === 'body') {
                const rowIndex = data.row.index;
                const rowData = enrichedData[rowIndex];
                
                if (!rowData) return;
                
                const avatarX = data.cell.x + 8;
                const avatarY = data.cell.y + (data.cell.height - 34) / 2;
                const avatarSize = 34;
                
                if (rowData.hasPhoto && rowData.photoData) {
                    try {
                        doc.addImage(rowData.photoData, 'JPEG', avatarX, avatarY, avatarSize, avatarSize);
                        doc.setDrawColor(0, 158, 247);
                        doc.setLineWidth(1);
                        doc.circle(avatarX + avatarSize/2, avatarY + avatarSize/2, avatarSize/2, 'S');
                    } catch(e) {
                        drawInitialsAvatar(doc, avatarX, avatarY, avatarSize, rowData.initials);
                    }
                } else {
                    drawInitialsAvatar(doc, avatarX, avatarY, avatarSize, rowData.initials);
                }
                
                const textX = avatarX + avatarSize + 10;
                const centerY = data.cell.y + data.cell.height / 2;
                
                doc.setFontSize(11);
                doc.setTextColor(17, 24, 39);
                doc.setFont(undefined, 'bold');
                doc.text(rowData.fullName, textX, centerY - 5);
                
                if (rowData.gender) {
                    doc.setFontSize(9);
                    doc.setTextColor(107, 114, 128);
                    doc.setFont(undefined, 'normal');
                    doc.text(rowData.gender, textX, centerY + 10);
                }
            }
            
            if (data.column.index === 5 && data.cell.section === 'body') {
                const rowIndex = data.row.index;
                const rowData = enrichedData[rowIndex];
                
                if (!rowData) return;
                
                const status = rowData.status;
                const badgeWidth = 60;
                const badgeHeight = 20;
                const badgeX = data.cell.x + (data.cell.width - badgeWidth) / 2;
                const badgeY = data.cell.y + (data.cell.height - badgeHeight) / 2;
                
                if (status === 'Active') {
                    doc.setFillColor(220, 252, 231);
                    doc.setTextColor(22, 101, 52);
                } else {
                    doc.setFillColor(254, 226, 226);
                    doc.setTextColor(185, 28, 28);
                }
                
                doc.roundedRect(badgeX, badgeY, badgeWidth, badgeHeight, 3, 3, 'F');
                
                doc.setFontSize(9);
                doc.setFont(undefined, 'bold');
                
                const textWidth = doc.getTextWidth(status);
                const textX = badgeX + (badgeWidth - textWidth) / 2;
                const textY = badgeY + 14;
                
                doc.text(status, textX, textY);
            }
        }
    });
    
    // Footer
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        
        // Page number (no line above)
        doc.setFontSize(9);
        doc.setTextColor(126, 130, 153);
        doc.text(
            'Page ' + i + ' of ' + pageCount,
            doc.internal.pageSize.getWidth() / 2,
            doc.internal.pageSize.getHeight() - 20,
            { align: 'center' }
        );
    }
    
    doc.save('users_report_' + new Date().getTime() + '.pdf');
    
    Swal.fire({
        text: "PDF exported successfully!",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok!",
        customClass: {
            confirmButton: "btn fw-bold btn-primary",
        }
    });
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
    KTUsersList.init();
});
</script>
