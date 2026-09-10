<?php
$doc         = $doc ?? [];
$token       = $token ?? '';
$canDownload = $canDownload ?? false;
$viewUrl     = base_url('doc-manager/shared-link/' . $token . '/view');
$downloadUrl = base_url('doc-manager/shared-link/' . $token . '/download');
$isExternal    = !empty($doc['is_external']);
$extension     = strtolower($doc['extension'] ?? '');
$isImage       = !$isExternal && in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
$isPdf         = !$isExternal && $extension === 'pdf';
$isPreviewable = $isImage || $isPdf;
?>

<div class="text-center mb-8">
    <i class="ki-duotone <?= esc($doc['icon']) ?> fs-5x text-<?= esc($doc['color']) ?> mb-4">
        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
    </i>
    <h2 class="text-gray-900 fw-bold mb-1"><?= esc($doc['original_name']) ?></h2>
    <div class="text-muted fs-6"><?= esc($doc['category']) ?> · Shared document</div>
</div>

<?php if ($isImage): ?>
<div class="border rounded mb-6 text-center bg-light p-3" id="shared_doc_embed">
    <img src="<?= $viewUrl ?>" alt="<?= esc($doc['original_name']) ?>" class="mw-100" style="max-height: 60vh;" onerror="docmgrEmbedFallback()" />
</div>
<?php elseif ($isPdf): ?>
<div class="border rounded mb-6" style="height: 60vh; overflow: hidden;" id="shared_doc_embed">
    <iframe id="shared_doc_frame" src="<?= $viewUrl ?>" style="width:100%; height:100%; border:0;"></iframe>
</div>
<?php endif; ?>

<?php if ($isExternal): ?>
<div class="notice d-flex bg-light-info rounded border border-info border-dashed p-5 mb-6 text-start">
    <i class="ki-duotone ki-information fs-2tx text-info me-4 flex-shrink-0">
        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
    </i>
    <div class="fs-6 text-gray-700">This is a lesson video hosted externally — use View to watch it.</div>
</div>
<?php elseif (!$isPreviewable): ?>
<div class="notice d-flex bg-light-info rounded border border-info border-dashed p-5 mb-6 text-start">
    <i class="ki-duotone ki-information fs-2tx text-info me-4 flex-shrink-0">
        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
    </i>
    <div class="fs-6 text-gray-700">No inline preview available for this file type — use View or Download below.</div>
</div>
<?php endif; ?>

<div class="notice d-flex bg-light-info rounded border border-info border-dashed p-5 mb-6 text-start d-none" id="shared_doc_fallback">
    <i class="ki-duotone ki-information fs-2tx text-info me-4 flex-shrink-0">
        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
    </i>
    <div class="fs-6 text-gray-700">This file can't be previewed inline here — use View or Download below.</div>
</div>

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
function docmgrEmbedFallback() {
    var embed = document.getElementById('shared_doc_embed');
    var fb    = document.getElementById('shared_doc_fallback');
    if (embed) embed.classList.add('d-none');
    if (fb) fb.classList.remove('d-none');
}

<?php if ($isPdf): ?>
(function () {
    var iframe = document.getElementById('shared_doc_frame');
    if (!iframe) return;
    var checked = false;
    function check() {
        if (checked) return;
        checked = true;
        var failed = false;
        try {
            var loc = iframe.contentWindow && iframe.contentWindow.location.href;
            if (!loc || loc === 'about:blank') failed = true;
        } catch (e) {
            failed = true;
        }
        if (failed) docmgrEmbedFallback();
    }
    iframe.addEventListener('load', function () { setTimeout(check, 300); });
    setTimeout(check, 3000);
})();
<?php endif; ?>

function printSharedDoc() {
    var win = window.open('<?= $viewUrl ?>', '_blank');
    if (win) {
        win.addEventListener('load', function () {
            setTimeout(function () { win.print(); }, 400);
        });
    }
}
</script>
