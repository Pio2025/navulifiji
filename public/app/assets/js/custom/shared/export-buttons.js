"use strict";

/**
 * Generic client-side Export (Copy/CSV/Excel/PDF) for a DataTable, reading
 * from the currently rendered page (matches the existing behaviour on the
 * User/School/Role/Permission listing pages this was extracted from — for a
 * server-side DataTable that means "current page", not the whole dataset).
 *
 * Usage:
 *   KTExport.init(datatable, {
 *     menuId: 'kt_datatable_example_export_menu', // optional, this is the default
 *     filenamePrefix: 'admissions',
 *     title: 'Admissions Report',                  // optional, used on the PDF
 *     columns: [
 *       { header: 'Name', index: 0 },
 *       { header: 'Status', index: 5, transform: v => v.includes('Active') ? 'Active' : 'Inactive' },
 *     ],
 *   });
 */
var KTExport = (function () {
    var stripHtml = function (html) {
        var tmp = document.createElement('div');
        tmp.innerHTML = html == null ? '' : String(html);
        return tmp.textContent || tmp.innerText || '';
    };

    var showExportSuccess = function (format) {
        Swal.fire({
            text: format + ' export ready.',
            icon: 'success',
            buttonsStyling: false,
            confirmButtonText: 'Ok!',
            customClass: { confirmButton: 'btn fw-bold btn-primary' }
        });
    };

    var collectRows = function (datatable, columns) {
        var rows = [];
        datatable.rows().every(function () {
            var raw = this.data();
            var row = {};
            columns.forEach(function (col) {
                var value = stripHtml(raw[col.index]);
                row[col.header] = typeof col.transform === 'function' ? col.transform(value, raw) : value;
            });
            rows.push(row);
        });
        return rows;
    };

    var exportToCopy = function (rows, columns) {
        var headers = columns.map(function (c) { return c.header; });
        var text = headers.join('\t') + '\n';
        rows.forEach(function (row) {
            text += headers.map(function (h) { return row[h]; }).join('\t') + '\n';
        });
        navigator.clipboard.writeText(text).then(function () {
            Swal.fire({
                text: 'Data copied to clipboard!',
                icon: 'success',
                buttonsStyling: false,
                confirmButtonText: 'Ok!',
                customClass: { confirmButton: 'btn fw-bold btn-primary' }
            });
        });
    };

    var downloadBlob = function (content, type, filename) {
        var blob = new Blob([content], { type: type });
        var url = window.URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        window.URL.revokeObjectURL(url);
    };

    var exportToCSV = function (rows, columns, filenamePrefix) {
        var headers = columns.map(function (c) { return c.header; });
        var csv = headers.map(function (h) { return '"' + h + '"'; }).join(',') + '\n';
        rows.forEach(function (row) {
            csv += headers.map(function (h) { return '"' + String(row[h]).replace(/"/g, '""') + '"'; }).join(',') + '\n';
        });
        downloadBlob(csv, 'text/csv', filenamePrefix + '_' + Date.now() + '.csv');
        showExportSuccess('CSV');
    };

    var exportToExcel = function (rows, columns, filenamePrefix) {
        var headers = columns.map(function (c) { return c.header; });
        var html = '<table><thead><tr>' + headers.map(function (h) { return '<th>' + h + '</th>'; }).join('') + '</tr></thead><tbody>';
        rows.forEach(function (row) {
            html += '<tr>' + headers.map(function (h) { return '<td>' + row[h] + '</td>'; }).join('') + '</tr>';
        });
        html += '</tbody></table>';
        downloadBlob(html, 'application/vnd.ms-excel', filenamePrefix + '_' + Date.now() + '.xls');
        showExportSuccess('Excel');
    };

    var exportToPDF = function (rows, columns, opts) {
        if (typeof window.jspdf === 'undefined') {
            Swal.fire({
                title: 'Library Missing',
                text: 'jsPDF library is required for PDF export.',
                icon: 'info',
                buttonsStyling: false,
                confirmButtonText: 'Ok!',
                customClass: { confirmButton: 'btn fw-bold btn-primary' }
            });
            return;
        }

        var jsPDF = window.jspdf.jsPDF;
        var doc = new jsPDF('l', 'pt', 'a4');
        var headers = columns.map(function (c) { return c.header; });
        var body = rows.map(function (row) { return headers.map(function (h) { return row[h]; }); });

        doc.setFontSize(20);
        doc.setTextColor(0, 158, 247);
        doc.setFont(undefined, 'bold');
        doc.text(opts.title || 'Report', 40, 45);

        doc.setFontSize(10);
        doc.setTextColor(126, 130, 153);
        doc.setFont(undefined, 'normal');
        doc.text('Generated: ' + new Date().toLocaleString(), 40, 65);
        doc.text('Total Records: ' + rows.length, 40, 78);

        doc.autoTable({
            head: [headers],
            body: body,
            startY: 90,
            theme: 'grid',
            headStyles: {
                fillColor: [46, 49, 146],
                textColor: [255, 255, 255],
                fontSize: 11,
                fontStyle: 'bold',
                halign: 'left',
                cellPadding: 8
            },
            bodyStyles: {
                fontSize: 10,
                cellPadding: 8,
                valign: 'middle'
            },
            alternateRowStyles: { fillColor: [249, 249, 249] },
            tableWidth: 'auto',
            margin: { left: 40, right: 40 }
        });

        doc.save((opts.filenamePrefix || 'export') + '_' + Date.now() + '.pdf');
        showExportSuccess('PDF');
    };

    var init = function (datatable, opts) {
        opts = opts || {};
        var menuId = opts.menuId || 'kt_datatable_example_export_menu';
        var columns = opts.columns || [];
        var filenamePrefix = opts.filenamePrefix || 'export';
        var menu = document.getElementById(menuId);

        if (!menu || !datatable || columns.length === 0) {
            return;
        }

        menu.querySelectorAll('[data-kt-export]').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                var exportType = this.getAttribute('data-kt-export');
                var rows = collectRows(datatable, columns);

                switch (exportType) {
                    case 'copy':
                        exportToCopy(rows, columns);
                        break;
                    case 'excel':
                        exportToExcel(rows, columns, filenamePrefix);
                        break;
                    case 'csv':
                        exportToCSV(rows, columns, filenamePrefix);
                        break;
                    case 'pdf':
                        exportToPDF(rows, columns, { title: opts.title, filenamePrefix: filenamePrefix });
                        break;
                }
            });
        });
    };

    return { init: init };
})();
