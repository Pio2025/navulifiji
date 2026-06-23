<!--begin::Reactions Modal-->
<div class="modal fade" id="cd_reactions_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-6 mb-0">Reactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3 pb-4">
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-7 fw-semibold border-0 mb-4" id="cd_react_tabs">
                    <li class="nav-item">
                        <a class="nav-link active text-active-primary pb-3 d-flex align-items-center gap-1" href="#" id="cd_react_tab_all">
                            All <span class="badge badge-light ms-1 fs-9" id="cd_react_all_cnt">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-3 d-flex align-items-center gap-1" href="#" id="cd_react_tab_like">
                            <i class="ki-duotone ki-like fs-6 text-primary"><span class="path1"></span><span class="path2"></span></i>
                            <span class="badge badge-light-primary ms-1 fs-9" id="cd_react_like_cnt">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-3 d-flex align-items-center gap-1" href="#" id="cd_react_tab_dislike">
                            <i class="ki-duotone ki-dislike fs-6 text-danger"><span class="path1"></span><span class="path2"></span></i>
                            <span class="badge badge-light-danger ms-1 fs-9" id="cd_react_dislike_cnt">0</span>
                        </a>
                    </li>
                </ul>
                <div id="cd_reactions_list" style="max-height:320px;overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>
<!--end::Reactions Modal-->

<!--begin::Photo Viewer Modal-->
<div class="modal fade" id="cd_photo_viewer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark border-0" style="border-radius:.75rem;overflow:hidden;">
            <div class="modal-header border-0 py-2 px-4 bg-dark">
                <span class="text-white fs-8 opacity-75" id="cd_viewer_counter"></span>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center position-relative" style="min-height:300px;">
                <button class="btn btn-icon btn-dark border-0 position-absolute start-0 top-50 translate-middle-y ms-2"
                        id="cd_viewer_prev" style="z-index:10;opacity:.8;">
                    <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                </button>
                <img id="cd_viewer_img" src="" alt="" style="max-height:80vh;max-width:100%;object-fit:contain;" />
                <button class="btn btn-icon btn-dark border-0 position-absolute end-0 top-50 translate-middle-y me-2"
                        id="cd_viewer_next" style="z-index:10;opacity:.8;">
                    <i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Photo Viewer Modal-->

<style>
.sd-photo-grid { display:grid; gap:3px; margin-top:.75rem; border-radius:.5rem; overflow:hidden; }
.sd-photo-grid-1 { grid-template-columns:1fr; }
.sd-photo-grid-2 { grid-template-columns:repeat(2,1fr); }
.sd-photo-grid-3 { grid-template-columns:repeat(3,1fr); }
.sd-photo-grid-4 { grid-template-columns:repeat(4,1fr); }
.sd-photo-item   { position:relative; aspect-ratio:1; cursor:pointer; overflow:hidden; }
.sd-photo-grid-1 .sd-photo-item { aspect-ratio:unset; max-height:480px; }
.sd-photo-grid-1 .sd-photo-thumb { height:auto; max-height:480px; object-fit:cover; }
.sd-photo-thumb  { width:100%; height:100%; object-fit:cover; display:block; transition:opacity .15s; }
.sd-photo-item:hover .sd-photo-thumb { opacity:.88; }
.sd-photo-more-overlay { position:absolute;inset:0;background:rgba(0,0,0,.55);display:flex;align-items:center;justify-content:center; }
.sd-photo-more-overlay span { color:#fff;font-size:1.2rem;font-weight:700; }
.sd-post-card { transition:box-shadow .2s; }
.sd-post-card:hover { box-shadow:0 4px 20px rgba(0,0,0,.08) !important; }
.sd-comment { padding:.25rem 0; }
.sd-post-react.sd-reacted,.sd-comment-react.sd-reacted,.sd-reply-react.sd-reacted { font-weight:700 !important; }
.sd-reply .sd-mention,.sd-comment .sd-mention { color:#3b82f6;font-weight:600; }
.sd-preview-wrap { position:relative;width:76px;height:76px; }
.sd-preview-wrap img { width:76px;height:76px;object-fit:cover;border-radius:6px;display:block; }
.sd-preview-remove { position:absolute;top:2px;right:2px;width:18px;height:18px;background:#ef4444;border:none;border-radius:50%;color:#fff;font-size:10px;line-height:18px;text-align:center;cursor:pointer;padding:0; }
</style>

<script>
// Photo viewer (shared, one instance per page)
let cdViewerPhotos = [], cdViewerIdx = 0;
function cdOpenViewer(el) {
    cdViewerPhotos = JSON.parse(el.dataset.photos || '[]');
    cdViewerIdx    = parseInt(el.dataset.index || '0');
    cdShowViewerPhoto();
    new bootstrap.Modal(document.getElementById('cd_photo_viewer')).show();
}
function cdShowViewerPhoto() {
    const path = cdViewerPhotos[cdViewerIdx];
    const src  = path && path.startsWith('http') ? path : ('<?= base_url('uploads/class_discussion/') ?>' + path);
    document.getElementById('cd_viewer_img').src             = src;
    document.getElementById('cd_viewer_counter').textContent = (cdViewerIdx+1) + ' / ' + cdViewerPhotos.length;
    document.getElementById('cd_viewer_prev').style.display  = cdViewerPhotos.length > 1 ? '' : 'none';
    document.getElementById('cd_viewer_next').style.display  = cdViewerPhotos.length > 1 ? '' : 'none';
}
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('cd_viewer_prev').addEventListener('click', function() {
        cdViewerIdx = (cdViewerIdx - 1 + cdViewerPhotos.length) % cdViewerPhotos.length;
        cdShowViewerPhoto();
    });
    document.getElementById('cd_viewer_next').addEventListener('click', function() {
        cdViewerIdx = (cdViewerIdx + 1) % cdViewerPhotos.length;
        cdShowViewerPhoto();
    });
});
</script>
