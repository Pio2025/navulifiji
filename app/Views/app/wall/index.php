<?php
$schId           = $schId           ?? 0;
$myId            = $myId            ?? 0;
$myName          = $myName          ?? 'Me';
$myPhoto         = $myPhoto         ?? base_url('assets/media/avatars/blank.png');
$schoolName      = $schoolName      ?? 'School Community';
$schoolMotto     = $schoolMotto     ?? '';
$schoolLogo      = $schoolLogo      ?? '';
$wallPostCount   = $wallPostCount   ?? 0;
$wallMemberCount = $wallMemberCount ?? 0;
$parentSchools   = $parentSchools   ?? [];
$activeSchoolId  = $activeSchoolId  ?? $schId;

$WALL_FEED_URL          = base_url('wall/feed');
$WALL_POST_URL          = base_url('wall/post');
$WALL_DELETE_POST_BASE  = base_url('wall/post/');       // + postId + '/delete'
$WALL_COMMENT_BASE      = base_url('wall/post/');       // + postId + '/comments' or /comment
$WALL_DELETE_CMT_BASE   = base_url('wall/comment/');    // + commentId + '/delete'
$WALL_REACT_URL         = base_url('wall/react');
$WALL_REACTIONS_URL     = base_url('wall/reactions');
$WALL_EDIT_POST_BASE    = base_url('wall/post/');       // + postId + '/data' or '/update'
?>
<style>
/* ── Wall sidebar ───────────────────────────────────── */
.wall-sidebar { position: sticky; top: 80px; display: flex; flex-direction: column; gap: 1rem; }
@media (max-width: 991px) { .wall-sidebar { position: static; } }
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
/* Embedded video */
.media-item.video-embed-item { max-height: none; background: #000; padding: 0; cursor: default; }
.video-embed-wrap { position: relative; width: 100%; padding-bottom: 56.25%; background: #000; }
.video-embed-wrap iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
/* File list (outside the photo grid) */
.file-list-section { padding: .25rem 1.25rem .85rem; display: grid; grid-template-columns: 1fr 1fr; gap: .4rem; }
@media (max-width: 480px) { .file-list-section { grid-template-columns: 1fr 1fr; } }
.file-row { display: flex; align-items: center; gap: .85rem; background: #fff; border: 1.5px solid #e9edf0; border-radius: 10px; padding: .75rem 1rem; cursor: pointer; transition: border-color .18s, box-shadow .18s; }
.file-row:hover { border-color: #93c5fd; box-shadow: 0 2px 10px rgba(0,0,0,.07); }
.file-row:hover .file-dl { color: #0095e8; }
.file-type-badge { width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .6rem; font-weight: 800; letter-spacing: .4px; color: #fff; flex-shrink: 0; text-transform: uppercase; line-height: 1; text-align: center; }
.file-info { flex: 1; min-width: 0; }
.file-info .fc-name { font-size: .87rem; font-weight: 600; color: #181c32; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.3; }
.file-info .fc-label { font-size: .75rem; color: #a1a5b7; margin-top: .1rem; }
.file-dl { color: #c0c5d5; flex-shrink: 0; transition: color .18s; display: flex; align-items: center; }
.file-expand-btn { display: inline-flex; align-items: center; gap: .4rem; background: #f5f8fa; border: 1.5px solid #e4e6ef; border-radius: 20px; padding: .35rem .95rem; font-size: .82rem; color: #5e6278; cursor: pointer; font-weight: 500; transition: background .15s, border-color .15s, color .15s; margin-top: .1rem; grid-column: 1 / -1; }
.file-expand-btn:hover { background: #e8f3ff; border-color: #93c5fd; color: #0095e8; }
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
.post-del-btn { background: none; border: none; color: #a1a5b7; cursor: pointer; padding: .3rem; border-radius: 6px; font-size: .9rem; }
.post-del-btn:hover { color: #f1416c; background: #fff5f8; }
/* Video modal */
#wall-video-modal .modal-body { background: #000; padding: 0; }
#wall-video-modal iframe, #wall-video-modal video { width: 100%; min-height: 340px; border: none; }
/* Lightbox */
#wall-lightbox { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.88); z-index: 9999; align-items: center; justify-content: center; }
#wall-lightbox.open { display: flex; }
#wall-lightbox img { max-width: min(92vw, 960px); max-height: 88vh; border-radius: 6px; object-fit: contain; display: block; }
#wall-lightbox .lb-close { position: fixed; top: 1rem; right: 1.5rem; color: #fff; font-size: 2rem; cursor: pointer; z-index: 10000; background: none; border: none; line-height: 1; }
#wall-lightbox .lb-nav { position: fixed; top: 50%; transform: translateY(-50%); color: #fff; font-size: 2.4rem; font-weight: 300; cursor: pointer; z-index: 10000; background: rgba(0,0,0,.35); border: none; width: 46px; height: 70px; display: flex; align-items: center; justify-content: center; border-radius: 6px; transition: background .15s; line-height: 1; }
#wall-lightbox .lb-nav:hover { background: rgba(0,0,0,.65); }
#wall-lightbox .lb-prev { left: .75rem; }
#wall-lightbox .lb-next { right: .75rem; }
#wall-lightbox .lb-counter { position: fixed; bottom: 1.25rem; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,.9); font-size: .82rem; background: rgba(0,0,0,.45); padding: .2rem .85rem; border-radius: 12px; pointer-events: none; white-space: nowrap; }
/* +N more overlay on 4th photo */
.more-overlay { position: absolute; inset: 0; background: rgba(0,0,0,.52); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.65rem; font-weight: 700; border-radius: 8px; letter-spacing: -.5px; pointer-events: none; }
/* Reactions detail modal */
.rxn-tabs { display: flex; gap: .3rem; flex-wrap: wrap; border-bottom: 1px solid #e9edf0; padding: .25rem .25rem .5rem; margin-bottom: .75rem; }
.rxn-tab { background: none; border: none; border-radius: 20px; padding: .3rem .85rem; font-size: .86rem; cursor: pointer; color: #5e6278; font-weight: 500; transition: background .13s, color .13s; white-space: nowrap; }
.rxn-tab.active { background: #e8f3ff; color: #0095e8; font-weight: 700; }
.rxn-tab:hover:not(.active) { background: #f5f8fa; }
.rxn-user-list { display: flex; flex-direction: column; gap: .55rem; max-height: 380px; overflow-y: auto; }
.rxn-user-row { display: flex; align-items: center; gap: .75rem; padding: .35rem .25rem; border-radius: 8px; }
.rxn-user-row:hover { background: #f8f9fa; }
.rxn-user-name { font-size: .9rem; font-weight: 600; color: #181c32; flex: 1; }
.rxn-emoji-badge { font-size: 1.35rem; line-height: 1; flex-shrink: 0; }
/* Reaction pill count — clickable to open who-reacted */
.rxn-count { text-decoration: underline dotted; text-underline-offset: 2px; cursor: pointer; padding: 0 .1rem; }
.rxn-count:hover { color: #0095e8; }
/* Hover tooltip for reaction pills */
#rxn-hover-tip { position:fixed;z-index:9999;background:#1e2129;color:#fff;border-radius:8px;padding:7px 11px;font-size:.82rem;max-width:230px;pointer-events:none;box-shadow:0 4px 16px rgba(0,0,0,.3);line-height:1.45; }
#rxn-hover-tip .rht-names { font-weight:600; }
#rxn-hover-tip .rht-hint { font-size:.74rem;opacity:.6;margin-top:2px; }
/* Post edit button */
.post-edit-btn { background: none; border: none; color: #a1a5b7; cursor: pointer; padding: .3rem; border-radius: 6px; font-size: .9rem; }
.post-edit-btn:hover { color: #0095e8; background: #f0f8ff; }
/* Edit modal media grid */
#edit-media-grid { display: flex; flex-wrap: wrap; gap: .5rem; }
.edit-media-thumb { position: relative; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; background: #eef1f5; flex-shrink: 0; border: 2px solid transparent; transition: border-color .15s; }
.edit-media-thumb.marked-delete { border-color: #f1416c; opacity: .5; }
.edit-media-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.edit-media-thumb .em-del { position: absolute; top: 2px; right: 2px; background: rgba(241,65,108,.85); border: none; color: #fff; border-radius: 50%; width: 20px; height: 20px; font-size: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; line-height: 1; }
.edit-media-thumb.video-thumb { background: #1a1a2e; display: flex; align-items: center; justify-content: center; }
.edit-file-chip { display: flex; align-items: center; gap: .4rem; background: #f1faff; border: 1px solid #b8d9ef; border-radius: 20px; padding: .3rem .75rem; font-size: .8rem; color: #0095e8; position: relative; transition: opacity .15s; }
.edit-file-chip.marked-delete { opacity: .4; text-decoration: line-through; border-color: #f1416c; color: #f1416c; background: #fff5f8; }
.edit-file-chip .em-del { background: none; border: none; color: inherit; cursor: pointer; padding: 0; font-size: .9rem; line-height: 1; }
.edit-new-preview { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .5rem; }
</style>

<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">School Wall</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="<?= base_url('dashboard') ?>" class="text-muted text-hover-primary">Home</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">School Wall</li>
            </ul>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
<div id="kt_app_content_container" class="app-container container-xxl">

    <?php if (!empty($parentSchools)): ?>
    <!--begin::School tabs (parent view)-->
    <div class="d-flex align-items-center gap-2 flex-wrap mb-6">
        <?php foreach ($parentSchools as $ps): ?>
        <a href="<?= base_url('wall?sch_id=' . (int)$ps['sch_id']) ?>"
           class="btn btn-sm d-inline-flex align-items-center gap-2 <?= (int)$ps['sch_id'] === (int)$activeSchoolId ? 'btn-primary' : 'btn-light text-gray-600' ?>">
            <img src="<?= !empty($ps['sch_logo']) ? base_url('uploads/schoolLogo/' . esc($ps['sch_logo'])) : base_url('navuli_logo_white_icon.png') ?>"
                 alt="" style="height:20px;width:20px;object-fit:contain;border-radius:3px;flex-shrink:0;">
            <?= esc($ps['sch_name']) ?>
        </a>
        <?php endforeach; ?>
    </div>
    <!--end::School tabs-->
    <?php endif; ?>

    <div class="row g-5 g-xl-8 align-items-start">

        <!-- ── Left: feed column ── -->
        <div class="col-lg-8">

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

        </div><!-- /col-lg-8 -->

        <!-- ── Right: sidebar ── -->
        <div class="col-lg-4">
        <aside class="wall-sidebar">

        <!-- Community card -->
        <div class="wsb-card">
            <?php if ($schoolLogo): ?>
            <div style="background:linear-gradient(135deg,#0095e8 0%,#1b5fc1 100%);padding:1.25rem 1rem .75rem;text-align:center;">
                <img src="<?= esc($schoolLogo) ?>" alt="School logo" style="height:56px;object-fit:contain;filter:drop-shadow(0 2px 6px rgba(0,0,0,.25));">
            </div>
            <?php else: ?>
            <div style="background:linear-gradient(135deg,#0095e8 0%,#1b5fc1 100%);padding:1.25rem 1rem .75rem;text-align:center;">
                <img src="<?= base_url('navuli_logo_white.png') ?>" alt="Navuli" style="height:56px;object-fit:contain;filter:drop-shadow(0 2px 6px rgba(0,0,0,.25));">
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

        </aside>
        </div><!-- /col-lg-4 -->

    </div><!-- /row -->

</div><!-- /container-xxl -->
</div><!-- /kt_app_content -->
<!--end::Content-->

<!-- Lightbox -->
<div id="wall-lightbox">
    <button class="lb-close" id="lb-close">&times;</button>
    <button class="lb-nav lb-prev" id="lb-prev">&#8249;</button>
    <img id="lb-img" src="" alt="">
    <button class="lb-nav lb-next" id="lb-next">&#8250;</button>
    <div class="lb-counter" id="lb-counter" style="display:none;"></div>
</div>

<!-- Reaction pill hover tooltip -->
<div id="rxn-hover-tip" style="display:none;"></div>

<!-- Reactions detail modal -->
<div class="modal fade" id="wall-rxn-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h6 class="modal-title fw-bold">Reactions</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2 pb-3 px-3">
                <div id="rxn-modal-tabs" class="rxn-tabs"></div>
                <div id="rxn-modal-list" class="rxn-user-list"></div>
                <div id="rxn-modal-empty" class="text-center text-muted fs-8 py-3" style="display:none;">No reactions yet.</div>
                <div id="rxn-modal-spinner" class="text-center py-4"><span class="spinner-border spinner-border-sm text-primary"></span></div>
            </div>
        </div>
    </div>
</div>

<!-- Edit post modal -->
<div class="modal fade" id="wall-edit-modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea id="edit-post-content" class="form-control mb-3" rows="4" placeholder="What's on your mind?"></textarea>

                <!-- Current media -->
                <div id="edit-current-media-wrap" style="display:none;">
                    <div class="fs-8 fw-bold text-muted mb-2 text-uppercase" style="letter-spacing:.5px;">Current Media <span class="fw-normal">(click × to remove)</span></div>
                    <div id="edit-media-grid"></div>
                    <div id="edit-file-chips" class="d-flex flex-wrap gap-2 mt-2"></div>
                </div>

                <!-- Add new media -->
                <div class="border-top mt-3 pt-3">
                    <div class="fs-8 fw-bold text-muted mb-2 text-uppercase" style="letter-spacing:.5px;">Add New Media</div>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <label class="btn btn-light-primary btn-sm" for="edit-image-input">
                            <i class="ki-duotone ki-picture fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Photos <span id="edit-img-slots" class="text-muted fs-9"></span>
                            <input type="file" id="edit-image-input" accept="image/*" multiple style="display:none;">
                        </label>
                        <button class="btn btn-light-success btn-sm" id="edit-video-btn">
                            <i class="ki-duotone ki-youtube fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Video URL
                        </button>
                        <label class="btn btn-light-warning btn-sm" for="edit-file-input">
                            <i class="ki-duotone ki-paper-clip fs-5"><span class="path1"></span><span class="path2"></span></i>
                            Files <span id="edit-file-slots" class="text-muted fs-9"></span>
                            <input type="file" id="edit-file-input" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" multiple style="display:none;">
                        </label>
                    </div>
                    <div id="edit-new-preview" class="edit-new-preview"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="edit-save-btn">Save Changes</button>
            </div>
        </div>
    </div>
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
const REACT_URL      = <?= json_encode($WALL_REACT_URL) ?>;
const REACTIONS_URL  = <?= json_encode($WALL_REACTIONS_URL) ?>;
const EDIT_POST_BASE = <?= json_encode($WALL_EDIT_POST_BASE) ?>;
const EMOJIS        = ['👍','❤️','😂','😮','😢','😡'];
const CI_TOKEN      = '<?= csrf_hash() ?>';
const CI_NAME       = '<?= csrf_token() ?>';

// ─── Lightbox state ───────────────────────────────────────────────────────────
let lbImages = [];
let lbIndex  = 0;

function openLightbox(images, startIdx) {
    lbImages = images;
    lbIndex  = Math.max(0, Math.min(startIdx, images.length - 1));
    updateLb();
    document.getElementById('wall-lightbox').classList.add('open');
}
function updateLb() {
    document.getElementById('lb-img').src = lbImages[lbIndex] || '';
    const counter  = document.getElementById('lb-counter');
    const showNav  = lbImages.length > 1;
    document.getElementById('lb-prev').style.display = showNav ? '' : 'none';
    document.getElementById('lb-next').style.display = showNav ? '' : 'none';
    if (showNav) {
        counter.textContent  = `${lbIndex + 1} / ${lbImages.length}`;
        counter.style.display = '';
    } else {
        counter.style.display = 'none';
    }
}
function closeLb() { document.getElementById('wall-lightbox').classList.remove('open'); }

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

function embedUrl(url) {
    let m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    if (m) return `https://www.youtube.com/embed/${m[1]}?rel=0&modestbranding=1`;
    m = url.match(/vimeo\.com\/(\d+)/);
    if (m) return `https://player.vimeo.com/video/${m[1]}`;
    return null; // non-embeddable
}

function renderFileList(files) {
    if (!files.length) return '';
    const MAX = 10;
    const rows = files.map((m, idx) => {
        const ft      = fileTypeInfo(m.file_name || m.file_src);
        const name    = esc(m.file_name || 'File');
        const xClass  = idx >= MAX ? ' file-row-extra' : '';
        const xStyle  = idx >= MAX ? ' style="display:none;"' : '';
        return `<div class="file-row${xClass}"${xStyle} data-type="file" data-src="${esc(m.file_src)}">
            <div class="file-type-badge" style="background:${ft.color};">${ft.badge}</div>
            <div class="file-info">
                <div class="fc-name" title="${name}">${name}</div>
                <div class="fc-label">${ft.label}</div>
            </div>
            <div class="file-dl"><i class="ki-duotone ki-arrow-down fs-4"><span class="path1"></span><span class="path2"></span></i></div>
        </div>`;
    }).join('');
    const extra = files.length - MAX;
    const expandBtn = extra > 0
        ? `<button class="file-expand-btn">+ ${extra} more file${extra !== 1 ? 's' : ''}</button>`
        : '';
    return `<div class="file-list-section">${rows}${expandBtn}</div>`;
}

function renderMedia(media, postId) {
    if (!media || !media.length) return '';

    const images = media.filter(m => m.media_type === 'image');
    const videos = media.filter(m => m.media_type === 'video_url');
    const files  = media.filter(m => m.media_type === 'file');

    let html = '';

    // ── Photo + video grid ──────────────────────────────────────────────────────
    const gridItems = [];

    // Images capped at 4; all URLs stored for lightbox navigation
    const displayImgs    = images.slice(0, 4);
    const hiddenImgCount = images.length - displayImgs.length;
    const allImageUrls   = esc(JSON.stringify(images.map(m => m.file_src)));
    let imgIdx = 0;
    displayImgs.forEach(m => {
        const myIdx   = imgIdx++;
        const isLast  = myIdx === 3 && hiddenImgCount > 0;
        const overlay = isLast ? `<div class="more-overlay">+${hiddenImgCount}</div>` : '';
        gridItems.push(`<div class="media-item" data-type="image" data-src="${m.file_src}" data-img-idx="${myIdx}"><img src="${m.file_src}" alt="" loading="lazy">${overlay}</div>`);
    });

    // Videos embedded inline as responsive iframes
    videos.forEach(m => {
        const src = embedUrl(m.file_src);
        if (src) {
            gridItems.push(`<div class="media-item video-embed-item"><div class="video-embed-wrap"><iframe src="${src}" loading="lazy" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"></iframe></div></div>`);
        } else {
            // Fallback thumbnail for non-embeddable URLs
            gridItems.push(`<div class="media-item" data-type="video" data-src="${m.file_src}"><div style="width:100%;height:200px;background:#1a1a2e;display:flex;align-items:center;justify-content:center;"><i class="ki-duotone ki-youtube text-danger fs-2x"><span class="path1"></span><span class="path2"></span></i></div></div>`);
        }
    });

    if (gridItems.length) {
        const n   = gridItems.length;
        const cls = n === 1 ? 'count-1' : n === 2 ? 'count-2' : n === 3 ? 'count-3' : 'count-4';
        html += `<div class="post-media-grid ${cls}" data-images="${allImageUrls}">${gridItems.join('')}</div>`;
    }

    // ── File list ──────────────────────────────────────────────────────────────
    html += renderFileList(files);

    return html;
}

function fileTypeInfo(filename) {
    const ext = (filename || '').split('.').pop().toLowerCase();
    const map = {
        pdf:  { badge: 'PDF',  label: 'PDF Document',   color: '#e53e3e' },
        doc:  { badge: 'DOC',  label: 'Word Document',   color: '#2b6cb0' },
        docx: { badge: 'DOCX', label: 'Word Document',   color: '#2b6cb0' },
        xls:  { badge: 'XLS',  label: 'Spreadsheet',     color: '#276749' },
        xlsx: { badge: 'XLSX', label: 'Spreadsheet',     color: '#276749' },
        ppt:  { badge: 'PPT',  label: 'Presentation',    color: '#c05621' },
        pptx: { badge: 'PPTX', label: 'Presentation',    color: '#c05621' },
        txt:  { badge: 'TXT',  label: 'Text File',       color: '#4a5568' },
        csv:  { badge: 'CSV',  label: 'CSV File',        color: '#276749' },
        zip:  { badge: 'ZIP',  label: 'Archive',         color: '#6b46c1' },
        rar:  { badge: 'RAR',  label: 'Archive',         color: '#6b46c1' },
        mp3:  { badge: 'MP3',  label: 'Audio File',      color: '#d53f8c' },
        mp4:  { badge: 'MP4',  label: 'Video File',      color: '#e53e3e' },
    };
    return map[ext] || { badge: (ext || 'FILE').slice(0,4).toUpperCase(), label: 'File', color: '#6b46c1' };
}

function renderReactions(reactions, targetType, targetId) {
    const {summary, my_emoji} = reactions;
    let pills = '';
    for (const [emoji, cnt] of Object.entries(summary || {})) {
        const isMine = emoji === my_emoji ? 'my-reaction' : '';
        pills += `<button class="reaction-pill ${isMine}" data-target-type="${targetType}" data-target-id="${targetId}" data-emoji="${emoji}">${emoji} <span class="rxn-count" data-target-type="${targetType}" data-target-id="${targetId}" data-open-emoji="${emoji}" title="See who reacted">${cnt}</span></button>`;
    }
    const addBtn = `<div class="position-relative d-inline-block">
        <button class="reaction-pill add-reaction-btn" data-target-type="${targetType}" data-target-id="${targetId}" title="React" style="gap:.2rem;">
            <span style="font-size:1rem;line-height:1;">😊</span><span style="font-size:.75rem;color:#5e6278;">React</span>
        </button>
        <div class="emoji-picker-pop" id="ep-${targetType}-${targetId}">${EMOJIS.map(e=>`<button class="ep-btn" data-emoji="${e}" data-target-type="${targetType}" data-target-id="${targetId}">${e}</button>`).join('')}</div>
    </div>`;
    return `<div class="post-reactions-bar" id="reactions-${targetType}-${targetId}">${pills}${addBtn}</div>`;
}

function roleBadge(role) {
    if (!role) return '';
    const map = {
        'System Admin':       'danger',
        'School Admin':       'primary',
        'Teacher':            'success',
        'Student':            'info',
        'Support Staff':      'secondary',
        'Parent or Guardian': 'warning',
        'Admin':              'primary',
    };
    const color = map[role] || 'secondary';
    const label = role === 'Parent or Guardian' ? 'Parent' : role;
    return `<span class="badge badge-light-${color} ms-1" style="font-size:.7rem;font-weight:500;vertical-align:middle;">${esc(label)}</span>`;
}

function renderPost(p) {
    const ownBtns = p.is_mine ? `
        <button class="post-edit-btn" data-post-id="${p.wall_post_id}" title="Edit" style="margin-left:auto;">
            <i class="ki-duotone ki-pencil fs-5"><span class="path1"></span><span class="path2"></span></i>
        </button>
        <button class="post-del-btn" data-post-id="${p.wall_post_id}" title="Delete">
            <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
        </button>` : '';
    return `<div class="wall-post-card" id="post-${p.wall_post_id}" data-post-id="${p.wall_post_id}">
        <div class="post-header">
            ${avatar(p.author_photo, p.author_name, 40)}
            <div class="post-meta">
                <div class="author">${esc(p.author_name)}${roleBadge(p.author_role)}</div>
                <div class="age">${p.age}</div>
            </div>
            ${ownBtns}
        </div>
        ${p.content ? `<div class="post-content">${esc(p.content)}</div>` : ''}
        ${renderMedia(p.media, p.wall_post_id)}
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
                    <div class="author">${esc(c.author_name)}${roleBadge(c.author_role)}</div>
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
const MAX_PHOTOS = 50;
const MAX_FILES  = 20;

document.getElementById('composer-image-input').addEventListener('change', function() {
    let skipped = 0;
    [...this.files].forEach(f => {
        if (composerImages.length >= MAX_PHOTOS) { skipped++; return; }
        composerImages.push({file: f, url: URL.createObjectURL(f)});
    });
    this.value = '';
    if (skipped) Swal.fire({icon:'warning', title:'Photo limit reached', text:`You can attach up to ${MAX_PHOTOS} photos per post. ${skipped} photo${skipped>1?'s were':' was'} not added.`, confirmButtonText:'OK'});
    renderComposerPreview();
});

document.getElementById('composer-file-input').addEventListener('change', function() {
    let skipped = 0;
    [...this.files].forEach(f => {
        if (composerFiles.length >= MAX_FILES) { skipped++; return; }
        composerFiles.push({file: f});
    });
    this.value = '';
    if (skipped) Swal.fire({icon:'warning', title:'File limit reached', text:`You can attach up to ${MAX_FILES} files per post. ${skipped} file${skipped>1?'s were':' was'} not added.`, confirmButtonText:'OK'});
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
    const target = e.target.closest('.post-del-btn, .post-edit-btn, .toggle-comments-btn, .media-item, .file-row, .file-expand-btn, .rxn-count, .reaction-pill, .add-reaction-btn, .ep-btn, .submit-comment-btn, .reply-btn, .del-comment-btn');
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

    // Edit post
    if (target.classList.contains('post-edit-btn')) {
        openEditModal(parseInt(target.dataset.postId));
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

    // File row click → open in new tab
    if (target.classList.contains('file-row')) {
        window.open(target.dataset.src, '_blank');
        return;
    }

    // File expand button → reveal hidden rows
    if (target.classList.contains('file-expand-btn')) {
        const section = target.closest('.file-list-section');
        section.querySelectorAll('.file-row-extra').forEach(el => el.style.display = '');
        target.remove();
        return;
    }

    // Media click
    if (target.classList.contains('media-item')) {
        const type = target.dataset.type;
        const src  = target.dataset.src;
        if (type === 'image') {
            const grid   = target.closest('.post-media-grid');
            const imgs   = grid ? JSON.parse(grid.dataset.images || '[]') : [src];
            const idx    = parseInt(target.dataset.imgIdx ?? '0');
            openLightbox(imgs.length ? imgs : [src], idx);
        } else if (type === 'video') {
            // Fallback: non-embeddable video URL — open directly
            window.open(src, '_blank');
        } else {
            window.open(src, '_blank');
        }
        return;
    }

    // Reaction count click → show who reacted
    if (target.classList.contains('rxn-count')) {
        e.stopPropagation();
        openRxnModal(target.dataset.targetType, parseInt(target.dataset.targetId), target.dataset.openEmoji);
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
    // Invalidate hover cache so names refresh after a reaction change
    delete rhtCache[`${targetType}-${targetId}`];
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

// Lightbox controls
document.getElementById('lb-close').addEventListener('click', closeLb);
document.getElementById('lb-prev').addEventListener('click', e => {
    e.stopPropagation();
    lbIndex = (lbIndex - 1 + lbImages.length) % lbImages.length;
    updateLb();
});
document.getElementById('lb-next').addEventListener('click', e => {
    e.stopPropagation();
    lbIndex = (lbIndex + 1) % lbImages.length;
    updateLb();
});
document.getElementById('wall-lightbox').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeLb();
});
document.addEventListener('keydown', e => {
    if (!document.getElementById('wall-lightbox').classList.contains('open')) return;
    if (e.key === 'ArrowLeft')  { lbIndex = (lbIndex - 1 + lbImages.length) % lbImages.length; updateLb(); }
    if (e.key === 'ArrowRight') { lbIndex = (lbIndex + 1) % lbImages.length; updateLb(); }
    if (e.key === 'Escape')     { closeLb(); }
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

// (embedUrl defined above near renderMedia)

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

// ─── Reactions detail modal ───────────────────────────────────────────────────
const rxnModal = new bootstrap.Modal(document.getElementById('wall-rxn-modal'));
let rxnAllData = {}; // emoji → [{name, photo}]

async function openRxnModal(targetType, targetId, openEmoji) {
    rxnAllData = {};
    document.getElementById('rxn-modal-tabs').innerHTML   = '';
    document.getElementById('rxn-modal-list').innerHTML   = '';
    document.getElementById('rxn-modal-empty').style.display  = 'none';
    document.getElementById('rxn-modal-spinner').style.display = '';
    rxnModal.show();

    try {
        const r    = await fetch(`${REACTIONS_URL}?target_type=${encodeURIComponent(targetType)}&target_id=${targetId}`);
        const data = await r.json();
        rxnAllData = data.reactions || {};
    } catch(e) {
        rxnAllData = {};
    }

    document.getElementById('rxn-modal-spinner').style.display = 'none';

    const emojis = Object.keys(rxnAllData);
    if (!emojis.length) {
        document.getElementById('rxn-modal-empty').style.display = '';
        return;
    }

    renderRxnTabs(emojis, openEmoji && rxnAllData[openEmoji] ? openEmoji : 'all');
}

function renderRxnTabs(emojis, activeKey) {
    const tabsEl = document.getElementById('rxn-modal-tabs');
    const total  = emojis.reduce((s, e) => s + (rxnAllData[e]?.length || 0), 0);

    const allTab = `<button class="rxn-tab${activeKey === 'all' ? ' active' : ''}" data-key="all">All <span style="font-size:.78rem;opacity:.7;">${total}</span></button>`;
    const emojiTabs = emojis.map(emoji => {
        const cnt = rxnAllData[emoji]?.length || 0;
        return `<button class="rxn-tab${activeKey === emoji ? ' active' : ''}" data-key="${esc(emoji)}">${emoji} <span style="font-size:.78rem;opacity:.7;">${cnt}</span></button>`;
    }).join('');

    tabsEl.innerHTML = allTab + emojiTabs;
    tabsEl.querySelectorAll('.rxn-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            tabsEl.querySelectorAll('.rxn-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            renderRxnList(btn.dataset.key);
        });
    });

    renderRxnList(activeKey);
}

function renderRxnList(key) {
    const listEl = document.getElementById('rxn-modal-list');
    let rows = [];
    if (key === 'all') {
        Object.entries(rxnAllData).forEach(([emoji, users]) => {
            users.forEach(u => rows.push({...u, emoji}));
        });
    } else {
        (rxnAllData[key] || []).forEach(u => rows.push({...u, emoji: key}));
    }

    if (!rows.length) { listEl.innerHTML = '<div class="text-center text-muted fs-8 py-2">No reactions.</div>'; return; }

    listEl.innerHTML = rows.map(u => {
        const initials = (u.name || '').split(' ').map(w => w[0] || '').join('').slice(0, 2).toUpperCase();
        const av = u.photo && !u.photo.endsWith('blank.png')
            ? `<img src="${esc(u.photo)}" style="width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0;" alt="">`
            : `<div style="width:38px;height:38px;border-radius:50%;background:#e8f3ff;color:#0095e8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">${esc(initials)}</div>`;
        return `<div class="rxn-user-row">
            ${av}
            <span class="rxn-user-name">${esc(u.name)}</span>
            <span class="rxn-emoji-badge">${u.emoji}</span>
        </div>`;
    }).join('');
}

// ─── Reaction pill hover tooltip ─────────────────────────────────────────────
const rhtEl    = document.getElementById('rxn-hover-tip');
const rhtCache = {};
let   rhtTimer = null;

document.getElementById('wall-feed').addEventListener('mouseover', function(e) {
    const pill = e.target.closest('.reaction-pill');
    if (!pill || pill.classList.contains('add-reaction-btn') || !pill.dataset.emoji) return;
    clearTimeout(rhtTimer);
    rhtTimer = setTimeout(() => showRxnHoverTip(pill), 350);
});

document.getElementById('wall-feed').addEventListener('mouseout', function(e) {
    if (!e.target.closest('.reaction-pill')) return;
    clearTimeout(rhtTimer);
    rhtEl.style.display = 'none';
});

async function showRxnHoverTip(pill) {
    const targetType = pill.dataset.targetType;
    const targetId   = pill.dataset.targetId;
    const emoji      = pill.dataset.emoji;
    const cacheKey   = `${targetType}-${targetId}`;

    rhtEl.innerHTML     = '<span style="opacity:.55;font-size:.78rem;">Loading…</span>';
    positionRxnTip(pill);
    rhtEl.style.display = 'block';

    if (!rhtCache[cacheKey]) {
        try {
            const r  = await fetch(`${REACTIONS_URL}?target_type=${encodeURIComponent(targetType)}&target_id=${targetId}`);
            const d  = await r.json();
            rhtCache[cacheKey] = d.reactions || {};
        } catch { rhtCache[cacheKey] = {}; }
    }

    const users = (rhtCache[cacheKey][emoji] || []);
    if (!users.length) { rhtEl.style.display = 'none'; return; }

    const names = users.map(u => esc(u.name));
    const summary = names.length <= 3
        ? names.join(', ')
        : names.slice(0, 3).join(', ') + ` and ${names.length - 3} more`;

    rhtEl.innerHTML = `<div class="rht-names">${emoji} ${summary}</div><div class="rht-hint">Click the count to see all</div>`;
    positionRxnTip(pill);
}

function positionRxnTip(el) {
    rhtEl.style.display = 'block';
    const rect  = el.getBoundingClientRect();
    const tipH  = rhtEl.offsetHeight;
    const tipW  = rhtEl.offsetWidth;
    let top  = rect.top - tipH - 8;
    let left = rect.left;
    if (top < 4) top = rect.bottom + 8;
    if (left + tipW > window.innerWidth - 8) left = window.innerWidth - tipW - 8;
    rhtEl.style.top  = `${Math.max(4, top)}px`;
    rhtEl.style.left = `${Math.max(4, left)}px`;
}

// ─── Edit post modal ──────────────────────────────────────────────────────────
let editPostId     = null;
let editDeleteIds  = new Set();
let editNewImages  = [];   // {file, url}
let editNewFiles   = [];   // {file}
let editNewVideos  = [];   // string[]
let editCurrentMedia = []; // from server

const editModal = new bootstrap.Modal(document.getElementById('wall-edit-modal'));

async function openEditModal(postId) {
    editPostId    = postId;
    editDeleteIds = new Set();
    editNewImages = [];
    editNewFiles  = [];
    editNewVideos = [];

    document.getElementById('edit-post-content').value = '';
    document.getElementById('edit-media-grid').innerHTML = '';
    document.getElementById('edit-file-chips').innerHTML = '';
    document.getElementById('edit-new-preview').innerHTML = '';

    // Show loading state
    const saveBtn = document.getElementById('edit-save-btn');
    saveBtn.disabled = true;
    saveBtn.textContent = 'Loading...';
    editModal.show();

    try {
        const r = await fetch(`${EDIT_POST_BASE}${postId}/data`);
        const data = await r.json();
        if (!data.success) { editModal.hide(); Swal.fire({icon:'error', title:'Error', text: data.error||'Could not load post.'}); return; }

        editCurrentMedia = data.media;
        document.getElementById('edit-post-content').value = data.content || '';
        renderEditCurrentMedia();
        updateEditSlots();
    } catch(e) {
        editModal.hide();
        Swal.fire({icon:'error', title:'Error', text:'Could not load post data.'});
        return;
    }

    saveBtn.disabled = false;
    saveBtn.textContent = 'Save Changes';
}

function renderEditCurrentMedia() {
    const images = editCurrentMedia.filter(m => m.media_type === 'image');
    const videos = editCurrentMedia.filter(m => m.media_type === 'video_url');
    const files  = editCurrentMedia.filter(m => m.media_type === 'file');
    const wrap   = document.getElementById('edit-current-media-wrap');
    const grid   = document.getElementById('edit-media-grid');
    const chips  = document.getElementById('edit-file-chips');

    grid.innerHTML  = '';
    chips.innerHTML = '';

    images.forEach(m => {
        const del   = editDeleteIds.has(m.wall_media_id);
        const thumb = document.createElement('div');
        thumb.className = 'edit-media-thumb' + (del ? ' marked-delete' : '');
        thumb.innerHTML = `<img src="${esc(m.file_src)}" alt="" loading="lazy">
            <button class="em-del" data-mid="${m.wall_media_id}" title="${del?'Undo remove':'Remove'}">${del?'↩':'×'}</button>`;
        thumb.querySelector('.em-del').addEventListener('click', () => toggleEditDelete(m.wall_media_id));
        grid.appendChild(thumb);
    });

    videos.forEach(m => {
        const del   = editDeleteIds.has(m.wall_media_id);
        const thumb = document.createElement('div');
        thumb.className = 'edit-media-thumb video-thumb' + (del ? ' marked-delete' : '');
        thumb.style.cssText = 'width:80px;height:80px;';
        thumb.innerHTML = `<i class="ki-duotone ki-youtube text-danger fs-2x"><span class="path1"></span><span class="path2"></span></i>
            <button class="em-del" data-mid="${m.wall_media_id}" title="${del?'Undo remove':'Remove'}" style="position:absolute;top:2px;right:2px;">${del?'↩':'×'}</button>`;
        thumb.querySelector('.em-del').addEventListener('click', () => toggleEditDelete(m.wall_media_id));
        grid.appendChild(thumb);
    });

    files.forEach(m => {
        const del  = editDeleteIds.has(m.wall_media_id);
        const chip = document.createElement('div');
        chip.className = 'edit-file-chip' + (del ? ' marked-delete' : '');
        chip.innerHTML = `<i class="ki-duotone ki-paper-clip fs-6"><span class="path1"></span><span class="path2"></span></i>
            <span>${esc(m.file_name || 'File')}</span>
            <button class="em-del" data-mid="${m.wall_media_id}" title="${del?'Undo remove':'Remove'}">${del?'↩':'×'}</button>`;
        chip.querySelector('.em-del').addEventListener('click', () => toggleEditDelete(m.wall_media_id));
        chips.appendChild(chip);
    });

    wrap.style.display = (images.length || videos.length || files.length) ? '' : 'none';
}

function toggleEditDelete(mid) {
    if (editDeleteIds.has(mid)) editDeleteIds.delete(mid);
    else editDeleteIds.add(mid);
    renderEditCurrentMedia();
    updateEditSlots();
}

function countActiveByType(type) {
    return editCurrentMedia.filter(m => m.media_type === type && !editDeleteIds.has(m.wall_media_id)).length;
}

function updateEditSlots() {
    const imgUsed  = countActiveByType('image') + editNewImages.length;
    const fileUsed = countActiveByType('file')  + editNewFiles.length;
    document.getElementById('edit-img-slots').textContent  = `(${imgUsed}/50)`;
    document.getElementById('edit-file-slots').textContent = `(${fileUsed}/20)`;
}

function renderEditNewPreview() {
    const wrap = document.getElementById('edit-new-preview');
    wrap.innerHTML = editNewImages.map((item, i) =>
        `<div class="cmp-thumb"><img src="${item.url}" alt=""><button class="rm-btn" data-type="image" data-idx="${i}" style="position:absolute;top:2px;right:2px;background:rgba(0,0,0,.55);border:none;color:#fff;border-radius:50%;width:20px;height:20px;font-size:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;">&times;</button></div>`
    ).join('') + editNewFiles.map((item, i) =>
        `<div class="cmp-file-chip"><i class="ki-duotone ki-paper-clip fs-6"><span class="path1"></span><span class="path2"></span></i>${esc(item.file.name)}<button class="rm-btn" data-type="file" data-idx="${i}" style="background:none;border:none;color:inherit;cursor:pointer;padding:0;font-size:.9rem;">&times;</button></div>`
    ).join('') + editNewVideos.map((url, i) =>
        `<div class="d-flex align-items-center gap-2 fs-8 text-success"><i class="ki-duotone ki-youtube fs-5"><span class="path1"></span><span class="path2"></span></i>${esc(url)}<button class="rm-btn" data-type="video" data-idx="${i}" style="background:none;border:none;color:inherit;cursor:pointer;padding:0;font-size:.9rem;">&times;</button></div>`
    ).join('');
    wrap.querySelectorAll('.rm-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const t = btn.dataset.type, idx = parseInt(btn.dataset.idx);
            if (t === 'image') editNewImages.splice(idx, 1);
            else if (t === 'file') editNewFiles.splice(idx, 1);
            else if (t === 'video') editNewVideos.splice(idx, 1);
            renderEditNewPreview();
            updateEditSlots();
        });
    });
    updateEditSlots();
}

document.getElementById('edit-image-input').addEventListener('change', function() {
    let skipped = 0;
    [...this.files].forEach(f => {
        const used = countActiveByType('image') + editNewImages.length;
        if (used >= MAX_PHOTOS) { skipped++; return; }
        editNewImages.push({file: f, url: URL.createObjectURL(f)});
    });
    this.value = '';
    if (skipped) Swal.fire({icon:'warning', title:'Photo limit', text:`Max ${MAX_PHOTOS} photos per post.`, confirmButtonText:'OK'});
    renderEditNewPreview();
});

document.getElementById('edit-file-input').addEventListener('change', function() {
    let skipped = 0;
    [...this.files].forEach(f => {
        const used = countActiveByType('file') + editNewFiles.length;
        if (used >= MAX_FILES) { skipped++; return; }
        editNewFiles.push({file: f});
    });
    this.value = '';
    if (skipped) Swal.fire({icon:'warning', title:'File limit', text:`Max ${MAX_FILES} files per post.`, confirmButtonText:'OK'});
    renderEditNewPreview();
});

document.getElementById('edit-video-btn').addEventListener('click', () => {
    Swal.fire({
        title: 'Add Video URL',
        input: 'url',
        inputPlaceholder: 'https://www.youtube.com/watch?v=...',
        showCancelButton: true,
        confirmButtonText: 'Add',
    }).then(r => {
        if (r.isConfirmed && r.value) {
            editNewVideos.push(r.value.trim());
            renderEditNewPreview();
        }
    });
});

document.getElementById('edit-save-btn').addEventListener('click', async () => {
    const saveBtn = document.getElementById('edit-save-btn');
    saveBtn.disabled = true; saveBtn.textContent = 'Saving...';

    const fd = new FormData();
    fd.append('content', document.getElementById('edit-post-content').value.trim());
    editDeleteIds.forEach(id => fd.append('delete_media_ids[]', id));
    editNewImages.forEach(item => fd.append('media[]', item.file));
    editNewFiles.forEach(item => fd.append('media[]', item.file));
    editNewVideos.forEach(url => fd.append('video_urls[]', url));

    try {
        const data = await postForm(`${EDIT_POST_BASE}${editPostId}/update`, fd);
        if (data.success) {
            editModal.hide();
            const cardEl = document.getElementById(`post-${editPostId}`);
            if (cardEl) {
                const newHtml = renderPost({...data.post,
                    reactions: cardEl.__reactions || data.post.reactions,
                    comment_count: parseInt(cardEl.querySelector('.cmt-count')?.textContent ?? '0'),
                });
                const temp = document.createElement('div');
                temp.innerHTML = newHtml;
                cardEl.replaceWith(temp.firstElementChild);
            }
        } else {
            Swal.fire({icon:'error', title:'Error', text: data.error || 'Could not save changes.'});
        }
    } catch(e) {
        Swal.fire({icon:'error', title:'Error', text:'Could not connect to server.'});
    }

    saveBtn.disabled = false; saveBtn.textContent = 'Save Changes';
});

// Reset edit state when modal is closed
document.getElementById('wall-edit-modal').addEventListener('hidden.bs.modal', () => {
    editPostId = null; editDeleteIds = new Set();
    editNewImages = []; editNewFiles = []; editNewVideos = [];
    document.getElementById('edit-new-preview').innerHTML = '';
    document.getElementById('edit-image-input').value = '';
    document.getElementById('edit-file-input').value  = '';
});

// Kick off
loadFeed(true);

})();
</script>
