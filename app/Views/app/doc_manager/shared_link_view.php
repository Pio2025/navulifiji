<?php
$doc         = $doc ?? [];
$token       = $token ?? '';
$canDownload = $canDownload ?? false;
$viewUrl     = base_url('doc-manager/shared-link/' . $token . '/view');
$downloadUrl = base_url('doc-manager/shared-link/' . $token . '/download');
$isExternal    = !empty($doc['is_external']);
$isPreviewable = !$isExternal && in_array($doc['extension'] ?? '', ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp'], true);
?>

<div class="text-center mb-8">
    <i class="ki-duotone <?= esc($doc['icon']) ?> fs-5x text-<?= esc($doc['color']) ?> mb-4">
        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
    </i>
    <h2 class="text-gray-900 fw-bold mb-1"><?= esc($doc['original_name']) ?></h2>
    <div class="text-muted fs-6"><?= esc($doc['category']) ?> · Shared document</div>
</div>

<?php if ($isPreviewable): ?>
<div class="border rounded mb-6" style="height: 60vh; overflow: hidden;">
    <iframe src="<?= $viewUrl ?>" style="width:100%; height:100%; border:0;"></iframe>
</div>
<?php elseif ($isExternal): ?>
<div class="alert alert-secondary text-center mb-6">This is a lesson video hosted externally — use View to watch it.</div>
<?php else: ?>
<div class="alert alert-secondary text-center mb-6">No inline preview available for this file type — use View or Download below.</div>
<?php endif; ?>

<div class="d-flex justify-content-center gap-3">
    <a href="<?= $viewUrl ?>" target="_blank" class="btn btn-light-info">
        <i class="ki-duotone ki-eye fs-3 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
        View
    </a>
    <?php if (!$isExternal): ?>
    <button type="button" class="btn btn-light-dark" onclick="printSharedDoc()">
        <i class="ki-duotone ki-printer fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
        Print
    </button>
    <?php endif; ?>
    <?php if ($canDownload && !$isExternal): ?>
    <a href="<?= $downloadUrl ?>" class="btn btn-primary">
        <i class="ki-duotone ki-down fs-3 me-1"><span class="path1"></span><span class="path2"></span></i>
        Download
    </a>
    <?php endif; ?>
</div>

<script>
function printSharedDoc() {
    var win = window.open('<?= $viewUrl ?>', '_blank');
    if (win) {
        win.addEventListener('load', function () {
            setTimeout(function () { win.print(); }, 400);
        });
    }
}
</script>
