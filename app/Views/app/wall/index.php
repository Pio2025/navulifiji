<?php
$schId        = $schId        ?? 0;
$myId         = $myId         ?? 0;
$myName       = $myName       ?? 'Me';
$myPhoto      = $myPhoto      ?? base_url('assets/media/avatars/blank.png');
$schoolName   = $schoolName   ?? 'School Community';
$schoolMotto  = $schoolMotto  ?? '';
$schoolLogo   = $schoolLogo   ?? '';
$wallPostCount   = $wallPostCount   ?? 0;
$wallMemberCount = $wallMemberCount ?? 0;

$WALL_FEED_URL          = base_url('wall/feed');
$WALL_POST_URL          = base_url('wall/post');
$WALL_DELETE_POST_BASE  = base_url('wall/post/');       // + postId + '/delete'
$WALL_COMMENT_BASE      = base_url('wall/post/');       // + postId + '/comments' or /comment
$WALL_DELETE_CMT_BASE   = base_url('wall/comment/');    // + commentId + '/delete'
$WALL_REACT_URL         = base_url('wall/react');
?>
<style>
/* ── Wall layout ───────────────────────────────────── */
.wall-outer { display: grid; grid-template-columns: minmax(0,1fr) 300px; gap: 1.5rem; max-width: 1060px; margin: 0 auto; padding: 1.5rem 1rem 3rem; align-items: start; }
.wall-feed-col { min-width: 0; }
.wall-sidebar { position: sticky; top: 80px; display: flex; flex-direction: column; gap: 1rem; }
@media (max-width: 860px) {
    .wall-outer { grid-template-columns: 1fr; }
    .wall-sidebar { position: static; }
}
/* sidebar cards */
.wsb-card { background: #fff; border-radius: 14px; border: 1px solid #e9edf0; box-shadow: 0 1px 6px rgba(0,0,0,.06); overflow: hidden; }
.wsb-head { display: flex; align-items: center; gap: .5rem; padding: .85rem 1rem; border-bottom: 1px solid #f1f3f5; font-weight: 700; font-size: .88rem; color: #181c32; }
.wsb-stat { text-align: center; flex: 1; }
.wsb-stat .num { font-size: 1.4rem; font-weight: 800; line-height: 1; }
.wsb-stat .lbl { font-size: .74rem; color: #a1a5b7; margin-top: .15rem; }
.wsb-link { display: flex; align-items: center; gap: .65rem; padding: .6rem 1rem; color: #3f4254; font-size: .86rem; text-decoration: none; border-radius: 8px; transition: background .15s; }
.wsb-link:hover { background: #f5f8fa; color: #0095e8; text-decoration: none; }
.wsb-link i { font-size: 1.1rem; flex-shrink: 0; }
.wsb-rule { display: flex; align-items: flex-start; gap: .5rem; padding: .45rem 0; font-size: .83rem; color: #5e6278; border-bottom: 1px solid #f5f5f5; }
.wsb-rule:last-child { border-bottom: none; }
.wsb-rule .dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
.wall-composer, .wall-post-card { background: #fff; border-radius: 14px; border: 1px solid #e9edf0; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 1.25rem; }
.wall-composer { padding: 1.25rem; }
.composer-top { display: flex; gap: .75rem; align-items: flex-start; }
.composer-avatar { width: 42px; height: 42px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
#composer-text { border: none; outline: none; resize: none; width: 100%; min-height: 72px; font-size: .95rem; color: #3f4254; background: #f5f8fa; border-radius: 10px; padding: .75rem 1rem; }
.composer-toolbar { display: flex; align-items: center; gap: .5rem; margin-top: .75rem; flex-wrap: wrap; }
.composer-toolbar .btn { border-radius: 20px; font-size: .82rem; padding: .35rem .85rem; }
.composer-media-preview { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .75rem; }
.cmp-thumb { position: relative; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; background: #eef1f5; flex-shrink: 0; }
.cmp-thumb img { width: 100%; height: 100%; object-fit: cover; }
.cmp-thumb .rm-btn { position: absolute; top: 2px; right: 2px; background: rgba(0,0,0,.55); border: none; color: #fff; border-radius: 50%; width: 20px; height: 20px; font-size: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
.cmp-file-chip { display: flex; align-items: center; gap: .4rem; background: #f1faff; border: 1px solid #b8d9ef; border-radius: 20px; padding: .3rem .75rem; font-size: .8rem; color: #0095e8; }
/* Post card */
.post-header { display: flex; align-items: center; gap: .75rem; padding: 1rem 1.25rem .5rem; }
.post-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
.post-meta .author { font-weight: 600; font-size: .92rem; color: #181c32; }
.post-meta .age { font-size: .78rem; color: #a1a5b7; }
.post-content { padding: .5rem 1.25rem; font-size: .92rem; color: #3f4254; white-space: pre-wrap; word-break: break-word; }
.post-media-grid { display: grid; gap: 3px; padding: 0 1.25rem .75rem; }
.post-media-grid.count-1 { grid-template-columns: 1fr; }
.post-media-grid.count-2 { grid-template-columns: 1fr 1fr; }
.post-media-grid.count-3 { grid-template-columns: 1fr 1fr; }
.post-media-grid.count-3 .media-item:first-child { grid-column: 1 / -1; }
.post-media-grid.count-4, .post-media-grid.count-many { grid-template-columns: 1fr 1fr; }
.media-item { border-radius: 8px; overflow: hidden; max-height: 320px; cursor: pointer; background: #f1f3f5; position: relative; }
.media-item img { width: 100%; height: 100%; object-fit: cover; display: block; }
.media-item .video-thumb { width: 100%; height: 200px; background: #1a1a2e; display: flex; align-items: center; justify-content: center; }
.media-item .video-thumb i { font-size: 3rem; color: rgba(255,255,255,.7); }
.media-item .file-card { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .5rem; padding: 1.5rem; background: #f8f9fa; height: 100px; }
.media-item .file-card i { font-size: 2rem; color: #7239ea; }
.media-item .file-card span { font-size: .8rem; color: #6d6e7c; text-align: center; word-break: break-all; max-width: 160px; }
/* Reactions bar */
.post-reactions-bar, .comment-reactions-bar { display: flex; flex-wrap: wrap; gap: .3rem; padding: .25rem 0; }
.reaction-pill { display: inline-flex; align-items: center; gap: .25rem; background: #f1f3f5; border: 1.5px solid transparent; border-radius: 20px; padding: .15rem .6rem; font-size: .82rem; cursor: pointer; transition: border-color .15s, background .15s; user-select: none; }
.reaction-pill.my-reaction { background: #e8f3ff; border-color: #0095e8; }
.reaction-pill:hover { border-color: #b5d2f0; }
/* Emoji picker */
.emoji-picker-pop { display: none; position: absolute; z-index: 200; background: #fff; border: 1px solid #e4e6ef; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,.12); padding: .5rem .4rem; }
.emoji-picker-pop.show { display: flex; gap: .2rem; }
.ep-btn { background: none; border: none; font-size: 1.35rem; cursor: pointer; padding: .2rem .3rem; border-radius: 8px; transition: background .12s; line-height: 1; }
.ep-btn:hover { background: #f1f3f5; }
/* Post actions */
.post-actions { display: flex; gap: 0; border-top: 1px solid #f1f3f5; }
.post-action-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: .4rem; padding: .65rem; font-size: .84rem; color: #5e6278; background: none; border: none; cursor: pointer; transition: background .15s; border-radius: 0 0 8px 8px; }
.post-action-btn:first-child { border-radius: 0 0 0 14px; }
.post-action-btn:last-child { border-radius: 0 0 14px 0; }
.post-action-btn:hover { background: #f5f8fa; color: #0095e8; }
/* Comments section */
.comments-section { background: #f8f9fa; border-top: 1px solid #f1f3f5; border-radius: 0 0 14px 14px; padding: 0; overflow: hidden; }
.comments-inner { padding: 1rem 1.25rem; display: flex; flex-direction: column; gap: .75rem; }
.comment-item { display: flex; gap: .6rem; }
.comment-item.reply-item { margin-left: 2.5rem; }
.comment-avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.comment-bubble { flex: 1; min-width: 0; }
.comment-bubble .bubble-body { background: #fff; border-radius: 12px; padding: .5rem .8rem; border: 1px solid #e9edf0; }
.bubble-body .author { font-weight: 600; font-size: .82rem; color: #181c32; }
.bubble-body .text { font-size: .87rem; color: #3f4254; white-space: pre-wrap; word-break: break-word; margin-top: .1rem; }
.comment-meta { display: flex; align-items: center; gap: .75rem; margin-top: .3rem; padding-left: .4rem; flex-wrap: wrap; }
.comment-meta .age { font-size: .76rem; color: #a1a5b7; }
.comment-meta .act-link { font-size: .78rem; color: #5e6278; cursor: pointer; background: none; border: none; padding: 0; font-weight: 500; }
.comment-meta .act-link:hover { color: #0095e8; }
/* Reply composer */
.reply-composer { display: flex; gap: .5rem; align-items: flex-start; }
.reply-composer .comment-avatar { width: 28px; height: 28px; flex-shrink: 0; margin-top: 4px; }
.reply-input { flex: 1; background: #fff; border: 1px solid #d9dee5; border-radius: 20px; padding: .4rem 1rem; font-size: .85rem; outline: none; resize: none; min-height: 36px; max-height: 100px; overflow-y: auto; }
.reply-input:focus { border-color: #0095e8; }
.send-btn { background: none; border: none; color: #0095e8; font-size: 1.2rem; cursor: pointer; padding: .4rem; flex-shrink: 0; margin-top: 2px; }
.send-btn:hover { color: #0076c6; }
/* Comment input at bottom */
.comment-input-row { display: flex; gap: .5rem; padding: .75rem 1.25rem; align-items: flex-start; border-top: 1px solid #f1f3f5; }
.comment-input-row .comment-avatar { width: 32px; height: 32px; flex-shrink: 0; margin-top: 4px; }
.comment-textarea { flex: 1; border: 1px solid #d9dee5; border-radius: 20px; padding: .4rem 1rem; font-size: .85rem; outline: none; resize: none; min-height: 36px; max-height: 100px; overflow-y: auto; }
.comment-textarea:focus { border-color: #0095e8; }
/* Load more / spinner */
.feed-spinner { text-align: center; padding: 2rem; display: none; }
.empty-wall { text-align: center; padding: 4rem 1rem; }
.empty-wall i { font-size: 3.5rem; color: #e4e6ef; }
.empty-wall p { color: #a1a5b7; margin-top: .75rem; }
/* Post delete btn */
.post-del-btn { background: none; border: none; color: #a1a5b7; cursor: pointer; padding: .3rem; border-radius: 6px; font-size: .9rem; margin-left: auto; }
.post-del-btn:hover { color: #f1416c; background: #fff5f8; }
/* Video modal */
#wall-video-modal .modal-body { background: #000; padding: 0; }
#wall-video-modal iframe, #wall-video-modal video { width: 100%; min-height: 340px; border: none; }
/* Lightbox */
#wall-lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.88); z-index: 9999; align-items: center; justify-content: center; }
#wall-lightbox.open { display: flex; }
#wall-lightbox img { max-width: 92vw; max-height: 90vh; border-radius: 6px; object-fit: contain; }
#wall-lightbox .lb-close { position: fixed; top: 1rem; right: 1.5rem; color: #fff; font-size: 2rem; cursor: pointer; z-index: 10000; background: none; border: none; }
</style>

<!-- Wall outer grid -->
<div class="wall-outer">

    <!-- ── Left: feed column ── -->
    <div class="wall-feed-col">

        <!-- Page header -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="ki-duotone ki-abstract-26 fs-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-gray-900">School Wall</h4>
                <div class="text-muted fs-7">Share with everyone in your school</div>
            </div>
        </div>

        <!-- Composer -->
        <div class="wall-composer" id="wall-composer">
            <div class="composer-top">
                <img src="<?= $myPhoto ?>" class="composer-avatar" alt="">
                <textarea id="composer-text" placeholder="What's on your mind?"></textarea>
            </div>
            <div id="composer-media-preview" class="composer-media-preview"></div>
            <div id="composer-video-urls"></div>
            <div class="composer-toolbar">
                <label class="btn btn-light-primary btn-sm" for="composer-image-input">
                    <i class="ki-duotone ki-picture fs-4"><span class="path1"></span><span class="path2"></span></i>
                    Photo
                    <input type="file" id="composer-image-input" accept="image/*" multiple style="display:none;">
                </label>
                <button class="btn btn-light-success btn-sm" id="composer-video-btn">
                    <i class="ki-duotone ki-youtube fs-4"><span class="path1"></span><span class="path2"></span></i>
                    Video URL
                </button>
                <label class="btn btn-light-warning btn-sm" for="composer-file-input">
                    <i class="ki-duotone ki-paper-clip fs-4"><span class="path1"></span><span class="path2"></span></i>
                    File
                    <input type="file" id="composer-file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" multiple style="display:none;">
                </label>
                <button class="btn btn-primary btn-sm ms-auto" id="composer-post-btn">Post</button>
            </div>
        </div>

        <!-- Feed -->
        <div id="wall-feed"></div>
        <div class="feed-spinner" id="feed-spinner">
            <span class="spinner-border spinner-border-sm text-primary"></span>
            <div class="text-muted fs-8 mt-2">Loading...</div>
        </div>
        <div id="load-more-wrap" class="text-center mb-4" style="display:none;">
            <button class="btn btn-light btn-sm" id="load-more-btn">Load more</button>
        </div>

    </div><!-- /wall-feed-col -->

    <!-- ── Right: sidebar ── -->
    <aside class="wall-sidebar">

        <!-- Community card -->
        <div class="wsb-card">
            <?php if ($schoolLogo): ?>
            <div style="background:linear-gradient(135deg,#0095e8 0%,#1b5fc1 100%);padding:1.25rem 1rem .75rem;text-align:center;">
                <img src="<?= esc($schoolLogo) ?>" alt="School logo" style="height:56px;object-fit:contain;filter:drop-shadow(0 2px 6px rgba(0,0,0,.25));">
            </div>
            <?php else: ?>
            <div style="background:linear-gradient(135deg,#0095e8 0%,#1b5fc1 100%);padding:1.5rem 1rem .75rem;text-align:center;">
                <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                    <i class="ki-duotone ki-abstract-26 fs-2 text-white"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <?php endif; ?>
            <div class="p-3 text-center border-bottom" style="border-color:#f1f3f5!important;">
                <div class="fw-bold text-gray-800 fs-6 mb-1"><?= esc($schoolName) ?></div>
                <?php if ($schoolMotto): ?>
                <div class="text-muted fs-8 fst-italic">"<?= esc($schoolMotto) ?>"</div>
                <?php endif; ?>
            </div>
            <div class="d-flex p-3" style="gap:.5rem;">
                <div class="wsb-stat">
                    <div class="num text-primary"><?= number_format($wallPostCount) ?></div>
                    <div class="lbl">Wall Posts</div>
                </div>
                <div style="width:1px;background:#f1f3f5;flex-shrink:0;"></div>
                <div class="wsb-stat">
                    <div class="num text-success"><?= number_format($wallMemberCount) ?></div>
                    <div class="lbl">Students</div>
                </div>
            </div>
        </div>

        <!-- Quick links card -->
        <div class="wsb-card">
            <div class="wsb-head">
                <i class="ki-duotone ki-compass fs-5 text-primary"><span class="path1"></span><span class="path2"></span></i>
                Quick Navigation
            </div>
            <div class="p-2">
                <a href="<?= base_url('dashboard') ?>" class="wsb-link">
                    <i class="ki-duotone ki-home-2 text-primary"><span class="path1"></span><span class="path2"></span></i>
                    Dashboard
                </a>
                <a href="<?= base_url('attendance') ?>" class="wsb-link">
                    <i class="ki-duotone ki-calendar-tick text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                    Attendance
                </a>
                <a href="<?= base_url('timetable') ?>" class="wsb-link">
                    <i class="ki-duotone ki-time text-warning"><span class="path1"></span><span class="path2"></span></i>
                    Timetable
                </a>
                <a href="<?= base_url('exam') ?>" class="wsb-link">
                    <i class="ki-duotone ki-award text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Exams &amp; Grades
                </a>
                <a href="<?= base_url('announcement') ?>" class="wsb-link">
                    <i class="ki-duotone ki-notification text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Announcements
                </a>
                <a href="<?= base_url('chat') ?>" class="wsb-link">
                    <i class="ki-duotone ki-message-text-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                    Messages
                </a>
            </div>
        </div>

        <!-- Community guidelines card -->
        <div class="wsb-card">
            <div class="wsb-head">
                <i class="ki-duotone ki-shield-tick fs-5 text-success"><span class="path1"></span><span class="path2"></span></i>
                Community Guidelines
            </div>
            <div class="px-3 py-2">
                <div class="wsb-rule">
                    <span class="dot bg-success"></span>
                    Be kind, respectful and supportive
                </div>
                <div class="wsb-rule">
                    <span class="dot bg-primary"></span>
                    Share academic work and achievements
                </div>
                <div class="wsb-rule">
                    <span class="dot bg-warning"></span>
                    No bullying, hate speech or harassment
                </div>
                <div class="wsb-rule">
                    <span class="dot bg-info"></span>
                    Keep personal information private
                </div>
                <div class="wsb-rule">
                    <span class="dot bg-danger"></span>
                    No inappropriate or offensive content
                </div>
                <div class="wsb-rule">
                    <span class="dot bg-success"></span>
                    Celebrate each other's successes
                </div>
            </div>
        </div>

        <!-- Active posters — populated by JS as feed loads -->
        <div class="wsb-card" id="wsb-active-card" style="display:none;">
            <div class="wsb-head">
                <i class="ki-duotone ki-people fs-5 text-warning"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                Recent Posters
            </div>
            <div id="wsb-active-list" class="p-2"></div>
        </div>

    </aside><!-- /wall-sidebar -->

</div><!-- /wall-outer -->

<!-- Lightbox -->
<div id="wall-lightbox">
    <button class="lb-close" id="lb-close">&times;</button>
    <img id="lb-img" src="" alt="">
</div>

<!-- Video modal -->
<div class="modal fade" id="wall-video-modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="video-modal-body"></div>
        </div>
    </div>
</div>

<script>
(function () {
'use strict';

const MY_ID         = <?= json_encode($myId) ?>;
const MY_PHOTO      = <?= json_encode($myPhoto) ?>;
const MY_NAME       = <?= json_encode($myName) ?>;
const FEED_URL      = <?= json_encode($WALL_FEED_URL) ?>;
const POST_URL      = <?= json_encode($WALL_POST_URL) ?>;
const DEL_POST_BASE = <?= json_encode($WALL_DELETE_POST_BASE) ?>;
const CMT_BASE      = <?= json_encode($WALL_COMMENT_BASE) ?>;
const DEL_CMT_BASE  = <?= json_encode($WALL_DELETE_CMT_BASE) ?>;
const REACT_URL     = <?= json_encode($WALL_REACT_URL) ?>;
const EMOJIS        = ['👍','❤️','😂','😮','😢','😡'];
const CI_TOKEN      = '<?= csrf_hash() ?>';
const CI_NAME       = '<?= csrf_token() ?>';

let feedOffset = 0;
let feedLoading = false;
let feedHasMore = true;
// Pending image files and video URLs for the composer
let composerImages = [];   // {file, url}
let composerFiles  = [];   // {file}
let composerVideos = [];   // string[]

// ─── CSRF helper ─────────────────────────────────────────────────────────────
function csrfField() { return {[CI_NAME]: CI_TOKEN}; }
function formWithCsrf(fd) { fd.append(CI_NAME, CI_TOKEN); return fd; }

// ─── POST request helper ──────────────────────────────────────────────────────
async function postJSON(url, body) {
    const fd = new FormData();
    Object.entries({...csrfField(), ...body}).forEach(([k,v]) => fd.append(k, v));
    const r = await fetch(url, {method:'POST', body: fd});
    return r.json();
}
async function postForm(url, fd) {
    formWithCsrf(fd);
    const r = await fetch(url, {method:'POST', body: fd});
    return r.json();
}

// ─── Relative time ────────────────────────────────────────────────────────────
function age(dt) {
    const s = Math.floor((Date.now() - new Date(dt)) / 1000);
    if (s < 60)    return 'Just now';
    if (s < 3600)  return Math.floor(s/60)+'m ago';
    if (s < 86400) return Math.floor(s/3600)+'h ago';
    if (s < 604800) return Math.floor(s/86400)+'d ago';
    return new Date(dt).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'});
}

// ─── Render helpers ───────────────────────────────────────────────────────────
function avatar(photo, name, size=40) {
    return `<img src="${photo}" class="rounded-circle" style="width:${size}px;height:${size}px;object-fit:cover;" alt="${name}">`;
}

function renderMedia(media) {
    if (!media || !media.length) return '';
    const cls = media.length === 1 ? 'count-1' : media.length === 2 ? 'count-2' : media.length === 3 ? 'count-3' : media.length === 4 ? 'count-4' : 'count-many';
    const items = media.map(m => {
        if (m.media_type === 'image') {
            return `<div class="media-item" data-type="image" data-src="${m.file_src}"><img src="${m.file_src}" alt="" loading="lazy"></div>`;
        }
        if (m.media_type === 'video_url') {
            const thumb = embedUrl(m.file_src);
            return `<div class="media-item" data-type="video" data-src="${m.file_src}">
                <div class="video-thumb"><i class="ki-duotone ki-youtube text-danger fs-2x"><span class="path1"></span><span class="path2"></span></i></div>
            </div>`;
        }
        // file
        return `<div class="media-item" data-type="file" data-src="${m.file_src}">
            <div class="file-card">
                <i class="ki-duotone ki-file-down fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                <span>${m.file_name}</span>
            </div>
        </div>`;
    }).join('');
    return `<div class="post-media-grid ${cls}">${items}</div>`;
}

function embedUrl(url) {
    // YouTube
    let m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    if (m) return `https://www.youtube.com/embed/${m[1]}`;
    return url;
}

function renderReactions(reactions, targetType, targetId) {
    const {summary, my_emoji} = reactions;
    let pills = '';
    for (const [emoji, cnt] of Object.entries(summary || {})) {
        const isMine = emoji === my_emoji ? 'my-reaction' : '';
        pills += `<button class="reaction-pill ${isMine}" data-target-type="${targetType}" data-target-id="${targetId}" data-emoji="${emoji}">${emoji} <span>${cnt}</span></button>`;
    }
    const addBtn = `<div class="position-relative d-inline-block">
        <button class="reaction-pill add-reaction-btn" data-target-type="${targetType}" data-target-id="${targetId}" title="React">
            <i class="ki-duotone ki-smile fs-6"><span class="path1"></span><span class="path2"></span></i>
        </button>
        <div class="emoji-picker-pop" id="ep-${targetType}-${targetId}">${EMOJIS.map(e=>`<button class="ep-btn" data-emoji="${e}" data-target-type="${targetType}" data-target-id="${targetId}">${e}</button>`).join('')}</div>
    </div>`;
    return `<div class="post-reactions-bar" id="reactions-${targetType}-${targetId}">${pills}${addBtn}</div>`;
}

function renderPost(p) {
    const delBtn = p.is_mine
        ? `<button class="post-del-btn" data-post-id="${p.wall_post_id}" title="Delete"><i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>`
        : '';
    return `<div class="wall-post-card" id="post-${p.wall_post_id}" data-post-id="${p.wall_post_id}">
        <div class="post-header">
            ${avatar(p.author_photo, p.author_name, 40)}
            <div class="post-meta">
                <div class="author">${esc(p.author_name)}</div>
                <div class="age">${p.age}</div>
            </div>
            ${delBtn}
        </div>
        ${p.content ? `<div class="post-content">${esc(p.content)}</div>` : ''}
        ${renderMedia(p.media)}
        <div class="px-4 pb-1">${renderReactions(p.reactions, 'post', p.wall_post_id)}</div>
        <div class="post-actions">
            <button class="post-action-btn toggle-comments-btn" data-post-id="${p.wall_post_id}">
                <i class="ki-duotone ki-message-text-2 fs-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                <span class="cmt-count">${p.comment_count}</span> Comment${p.comment_count!==1?'s':''}
            </button>
        </div>
        <div class="comments-section" id="comments-${p.wall_post_id}" style="display:none;"></div>
    </div>`;
}

function renderCommentTree(comments, postId) {
    const roots  = comments.filter(c => !c.parent_comment_id);
    const byParent = {};
    comments.filter(c => c.parent_comment_id).forEach(c => {
        (byParent[c.parent_comment_id] = byParent[c.parent_comment_id] || []).push(c);
    });

    function renderC(c, isReply=false) {
        const delBtn = c.is_mine ? `<button class="act-link text-danger del-comment-btn" data-comment-id="${c.wall_comment_id}">Delete</button>` : '';
        const replyBtn = !isReply ? `<button class="act-link reply-btn" data-post-id="${postId}" data-parent-id="${c.wall_comment_id}" data-author="${esc(c.author_name)}">Reply</button>` : '';
        const reactionBar = renderReactions(c.reactions, 'comment', c.wall_comment_id);
        const replies = (byParent[c.wall_comment_id] || []).map(r => renderC(r, true)).join('');
        return `<div class="comment-item${isReply?' reply-item':''}" id="comment-${c.wall_comment_id}">
            ${avatar(c.author_photo, c.author_name, 32)}
            <div class="comment-bubble">
                <div class="bubble-body">
                    <div class="author">${esc(c.author_name)}</div>
                    <div class="text">${esc(c.content)}</div>
                </div>
                <div class="comment-meta">
                    <span class="age">${c.age}</span>
                    ${reactionBar}
                    ${replyBtn}
                    ${delBtn}
                </div>
                ${replies}
                ${!isReply ? `<div class="reply-area-${c.wall_comment_id}"></div>` : ''}
            </div>
        </div>`;
    }

    const commentInput = `<div class="comment-input-row">
        ${avatar(MY_PHOTO, MY_NAME, 32)}
        <textarea class="comment-textarea" placeholder="Write a comment..." data-post-id="${postId}" rows="1"></textarea>
        <button class="send-btn submit-comment-btn" data-post-id="${postId}"><i class="ki-duotone ki-send fs-3"><span class="path1"></span><span class="path2"></span></i></button>
    </div>`;

    return `<div class="comments-inner">${roots.map(c=>renderC(c,false)).join('')}</div>${commentInput}`;
}

function esc(s) {
    if (!s) return '';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ─── Feed loading ──────────────────────────────────────────────────────────────
async function loadFeed(reset=false) {
    if (feedLoading) return;
    if (!feedHasMore && !reset) return;
    feedLoading = true;
    document.getElementById('feed-spinner').style.display = 'block';
    document.getElementById('load-more-wrap').style.display = 'none';

    if (reset) { feedOffset = 0; feedHasMore = true; }

    try {
        const r = await fetch(`${FEED_URL}?offset=${feedOffset}`);
        const data = await r.json();
        const feed = document.getElementById('wall-feed');

        if (reset) feed.innerHTML = '';

        if (data.posts.length === 0 && feedOffset === 0) {
            feed.innerHTML = `<div class="empty-wall"><i class="ki-duotone ki-abstract-26 fs-3x"><span class="path1"></span><span class="path2"></span></i><p>No posts yet. Be the first to share something!</p></div>`;
        } else {
            data.posts.forEach(p => feed.insertAdjacentHTML('beforeend', renderPost(p)));
            feedOffset += data.posts.length;
            feedHasMore = data.has_more;
            document.getElementById('load-more-wrap').style.display = feedHasMore ? 'block' : 'none';
            updateActivePosters(data.posts);
        }
    } catch(e) { console.error(e); }

    feedLoading = false;
    document.getElementById('feed-spinner').style.display = 'none';
}

// ─── Composer ─────────────────────────────────────────────────────────────────
document.getElementById('composer-image-input').addEventListener('change', function() {
    [...this.files].forEach(f => {
        composerImages.push({file: f, url: URL.createObjectURL(f)});
    });
    this.value = '';
    renderComposerPreview();
});

document.getElementById('composer-file-input').addEventListener('change', function() {
    [...this.files].forEach(f => composerFiles.push({file: f}));
    this.value = '';
    renderComposerPreview();
});

document.getElementById('composer-video-btn').addEventListener('click', () => {
    Swal.fire({
        title: 'Add Video URL',
        input: 'url',
        inputPlaceholder: 'https://www.youtube.com/watch?v=...',
        showCancelButton: true,
        confirmButtonText: 'Add',
    }).then(r => {
        if (r.isConfirmed && r.value) {
            composerVideos.push(r.value.trim());
            renderComposerPreview();
        }
    });
});

function renderComposerPreview() {
    const imgWrap = document.getElementById('composer-media-preview');
    const vidWrap = document.getElementById('composer-video-urls');

    imgWrap.innerHTML = composerImages.map((item, i) =>
        `<div class="cmp-thumb"><img src="${item.url}" alt=""><button class="rm-btn" data-type="image" data-idx="${i}">&times;</button></div>`
    ).join('') + composerFiles.map((item, i) =>
        `<div class="cmp-file-chip"><i class="ki-duotone ki-paper-clip fs-6"><span class="path1"></span><span class="path2"></span></i>${esc(item.file.name)}<button class="rm-btn" data-type="file" data-idx="${i}" style="background:rgba(0,0,0,.25);">&times;</button></div>`
    ).join('');

    vidWrap.innerHTML = composerVideos.map((url, i) =>
        `<div class="d-flex align-items-center gap-2 mt-1 fs-8 text-success"><i class="ki-duotone ki-youtube fs-5"><span class="path1"></span><span class="path2"></span></i>${esc(url)}<button class="rm-btn" data-type="video" data-idx="${i}" style="background:rgba(0,0,0,.25);position:static;">&times;</button></div>`
    ).join('');

    // Remove buttons
    [...imgWrap.querySelectorAll('.rm-btn'), ...vidWrap.querySelectorAll('.rm-btn')].forEach(btn => {
        btn.addEventListener('click', e => {
            const t = btn.dataset.type;
            const idx = parseInt(btn.dataset.idx);
            if (t === 'image') composerImages.splice(idx, 1);
            else if (t === 'file') composerFiles.splice(idx, 1);
            else if (t === 'video') composerVideos.splice(idx, 1);
            renderComposerPreview();
        });
    });
}

document.getElementById('composer-post-btn').addEventListener('click', async () => {
    const content = document.getElementById('composer-text').value.trim();
    if (!content && !composerImages.length && !composerFiles.length && !composerVideos.length) {
        Swal.fire({icon:'warning', title:'Empty post', text:'Please add some content or media.', confirmButtonText:'OK'});
        return;
    }

    const btn = document.getElementById('composer-post-btn');
    btn.disabled = true; btn.textContent = 'Posting...';

    const fd = new FormData();
    fd.append('content', content);
    composerImages.forEach(item => fd.append('media[]', item.file));
    composerFiles.forEach(item => fd.append('media[]', item.file));
    composerVideos.forEach(url => fd.append('video_urls[]', url));

    try {
        const data = await postForm(POST_URL, fd);
        if (data.success) {
            document.getElementById('composer-text').value = '';
            composerImages = []; composerFiles = []; composerVideos = [];
            renderComposerPreview();
            const feed = document.getElementById('wall-feed');
            const emptyEl = feed.querySelector('.empty-wall');
            if (emptyEl) emptyEl.remove();
            feed.insertAdjacentHTML('afterbegin', renderPost(data.post));
        } else {
            Swal.fire({icon:'error', title:'Error', text: data.error || 'Failed to post.'});
        }
    } catch(e) {
        Swal.fire({icon:'error', title:'Error', text:'Could not connect to server.'});
    }

    btn.disabled = false; btn.textContent = 'Post';
});

// ─── Feed interactions ─────────────────────────────────────────────────────────
document.getElementById('wall-feed').addEventListener('click', async function(e) {
    const target = e.target.closest('[data-post-id][class*="del-post"], .post-del-btn, .toggle-comments-btn, .media-item, .reaction-pill, .add-reaction-btn, .ep-btn, .submit-comment-btn, .reply-btn, .del-comment-btn');
    if (!target) return;

    // Delete post
    if (target.classList.contains('post-del-btn')) {
        const pid = parseInt(target.dataset.postId);
        const c = await Swal.fire({icon:'warning', title:'Delete post?', text:'This cannot be undone.', showCancelButton:true, confirmButtonColor:'#f1416c', confirmButtonText:'Delete'});
        if (!c.isConfirmed) return;
        const data = await postJSON(`${DEL_POST_BASE}${pid}/delete`, {});
        if (data.success) document.getElementById(`post-${pid}`)?.remove();
        else Swal.fire({icon:'error', title:'Error', text: data.error||'Failed.'});
        return;
    }

    // Toggle comments
    if (target.classList.contains('toggle-comments-btn')) {
        const pid = parseInt(target.dataset.postId);
        const sec = document.getElementById(`comments-${pid}`);
        if (!sec) return;
        if (sec.style.display === 'none') {
            sec.style.display = 'block';
            if (!sec.dataset.loaded) {
                sec.innerHTML = '<div class="text-center py-3"><span class="spinner-border spinner-border-sm text-primary"></span></div>';
                await loadComments(pid, sec);
                sec.dataset.loaded = '1';
            }
        } else {
            sec.style.display = 'none';
        }
        return;
    }

    // Media click
    if (target.classList.contains('media-item')) {
        const type = target.dataset.type;
        const src  = target.dataset.src;
        if (type === 'image') {
            document.getElementById('lb-img').src = src;
            document.getElementById('wall-lightbox').classList.add('open');
        } else if (type === 'video') {
            document.getElementById('video-modal-body').innerHTML =
                `<iframe src="${embedUrl(src)}" allowfullscreen></iframe>`;
            new bootstrap.Modal(document.getElementById('wall-video-modal')).show();
        } else {
            window.open(src, '_blank');
        }
        return;
    }

    // Emoji picker pill
    if (target.classList.contains('reaction-pill') && !target.classList.contains('add-reaction-btn')) {
        await doReact(target.dataset.targetType, parseInt(target.dataset.targetId), target.dataset.emoji);
        return;
    }

    // Open emoji picker
    if (target.classList.contains('add-reaction-btn')) {
        const tt = target.dataset.targetType, tid = parseInt(target.dataset.targetId);
        document.querySelectorAll('.emoji-picker-pop.show').forEach(p => { if (p.id !== `ep-${tt}-${tid}`) p.classList.remove('show'); });
        document.getElementById(`ep-${tt}-${tid}`)?.classList.toggle('show');
        return;
    }

    // Emoji picker button
    if (target.classList.contains('ep-btn')) {
        const tt = target.dataset.targetType, tid = parseInt(target.dataset.targetId);
        document.getElementById(`ep-${tt}-${tid}`)?.classList.remove('show');
        await doReact(tt, tid, target.dataset.emoji);
        return;
    }

    // Submit comment
    if (target.classList.contains('submit-comment-btn')) {
        const pid = parseInt(target.dataset.postId);
        const sec = document.getElementById(`comments-${pid}`);
        const ta  = sec?.querySelector(`.comment-textarea[data-post-id="${pid}"]`);
        if (!ta) return;
        await submitComment(pid, ta.value.trim(), null, ta);
        return;
    }

    // Reply button
    if (target.classList.contains('reply-btn')) {
        const pid   = parseInt(target.dataset.postId);
        const parid = parseInt(target.dataset.parentId);
        const auth  = target.dataset.author;
        const area  = document.querySelector(`.reply-area-${parid}`);
        if (!area) return;
        if (area.querySelector('.reply-composer')) { area.innerHTML = ''; return; }
        area.innerHTML = `<div class="reply-composer mt-2">
            ${avatar(MY_PHOTO, MY_NAME, 28)}
            <textarea class="reply-input" placeholder="Reply to ${auth}..." rows="1"></textarea>
            <button class="send-btn reply-send-btn" data-post-id="${pid}" data-parent-id="${parid}"><i class="ki-duotone ki-send fs-4"><span class="path1"></span><span class="path2"></span></i></button>
        </div>`;
        area.querySelector('.reply-input')?.focus();
        area.querySelector('.reply-send-btn')?.addEventListener('click', async function() {
            const ta = area.querySelector('.reply-input');
            await submitComment(pid, ta.value.trim(), parid, ta);
            area.innerHTML = '';
        });
        return;
    }

    // Delete comment
    if (target.classList.contains('del-comment-btn')) {
        const cid = parseInt(target.dataset.commentId);
        const c = await Swal.fire({icon:'warning', title:'Delete comment?', showCancelButton:true, confirmButtonColor:'#f1416c', confirmButtonText:'Delete'});
        if (!c.isConfirmed) return;
        const data = await postJSON(`${DEL_CMT_BASE}${cid}/delete`, {});
        if (data.success) document.getElementById(`comment-${cid}`)?.remove();
        else Swal.fire({icon:'error', title:'Error', text: data.error||'Failed.'});
        return;
    }
});

async function loadComments(postId, sec) {
    const r = await fetch(`${CMT_BASE}${postId}/comments`);
    const data = await r.json();
    sec.innerHTML = renderCommentTree(data.comments || [], postId);
    // auto-resize textareas
    sec.querySelectorAll('textarea').forEach(ta => {
        ta.addEventListener('input', () => { ta.style.height='auto'; ta.style.height=ta.scrollHeight+'px'; });
        ta.addEventListener('keydown', e => {
            if (e.key==='Enter' && !e.shiftKey) {
                e.preventDefault();
                const btn = ta.closest('.comment-input-row')?.querySelector('.submit-comment-btn')
                         || ta.closest('.reply-composer')?.querySelector('.reply-send-btn');
                btn?.click();
            }
        });
    });
}

async function submitComment(postId, content, parentId, ta) {
    if (!content) return;
    const fd = new FormData();
    fd.append('content', content);
    if (parentId) fd.append('parent_comment_id', parentId);
    const data = await postForm(`${CMT_BASE}${postId}/comment`, fd);
    if (data.success) {
        // Reload comments
        const sec = document.getElementById(`comments-${postId}`);
        if (sec) { await loadComments(postId, sec); }
        // Update comment count badge
        const countEl = document.querySelector(`#post-${postId} .cmt-count`);
        if (countEl) countEl.textContent = parseInt(countEl.textContent||'0') + 1;
        if (ta) ta.value = '';
    } else {
        Swal.fire({icon:'error', title:'Error', text: data.error||'Failed.'});
    }
}

async function doReact(targetType, targetId, emoji) {
    const data = await postJSON(REACT_URL, {target_type: targetType, target_id: targetId, emoji});
    if (!data.action) return;
    // Re-render reaction bar
    const bar = document.getElementById(`reactions-${targetType}-${targetId}`);
    if (bar) {
        const fakeReactions = {summary: data.summary, my_emoji: data.my_emoji};
        bar.outerHTML = renderReactions(fakeReactions, targetType, targetId);
    }
    // Update reaction count on post if it's a post
    if (targetType === 'post') {
        const countSpan = document.querySelector(`#post-${targetId} .reaction-count`);
        if (countSpan) {
            const total = Object.values(data.summary||{}).reduce((a,b)=>a+b,0);
            countSpan.textContent = total;
        }
    }
}

// Emoji picker: close on outside click
document.addEventListener('click', e => {
    if (!e.target.closest('.add-reaction-btn') && !e.target.closest('.emoji-picker-pop')) {
        document.querySelectorAll('.emoji-picker-pop.show').forEach(p => p.classList.remove('show'));
    }
});

// Lightbox close
document.getElementById('lb-close').addEventListener('click', () => document.getElementById('wall-lightbox').classList.remove('open'));
document.getElementById('wall-lightbox').addEventListener('click', e => {
    if (e.target === e.currentTarget) e.currentTarget.classList.remove('open');
});

// Video modal: stop video on close
document.getElementById('wall-video-modal').addEventListener('hidden.bs.modal', () => {
    document.getElementById('video-modal-body').innerHTML = '';
});

// Load more
document.getElementById('load-more-btn').addEventListener('click', () => loadFeed(false));

// Auto-resize composer textarea
(function() {
    const ta = document.getElementById('composer-text');
    ta.addEventListener('input', () => { ta.style.height='auto'; ta.style.height=ta.scrollHeight+'px'; });
})();

function embedUrl(url) {
    let m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    if (m) return `https://www.youtube.com/embed/${m[1]}`;
    m = url.match(/vimeo\.com\/(\d+)/);
    if (m) return `https://player.vimeo.com/video/${m[1]}`;
    return url;
}

// ── Recent Posters sidebar widget ────────────────────────────────────────────
const _seenPosters = new Map(); // userId → {name, photo}
function updateActivePosters(posts) {
    posts.forEach(p => {
        if (!_seenPosters.has(p.user_id_fk)) {
            _seenPosters.set(p.user_id_fk, {name: p.author_name, photo: p.author_photo});
        }
    });
    const card = document.getElementById('wsb-active-card');
    const list = document.getElementById('wsb-active-list');
    if (!card || !list) return;
    const entries = [..._seenPosters.entries()].slice(0, 6);
    if (!entries.length) return;
    card.style.display = '';
    list.innerHTML = entries.map(([uid, u]) => {
        const init = ((u.name||'').split(' ').map(w=>w[0]||'').join('').slice(0,2)).toUpperCase();
        const av = u.photo && !u.photo.endsWith('blank.png')
            ? `<img src="${u.photo}" style="width:34px;height:34px;border-radius:50%;object-fit:cover;flex-shrink:0;" alt="">`
            : `<div style="width:34px;height:34px;border-radius:50%;background:#e8f3ff;color:#0095e8;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">${esc(init)}</div>`;
        return `<div class="d-flex align-items-center gap-2 px-1 py-1 rounded" style="font-size:.84rem;">
            ${av}
            <span class="text-gray-700 fw-semibold text-truncate" style="max-width:180px;">${esc(u.name)}</span>
        </div>`;
    }).join('');
}

// Kick off
loadFeed(true);

})();
</script>
