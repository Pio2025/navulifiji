<?php
/**
 * Shared Import/Export toolbar buttons, gated by $canImport / $canExport
 * booleans computed via BaseController::canImportExport(). Import always
 * renders before Export. Pass $importModalId / $exportMenuId to avoid id
 * collisions on pages with more than one table.
 */
$canImport     = $canImport ?? false;
$canExport     = $canExport ?? false;
$importModalId = $importModalId ?? 'kt_import_modal';
$exportMenuId  = $exportMenuId ?? 'kt_datatable_example_export_menu';
?>
<?php if ($canImport): ?>
<!--begin::Import-->
<button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#<?= esc($importModalId) ?>">
	<i class="ki-duotone ki-file-up fs-2">
		<span class="path1"></span>
		<span class="path2"></span>
	</i>
	Import
</button>
<!--end::Import-->
<?php endif; ?>

<?php if ($canExport): ?>
<!--begin::Export-->
<button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
	<i class="ki-duotone ki-exit-up fs-2">
		<span class="path1"></span>
		<span class="path2"></span>
	</i>
	Export
</button>
<!--begin::Menu-->
<div id="<?= esc($exportMenuId) ?>" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
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
<?php endif; ?>
