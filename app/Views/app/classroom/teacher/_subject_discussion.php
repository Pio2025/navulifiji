<?php
// Helper: render an avatar (photo or initials)
function sdAvatar(string $photo = '', string $name = '', int $size = 40): string {
    if ($photo) {
        return '<img src="' . base_url('uploads/profilePhoto/' . $photo) . '" '
             . 'class="rounded-circle flex-shrink-0" '
             . 'style="width:' . $size . 'px;height:' . $size . 'px;object-fit:cover;" alt="" />';
    }
    $words    = array_filter(explode(' ', trim($name)));
    $initials = strtoupper(implode('', array_map(fn($w) => $w[0], $words)));
    $initials = substr($initials, 0, 2) ?: '?';
    $colors   = ['primary','success','info','warning','danger'];
    $color    = $colors[abs(crc32($name)) % 5];
    $fs       = $size >= 40 ? '7' : '9';
    return '<div class="symbol symbol-' . $size . 'px flex-shrink-0">'
         . '<div class="symbol-label bg-light-' . $color . ' fw-bold text-' . $color . ' fs-' . $fs . '">' . $initials . '</div>'
         . '</div>';
}

// Helper: photo grid HTML
function sdPhotoGrid(array $photos, int $sdId): string {
    $total = count($photos);
    if ($total === 0) return '';
    $show  = min($total, 4);
    $extra = $total > 4 ? $total - 4 : 0;
    $cols  = $total === 1 ? 1 : ($total === 2 ? 2 : ($total === 3 ? 3 : 4));
    $all   = json_encode(array_column($photos, 'photo_path'), JSON_HEX_APOS);
    $html  = '<div class="sd-photo-grid sd-photo-grid-' . $cols . ' mt-3">';
    for ($i = 0; $i < $show; $i++) {
        $path = $photos[$i]['photo_path'];
        $html .= '<div class="sd-photo-item" data-index="' . $i . '" data-photos=\'' . $all . '\' onclick="sdOpenViewer(this)">';
        $html .= '<img src="' . base_url('uploads/subject_discussion/' . $path) . '" alt="" class="sd-photo-thumb" />';
        if ($i === 3 && $extra > 0) {
            $html .= '<div class="sd-photo-more-overlay"><span>+' . $extra . ' more</span></div>';
        }
        $html .= '</div>';
    }
    $html .= '</div>';
    return $html;
}

// Helper: highlight @mentions in text
function sdFormatTextPhp(string $text): string {
    return preg_replace('/@(\S+)/', '<span class="sd-mention">@$1</span>', nl2br(esc($text)));
}

// Helper: time ago
function sdTimeAgo(string $dt): string {
    $diff = time() - strtotime($dt);
    if ($diff < 60)    return 'just now';
    if ($diff < 3600)  return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('d M Y', strtotime($dt));
}
?>

<!--begin::Class Subject Discussion-->
<div class="d-flex align-items-center justify-content-between mb-5">
    <div class="d-flex align-items-center gap-2">
        <i class="ki-duotone ki-message-edit fs-2 text-primary me-1">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <h4 class="fw-bold text-gray-800 mb-0">Class Subject Discussion</h4>
        <span class="badge badge-light-primary ms-2 fs-8" id="sd_post_count"><?= count($discussions ?? []) ?></span>
    </div>
</div>

<!--begin::Post Creator-->
<?php if ($canPost ?? true): ?>
<div class="card border-0 shadow-sm mb-5" style="border-radius:.75rem;">
    <div class="card-body p-4">
        <div class="d-flex align-items-start gap-3">
            <div class="flex-shrink-0">
                <?php if (!empty($sessionPhotoUrl)): ?>
                <img src="<?= $sessionPhotoUrl ?>" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;" />
                <?php else: ?>
                <?= sdAvatar('', $sessionFname, 40) ?>
                <?php endif; ?>
            </div>
            <div class="flex-grow-1">
                <textarea class="form-control border-0 bg-light-secondary fs-7" id="sd_message"
                          placeholder="Share something with your class..." rows="2"
                          style="resize:none;border-radius:.75rem;padding:.75rem 1rem;"></textarea>
                <div id="sd_photo_preview" class="d-flex flex-wrap gap-2 mt-3" style="display:none!important;"></div>
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <label for="sd_photos" class="btn btn-sm btn-light-success mb-0 cursor-pointer">
                        <i class="ki-duotone ki-picture fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Photos <span class="text-muted fw-normal fs-9">(max 10, images only)</span>
                    </label>
                    <input type="file" id="sd_photos" accept="image/*" multiple class="d-none" />
                    <button type="button" class="btn btn-sm btn-primary" id="btn_sd_post">
                        <span class="indicator-label">
                            <i class="ki-duotone ki-send fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>Post
                        </span>
                        <span class="indicator-progress">Posting... <span class="spinner-border spinner-border-sm align-middle ms-1"></span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!--end::Post Creator-->

<!--begin::Posts Feed-->
<div id="sd_feed">
<?php if (empty($discussions)): ?>
<div class="text-center py-14 text-muted" id="sd_empty_state">
    <i class="ki-duotone ki-message-edit fs-4x text-gray-200 mb-3">
        <span class="path1"></span><span class="path2"></span>
    </i>
    <div class="fs-6 fw-semibold mb-1">No posts yet</div>
    <div class="fs-8">Be the first to share something with your class.</div>
</div>
<?php else: ?>
<?php foreach ($discussions as $post):
    $isOwnPost = (int)$post['author_id'] === (int)$sessionUserId;
?>
<div class="card border-0 shadow-sm mb-4 sd-post-card" id="sd_post_<?= $post['sd_id'] ?>" data-post-id="<?= $post['sd_id'] ?>">
    <div class="card-body p-5">
        <!--begin::Post header-->
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="d-flex align-items-start gap-3">
                <?= sdAvatar($post['author_photo'] ?? '', $post['author_name'] ?? '', 40) ?>
                <div>
                    <div class="fw-bold text-gray-800 fs-7"><?= esc($post['author_name']) ?></div>
                    <div class="text-muted fs-9"><?= sdTimeAgo($post['created_at']) ?></div>
                </div>
            </div>
            <?php if ($isOwnPost): ?>
            <button class="btn btn-sm btn-icon btn-light-danger sd-delete-post" data-id="<?= $post['sd_id'] ?>"
                    title="Delete post" style="width:30px;height:30px;">
                <i class="ki-duotone ki-trash fs-5">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    <span class="path4"></span><span class="path5"></span>
                </i>
            </button>
            <?php endif; ?>
        </div>
        <!--end::Post header-->

        <!--begin::Post message-->
        <?php if (!empty($post['message'])): ?>
        <div class="text-gray-700 fs-7 lh-lg mb-3" style="white-space:pre-line;"><?= nl2br(esc($post['message'])) ?></div>
        <?php endif; ?>
        <!--end::Post message-->

        <!--begin::Photo grid-->
        <?= sdPhotoGrid($post['photos'] ?? [], $post['sd_id']) ?>
        <!--end::Photo grid-->

        <!--begin::Reaction bar-->
        <?php
            $likeActive    = ($post['user_reaction'] ?? '') === 'like'    ? 'btn-light-primary sd-reacted' : 'btn-light';
            $dislikeActive = ($post['user_reaction'] ?? '') === 'dislike' ? 'btn-light-danger sd-reacted'  : 'btn-light';
            $totalReactions = (int)$post['like_count'] + (int)$post['dislike_count'];
        ?>
        <?php if ($totalReactions > 0): ?>
        <div class="d-flex align-items-center gap-2 mt-3 mb-1 px-1">
            <?php if ((int)$post['like_count'] > 0): ?>
            <span class="d-flex align-items-center gap-1 text-muted fs-9">
                <i class="ki-duotone ki-like-2 fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i>
                <?= (int)$post['like_count'] ?>
            </span>
            <?php endif; ?>
            <?php if ((int)$post['dislike_count'] > 0): ?>
            <span class="d-flex align-items-center gap-1 text-muted fs-9">
                <i class="ki-duotone ki-dislike-2 fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i>
                <?= (int)$post['dislike_count'] ?>
            </span>
            <?php endif; ?>
            <button class="btn btn-link p-0 text-muted fs-9 ms-auto text-hover-primary sd-view-reactions"
                    data-id="<?= $post['sd_id'] ?>" data-scope="post">See reactions</button>
        </div>
        <?php endif; ?>
        <div class="d-flex align-items-center gap-2 pt-2 mt-1" style="border-top:1px solid #f1f1f4;">
            <button class="btn btn-sm <?= $likeActive ?> sd-post-react d-flex align-items-center gap-1"
                    data-id="<?= $post['sd_id'] ?>" data-type="like">
                <i class="ki-duotone ki-like-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                <span class="sd-like-count"><?= (int)$post['like_count'] ?></span>
            </button>
            <button class="btn btn-sm <?= $dislikeActive ?> sd-post-react d-flex align-items-center gap-1"
                    data-id="<?= $post['sd_id'] ?>" data-type="dislike">
                <i class="ki-duotone ki-dislike-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                <span class="sd-dislike-count"><?= (int)$post['dislike_count'] ?></span>
            </button>
            <button class="btn btn-sm btn-light sd-toggle-comments d-flex align-items-center gap-1 ms-1"
                    data-id="<?= $post['sd_id'] ?>">
                <i class="ki-duotone ki-message fs-5"><span class="path1"></span><span class="path2"></span></i>
                <span class="sd-comment-count"><?= (int)$post['comment_count'] ?></span>
                <span class="ms-1 fs-8">Comment<?= (int)$post['comment_count'] !== 1 ? 's' : '' ?></span>
            </button>
        </div>
        <!--end::Reaction bar-->

        <!--begin::Comments section-->
        <div class="sd-comments-section mt-4 d-none" id="sd_comments_<?= $post['sd_id'] ?>">
            <div class="d-flex flex-column gap-3 mb-3" id="sd_comment_list_<?= $post['sd_id'] ?>">
            <?php foreach ($post['comments'] as $comment):
                $isOwnComment = (int)$comment['author_id'] === (int)$sessionUserId;
                $cLikeActive    = ($comment['user_reaction'] ?? '') === 'like'    ? 'text-primary fw-bold sd-reacted' : 'text-muted';
                $cDislikeActive = ($comment['user_reaction'] ?? '') === 'dislike' ? 'text-danger fw-bold sd-reacted'  : 'text-muted';
            ?>
            <div class="sd-comment d-flex align-items-start gap-2" id="sd_comment_<?= $comment['sdc_id'] ?>" data-comment-id="<?= $comment['sdc_id'] ?>">
                <?= sdAvatar($comment['author_photo'] ?? '', $comment['author_name'] ?? '', 32) ?>
                <div class="flex-grow-1 min-w-0">
                    <div class="p-3 rounded-3" style="background:#f0f4ff;">
                        <div class="fw-bold text-gray-800 fs-8 mb-1"><?= esc($comment['author_name']) ?></div>
                        <div class="text-gray-700 fs-8 lh-lg" style="white-space:pre-line;"><?= sdFormatTextPhp($comment['comment']) ?></div>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-3 mt-1 ms-1">
                        <span class="text-muted" style="font-size:.72rem;"><?= sdTimeAgo($comment['created_at']) ?></span>
                        <button class="btn btn-link btn-sm p-0 fs-8 sd-comment-react <?= $cLikeActive ?>"
                                data-id="<?= $comment['sdc_id'] ?>" data-type="like">
                            <i class="ki-duotone ki-like-2 fs-6"><span class="path1"></span><span class="path2"></span></i>
                            <span class="sd-clike-count"><?= (int)$comment['like_count'] ?></span>
                        </button>
                        <button class="btn btn-link btn-sm p-0 fs-8 sd-comment-react <?= $cDislikeActive ?>"
                                data-id="<?= $comment['sdc_id'] ?>" data-type="dislike">
                            <i class="ki-duotone ki-dislike-2 fs-6"><span class="path1"></span><span class="path2"></span></i>
                            <span class="sd-cdislike-count"><?= (int)$comment['dislike_count'] ?></span>
                        </button>
                        <button class="btn btn-link btn-sm p-0 text-primary fs-8 sd-reply-toggle"
                                data-comment-id="<?= $comment['sdc_id'] ?>">Reply</button>
                        <button class="btn btn-link btn-sm p-0 text-muted fs-9 sd-view-reactions"
                                data-id="<?= $comment['sdc_id'] ?>" data-scope="comment">Reactions</button>
                        <?php if ($isOwnComment): ?>
                        <button class="btn btn-link btn-sm p-0 text-danger fs-8 sd-comment-delete"
                                data-id="<?= $comment['sdc_id'] ?>">Delete</button>
                        <?php endif; ?>
                    </div>

                    <!--begin::Replies-->
                    <div class="ms-1 mt-2" id="sd_replies_<?= $comment['sdc_id'] ?>">
                    <?php foreach ($comment['replies'] as $reply):
                        $isOwnReply = (int)$reply['author_id'] === (int)$sessionUserId;
                        $rLikeActive    = ($reply['user_reaction'] ?? '') === 'like'    ? 'text-primary fw-bold sd-reacted' : 'text-muted';
                        $rDislikeActive = ($reply['user_reaction'] ?? '') === 'dislike' ? 'text-danger fw-bold sd-reacted'  : 'text-muted';
                    ?>
                    <div class="sd-reply d-flex align-items-start gap-2 mb-2" id="sd_reply_<?= $reply['sdcr_id'] ?>">
                        <?= sdAvatar($reply['author_photo'] ?? '', $reply['author_name'] ?? '', 28) ?>
                        <div class="flex-grow-1 min-w-0">
                            <div class="p-2 px-3 rounded-3" style="background:#f8f9fa;">
                                <div class="fw-bold text-gray-800 fs-9 mb-1"><?= esc($reply['author_name']) ?></div>
                                <div class="text-gray-700 fs-9 lh-lg" style="white-space:pre-line;"><?= sdFormatTextPhp($reply['reply']) ?></div>
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-2 mt-1 ms-1">
                                <span class="text-muted" style="font-size:.68rem;"><?= sdTimeAgo($reply['created_at']) ?></span>
                                <button class="btn btn-link p-0 fs-9 sd-reply-react <?= $rLikeActive ?>"
                                        data-id="<?= $reply['sdcr_id'] ?>" data-type="like">
                                    <i class="ki-duotone ki-like-2 fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="sd-rlike-count"><?= (int)$reply['like_count'] ?></span>
                                </button>
                                <button class="btn btn-link p-0 fs-9 sd-reply-react <?= $rDislikeActive ?>"
                                        data-id="<?= $reply['sdcr_id'] ?>" data-type="dislike">
                                    <i class="ki-duotone ki-dislike-2 fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="sd-rdislike-count"><?= (int)$reply['dislike_count'] ?></span>
                                </button>
                                <button class="btn btn-link p-0 text-muted fs-9 sd-view-reactions"
                                        data-id="<?= $reply['sdcr_id'] ?>" data-scope="reply">Reactions</button>
                                <button class="btn btn-link p-0 text-primary fs-9 fw-semibold sd-reply-on-reply"
                                        data-comment-id="<?= $comment['sdc_id'] ?>"
                                        data-mention="<?= esc($reply['author_name']) ?>">Reply</button>
                                <?php if ($isOwnReply): ?>
                                <button class="btn btn-link p-0 text-danger fs-9 sd-reply-delete"
                                        data-id="<?= $reply['sdcr_id'] ?>">Delete</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!--begin::Reply form-->
                    <div class="sd-reply-form d-none mt-2" id="sd_reply_form_<?= $comment['sdc_id'] ?>">
                        <div class="d-flex align-items-start gap-2">
                            <?php if (!empty($sessionPhotoUrl)): ?>
                            <img src="<?= $sessionPhotoUrl ?>" class="rounded-circle flex-shrink-0"
                                 style="width:28px;height:28px;object-fit:cover;" />
                            <?php else: ?>
                            <?= sdAvatar('', $sessionFname, 28) ?>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm sd-reply-input"
                                           id="sd_reply_input_<?= $comment['sdc_id'] ?>"
                                           placeholder="Write a reply..." maxlength="1000" />
                                    <button class="btn btn-sm btn-primary sd-reply-submit"
                                            data-comment-id="<?= $comment['sdc_id'] ?>">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Reply form-->
                    </div>
                    <!--end::Replies-->
                </div>
            </div>
            <?php endforeach; ?>
            </div>

            <!--begin::Comment form-->
            <div class="d-flex align-items-start gap-2 pt-3" style="border-top:1px solid #f8f9fa;">
                <?php if (!empty($sessionPhotoUrl)): ?>
                <img src="<?= $sessionPhotoUrl ?>" class="rounded-circle flex-shrink-0"
                     style="width:32px;height:32px;object-fit:cover;" />
                <?php else: ?>
                <?= sdAvatar('', $sessionFname, 32) ?>
                <?php endif; ?>
                <div class="flex-grow-1">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-sm sd-comment-input"
                               id="sd_comment_input_<?= $post['sd_id'] ?>"
                               placeholder="Write a comment..." maxlength="1000" />
                        <button class="btn btn-sm btn-primary sd-comment-submit"
                                data-id="<?= $post['sd_id'] ?>">Send</button>
                    </div>
                </div>
            </div>
            <!--end::Comment form-->
        </div>
        <!--end::Comments section-->
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
<!--end::Posts Feed-->

<!--begin::Reactions Modal-->
<div class="modal fade" id="sd_reactions_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-gray-800 fs-6 mb-0">Reactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3 pb-4">
                <!--begin::Tabs-->
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x fs-7 fw-semibold border-0 mb-4" id="sd_react_tabs">
                    <li class="nav-item">
                        <a class="nav-link active text-active-primary pb-3 d-flex align-items-center gap-1" href="#" id="sd_react_tab_all">
                            All <span class="badge badge-light ms-1 fs-9" id="sd_react_all_cnt">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-3 d-flex align-items-center gap-1" href="#" id="sd_react_tab_like">
                            <i class="ki-duotone ki-like-2 fs-6 text-primary"><span class="path1"></span><span class="path2"></span></i>
                            <span class="badge badge-light-primary ms-1 fs-9" id="sd_react_like_cnt">0</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-3 d-flex align-items-center gap-1" href="#" id="sd_react_tab_dislike">
                            <i class="ki-duotone ki-dislike-2 fs-6 text-danger"><span class="path1"></span><span class="path2"></span></i>
                            <span class="badge badge-light-danger ms-1 fs-9" id="sd_react_dislike_cnt">0</span>
                        </a>
                    </li>
                </ul>
                <!--end::Tabs-->
                <div id="sd_reactions_list" style="max-height:320px;overflow-y:auto;">
                    <div class="text-center py-8 text-muted fs-8" id="sd_react_empty">No reactions yet.</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Reactions Modal-->

<!--begin::Photo Viewer Modal-->
<div class="modal fade" id="sd_photo_viewer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark border-0" style="border-radius:.75rem;overflow:hidden;">
            <div class="modal-header border-0 py-2 px-4 bg-dark">
                <span class="text-white fs-8 opacity-75" id="sd_viewer_counter"></span>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center position-relative" style="min-height:300px;">
                <button class="btn btn-icon btn-dark border-0 position-absolute start-0 top-50 translate-middle-y ms-2"
                        id="sd_viewer_prev" style="z-index:10;opacity:.8;">
                    <i class="ki-duotone ki-arrow-left fs-2"><span class="path1"></span><span class="path2"></span></i>
                </button>
                <img id="sd_viewer_img" src="" alt=""
                     style="max-height:80vh;max-width:100%;object-fit:contain;" />
                <button class="btn btn-icon btn-dark border-0 position-absolute end-0 top-50 translate-middle-y me-2"
                        id="sd_viewer_next" style="z-index:10;opacity:.8;">
                    <i class="ki-duotone ki-arrow-right fs-2"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Photo Viewer Modal-->

<style>
/* Photo grid */
.sd-photo-grid { display:grid; gap:3px; margin-top:.75rem; border-radius:.5rem; overflow:hidden; }
.sd-photo-grid-1 { grid-template-columns:1fr; }
.sd-photo-grid-2 { grid-template-columns:repeat(2,1fr); }
.sd-photo-grid-3 { grid-template-columns:repeat(3,1fr); }
.sd-photo-grid-4 { grid-template-columns:repeat(4,1fr); }
.sd-photo-item   { position:relative; aspect-ratio:1; cursor:pointer; overflow:hidden; }
.sd-photo-grid-1 .sd-photo-item { aspect-ratio:unset; display:flex; align-items:center; justify-content:center; background:#f5f8fa; }
.sd-photo-grid-1 .sd-photo-thumb { width:auto; max-width:100%; height:auto; max-height:480px; object-fit:contain; }
.sd-photo-thumb  { width:100%; height:100%; object-fit:cover; display:block; transition:opacity .15s; }
.sd-photo-item:hover .sd-photo-thumb { opacity:.88; }
.sd-photo-more-overlay {
    position:absolute; inset:0;
    background:rgba(0,0,0,.55);
    display:flex; align-items:center; justify-content:center;
}
.sd-photo-more-overlay span { color:#fff; font-size:1.2rem; font-weight:700; }

/* Post & comment cards */
.sd-post-card { transition: box-shadow .2s; }
.sd-post-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.08) !important; }
.sd-comment { padding:.25rem 0; }
.sd-post-react.sd-reacted,
.sd-comment-react.sd-reacted,
.sd-reply-react.sd-reacted { font-weight: 700 !important; }

/* @mention highlight in reply text */
.sd-reply .sd-mention, .sd-comment .sd-mention { color: #3b82f6; font-weight: 600; }

/* Photo upload preview */
.sd-preview-wrap { position:relative; width:76px; height:76px; }
.sd-preview-wrap img { width:76px; height:76px; object-fit:cover; border-radius:6px; display:block; }
.sd-preview-remove {
    position:absolute; top:2px; right:2px; width:18px; height:18px;
    background:#ef4444; border:none; border-radius:50%; color:#fff;
    font-size:10px; line-height:18px; text-align:center; cursor:pointer; padding:0;
}
</style>

<script>
const SD_POST_URL    = '<?= $sdPostUrl ?? '' ?>';
const SD_LIKE_BASE   = '<?= base_url('classroom/subject-discussion/') ?>';
const SD_COMMENT_BASE= '<?= base_url('classroom/subject-discussion/') ?>';
const SD_CLIKE_BASE  = '<?= base_url('classroom/subject-discussion/comment/') ?>';
const SD_REPLY_BASE  = '<?= base_url('classroom/subject-discussion/comment/') ?>';
const SD_RLIKE_BASE  = '<?= base_url('classroom/subject-discussion/reply/') ?>';
const SD_UPLOAD_BASE = '<?= base_url('uploads/subject_discussion/') ?>';
const SD_PROF_BASE   = '<?= base_url('uploads/profilePhoto/') ?>';
const SD_ME_ID       = <?= (int)$sessionUserId ?>;
const SD_ME_NAME     = '<?= esc($sessionFname ?? 'Teacher', 'js') ?>';
const SD_ME_PHOTO    = '<?= !empty($sessionPhotoUrl) ? esc($sessionPhotoUrl, 'js') : '' ?>';

// ── Utils ──────────────────────────────────────────────────────────────
function sdTimeAgo(dt) {
    const diff = Math.floor((Date.now() - new Date(dt.replace(' ','T'))) / 1000);
    if (diff < 60)    return 'just now';
    if (diff < 3600)  return Math.floor(diff/60)   + 'm ago';
    if (diff < 86400) return Math.floor(diff/3600)  + 'h ago';
    if (diff < 604800)return Math.floor(diff/86400) + 'd ago';
    return new Date(dt.replace(' ','T')).toLocaleDateString();
}

function sdEsc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function sdFormatText(s) {
    return sdEsc(s).replace(/@(\S+)/g, '<span class="sd-mention">@$1</span>');
}

function sdAvatar(photo, name, size) {
    if (photo) {
        const src = photo.startsWith('http') ? photo : (SD_PROF_BASE + photo);
        return `<img src="${src}" class="rounded-circle flex-shrink-0" style="width:${size}px;height:${size}px;object-fit:cover;" />`;
    }
    const words    = name.trim().split(/\s+/);
    const initials = words.map(w => w[0]).join('').slice(0,2).toUpperCase() || '?';
    const palette  = ['primary','success','info','warning','danger'];
    const col      = palette[Math.abs(name.split('').reduce((a,c)=>a+c.charCodeAt(0),0)) % 5];
    const fs       = size >= 40 ? 7 : 9;
    return `<div class="symbol symbol-${size}px flex-shrink-0"><div class="symbol-label bg-light-${col} fw-bold text-${col} fs-${fs}">${initials}</div></div>`;
}

function sdPhotoGrid(photos, sdId) {
    if (!photos || !photos.length) return '';
    const total = photos.length;
    const show  = Math.min(total, 4);
    const extra = total > 4 ? total - 4 : 0;
    const cols  = total===1?1:total===2?2:total===3?3:4;
    const all   = JSON.stringify(photos.map(p => p.photo_path)).replace(/'/g,'&#39;');
    let html = `<div class="sd-photo-grid sd-photo-grid-${cols} mt-3">`;
    for (let i = 0; i < show; i++) {
        const p = photos[i].photo_path;
        html += `<div class="sd-photo-item" data-index="${i}" data-photos='${all}' onclick="sdOpenViewer(this)">`;
        html += `<img src="${SD_UPLOAD_BASE}${p}" alt="" class="sd-photo-thumb" />`;
        if (i === 3 && extra > 0) {
            html += `<div class="sd-photo-more-overlay"><span>+${extra} more</span></div>`;
        }
        html += `</div>`;
    }
    html += `</div>`;
    return html;
}

// ── Photo viewer ──────────────────────────────────────────────────────
let sdViewerPhotos = [], sdViewerIdx = 0;

function sdOpenViewer(el) {
    sdViewerPhotos = JSON.parse(el.dataset.photos);
    sdViewerIdx    = parseInt(el.dataset.index);
    sdUpdateViewer();
    new bootstrap.Modal(document.getElementById('sd_photo_viewer')).show();
}

function sdUpdateViewer() {
    const total = sdViewerPhotos.length;
    document.getElementById('sd_viewer_img').src = SD_UPLOAD_BASE + sdViewerPhotos[sdViewerIdx];
    document.getElementById('sd_viewer_counter').textContent = (sdViewerIdx+1) + ' / ' + total;
    document.getElementById('sd_viewer_prev').style.opacity = sdViewerIdx > 0 ? '0.8' : '0.2';
    document.getElementById('sd_viewer_next').style.opacity = sdViewerIdx < total-1 ? '0.8' : '0.2';
}

document.getElementById('sd_viewer_prev').addEventListener('click', function() {
    if (sdViewerIdx > 0) { sdViewerIdx--; sdUpdateViewer(); }
});
document.getElementById('sd_viewer_next').addEventListener('click', function() {
    if (sdViewerIdx < sdViewerPhotos.length-1) { sdViewerIdx++; sdUpdateViewer(); }
});

// Keyboard navigation in viewer
document.getElementById('sd_photo_viewer').addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft')  { if (sdViewerIdx > 0) { sdViewerIdx--; sdUpdateViewer(); } }
    if (e.key === 'ArrowRight') { if (sdViewerIdx < sdViewerPhotos.length-1) { sdViewerIdx++; sdUpdateViewer(); } }
});

// ── Render templates ──────────────────────────────────────────────────
function renderReply(r) {
    const isOwn  = r.author_id == SD_ME_ID;
    const rLike  = (r.user_reaction==='like')    ? 'text-primary fw-bold sd-reacted' : 'text-muted';
    const rDlike = (r.user_reaction==='dislike') ? 'text-danger fw-bold sd-reacted'  : 'text-muted';
    return `<div class="sd-reply d-flex align-items-start gap-2 mb-2" id="sd_reply_${r.sdcr_id}">
        ${sdAvatar(r.author_photo||'', r.author_name, 28)}
        <div class="flex-grow-1 min-w-0">
            <div class="p-2 px-3 rounded-3" style="background:#f8f9fa;">
                <div class="fw-bold text-gray-800 fs-9 mb-1">${sdEsc(r.author_name)}</div>
                <div class="text-gray-700 fs-9 lh-lg" style="white-space:pre-line;">${sdFormatText(r.reply)}</div>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2 mt-1 ms-1">
                <span class="text-muted" style="font-size:.68rem;">${sdTimeAgo(r.created_at)}</span>
                <button class="btn btn-link p-0 fs-9 sd-reply-react ${rLike}" data-id="${r.sdcr_id}" data-type="like">
                    <i class="ki-duotone ki-like-2 fs-7"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-rlike-count">${r.like_count||0}</span>
                </button>
                <button class="btn btn-link p-0 fs-9 sd-reply-react ${rDlike}" data-id="${r.sdcr_id}" data-type="dislike">
                    <i class="ki-duotone ki-dislike-2 fs-7"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-rdislike-count">${r.dislike_count||0}</span>
                </button>
                <button class="btn btn-link p-0 text-primary fs-9 sd-reply-on-reply" data-comment-id="${r.sdc_id_fk||''}" data-mention="${sdEsc(r.author_name)}">Reply</button>
                <button class="btn btn-link p-0 text-muted fs-9 sd-view-reactions" data-id="${r.sdcr_id}" data-scope="reply">Reactions</button>
                ${isOwn ? `<button class="btn btn-link p-0 text-danger fs-9 sd-reply-delete" data-id="${r.sdcr_id}">Delete</button>` : ''}
            </div>
        </div>
    </div>`;
}

function renderComment(c) {
    const isOwn  = c.author_id == SD_ME_ID;
    const cLike  = (c.user_reaction==='like')    ? 'text-primary fw-bold sd-reacted' : 'text-muted';
    const cDlike = (c.user_reaction==='dislike') ? 'text-danger fw-bold sd-reacted'  : 'text-muted';
    const replies= (c.replies||[]).map(renderReply).join('');
    return `<div class="sd-comment d-flex align-items-start gap-2" id="sd_comment_${c.sdc_id}" data-comment-id="${c.sdc_id}">
        ${sdAvatar(c.author_photo||'', c.author_name, 32)}
        <div class="flex-grow-1 min-w-0">
            <div class="p-3 rounded-3" style="background:#f0f4ff;">
                <div class="fw-bold text-gray-800 fs-8 mb-1">${sdEsc(c.author_name)}</div>
                <div class="text-gray-700 fs-8 lh-lg" style="white-space:pre-line;">${sdFormatText(c.comment)}</div>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-3 mt-1 ms-1">
                <span class="text-muted" style="font-size:.72rem;">${sdTimeAgo(c.created_at)}</span>
                <button class="btn btn-link btn-sm p-0 fs-8 sd-comment-react ${cLike}" data-id="${c.sdc_id}" data-type="like">
                    <i class="ki-duotone ki-like-2 fs-6"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-clike-count">${c.like_count||0}</span>
                </button>
                <button class="btn btn-link btn-sm p-0 fs-8 sd-comment-react ${cDlike}" data-id="${c.sdc_id}" data-type="dislike">
                    <i class="ki-duotone ki-dislike-2 fs-6"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-cdislike-count">${c.dislike_count||0}</span>
                </button>
                <button class="btn btn-link btn-sm p-0 text-primary fs-8 sd-reply-toggle" data-comment-id="${c.sdc_id}">Reply</button>
                <button class="btn btn-link btn-sm p-0 text-muted fs-9 sd-view-reactions" data-id="${c.sdc_id}" data-scope="comment">Reactions</button>
                ${isOwn ? `<button class="btn btn-link btn-sm p-0 text-danger fs-8 sd-comment-delete" data-id="${c.sdc_id}">Delete</button>` : ''}
            </div>
            <div class="ms-1 mt-2" id="sd_replies_${c.sdc_id}">
                ${replies}
                <div class="sd-reply-form d-none mt-2" id="sd_reply_form_${c.sdc_id}">
                    <div class="d-flex align-items-start gap-2">
                        ${sdAvatar(SD_ME_PHOTO, SD_ME_NAME, 28)}
                        <div class="flex-grow-1">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm sd-reply-input"
                                       id="sd_reply_input_${c.sdc_id}" placeholder="Write a reply..." maxlength="1000" />
                                <button class="btn btn-sm btn-primary sd-reply-submit" data-comment-id="${c.sdc_id}">Reply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
}

function renderPost(p) {
    const isOwn  = p.author_id == SD_ME_ID;
    const pLike  = (p.user_reaction==='like')    ? 'btn-light-primary sd-reacted' : 'btn-light';
    const pDlike = (p.user_reaction==='dislike') ? 'btn-light-danger sd-reacted'  : 'btn-light';
    const grid   = sdPhotoGrid(p.photos||[], p.sd_id);
    const msg    = p.message ? `<div class="text-gray-700 fs-7 lh-lg mb-3" style="white-space:pre-line;">${sdEsc(p.message)}</div>` : '';
    const delBtn = isOwn
        ? `<button class="btn btn-sm btn-icon btn-light-danger sd-delete-post" data-id="${p.sd_id}" title="Delete post" style="width:30px;height:30px;">
               <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
           </button>` : '';
    const sessionAvatar = SD_ME_PHOTO ? `<img src="${SD_ME_PHOTO}" class="rounded-circle flex-shrink-0" style="width:32px;height:32px;object-fit:cover;" />`
                                       : sdAvatar('', SD_ME_NAME, 32);
    return `<div class="card border-0 shadow-sm mb-4 sd-post-card" id="sd_post_${p.sd_id}" data-post-id="${p.sd_id}">
        <div class="card-body p-5">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-start gap-3">
                    ${sdAvatar(p.author_photo||'', p.author_name, 40)}
                    <div>
                        <div class="fw-bold text-gray-800 fs-7">${sdEsc(p.author_name)}</div>
                        <div class="text-muted fs-9">${sdTimeAgo(p.created_at)}</div>
                    </div>
                </div>
                ${delBtn}
            </div>
            ${msg}
            ${grid}
            <div class="d-flex align-items-center gap-2 mt-3 mb-1 px-1 sd-react-summary" style="display:none!important;"></div>
            <div class="d-flex align-items-center gap-2 pt-2 mt-1" style="border-top:1px solid #f1f1f4;">
                <button class="btn btn-sm ${pLike} sd-post-react d-flex align-items-center gap-1" data-id="${p.sd_id}" data-type="like">
                    <i class="ki-duotone ki-like-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-like-count">${p.like_count||0}</span>
                </button>
                <button class="btn btn-sm ${pDlike} sd-post-react d-flex align-items-center gap-1" data-id="${p.sd_id}" data-type="dislike">
                    <i class="ki-duotone ki-dislike-2 fs-5"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-dislike-count">${p.dislike_count||0}</span>
                </button>
                <button class="btn btn-sm btn-light sd-toggle-comments d-flex align-items-center gap-1 ms-1" data-id="${p.sd_id}">
                    <i class="ki-duotone ki-message fs-5"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-comment-count">${p.comment_count||0}</span>
                    <span class="ms-1 fs-8">Comments</span>
                </button>
            </div>
            <div class="sd-comments-section mt-4 d-none" id="sd_comments_${p.sd_id}">
                <div class="d-flex flex-column gap-3 mb-3" id="sd_comment_list_${p.sd_id}"></div>
                <div class="d-flex align-items-start gap-2 pt-3" style="border-top:1px solid #f8f9fa;">
                    ${sessionAvatar}
                    <div class="flex-grow-1">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm sd-comment-input"
                                   id="sd_comment_input_${p.sd_id}" placeholder="Write a comment..." maxlength="1000" />
                            <button class="btn btn-sm btn-primary sd-comment-submit" data-id="${p.sd_id}">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
}

// ── Photo upload preview & post submission (teacher/student post creator) ────
<?php if ($canPost ?? true): ?>
let sdSelectedFiles = [];

document.getElementById('sd_photos').addEventListener('change', function() {
    const files = Array.from(this.files);
    if (files.length > 10) {
        Swal.fire({ title:'Too many photos', text:'Maximum 10 photos allowed.', icon:'warning',
            buttonsStyling:false, confirmButtonText:'OK', customClass:{confirmButton:'btn btn-warning'} });
        this.value = '';
        sdSelectedFiles = [];
        document.getElementById('sd_photo_preview').innerHTML = '';
        document.getElementById('sd_photo_preview').style.display = 'none';
        return;
    }
    sdSelectedFiles = files;
    const preview = document.getElementById('sd_photo_preview');
    preview.innerHTML = '';
    if (!files.length) { preview.style.display = 'none'; return; }
    preview.style.display = 'flex';
    files.forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.createElement('div');
            wrap.className = 'sd-preview-wrap';
            wrap.dataset.idx = i;
            wrap.innerHTML = `<img src="${e.target.result}" alt="" />
                <button type="button" class="sd-preview-remove" data-idx="${i}" title="Remove">×</button>`;
            preview.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
});

document.getElementById('sd_photo_preview').addEventListener('click', function(e) {
    const btn = e.target.closest('.sd-preview-remove');
    if (!btn) return;
    const idx = parseInt(btn.dataset.idx);
    sdSelectedFiles = sdSelectedFiles.filter((_, i) => i !== idx);
    const dt = new DataTransfer();
    sdSelectedFiles.forEach(f => dt.items.add(f));
    document.getElementById('sd_photos').files = dt.files;
    document.getElementById('sd_photo_preview').innerHTML = '';
    if (!sdSelectedFiles.length) {
        document.getElementById('sd_photo_preview').style.display = 'none';
        return;
    }
    document.getElementById('sd_photo_preview').style.display = 'flex';
    sdSelectedFiles.forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e2 => {
            const wrap = document.createElement('div');
            wrap.className = 'sd-preview-wrap';
            wrap.innerHTML = `<img src="${e2.target.result}" alt="" />
                <button type="button" class="sd-preview-remove" data-idx="${i}" title="Remove">×</button>`;
            document.getElementById('sd_photo_preview').appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
});

// ── Post submission ───────────────────────────────────────────────────
document.getElementById('btn_sd_post').addEventListener('click', function() {
    const btn     = this;
    const message = document.getElementById('sd_message').value.trim();
    const photos  = document.getElementById('sd_photos').files;

    if (!message && !photos.length) {
        Swal.fire({ title:'Nothing to post', text:'Write a message or upload at least one photo.', icon:'warning',
            buttonsStyling:false, confirmButtonText:'OK', customClass:{confirmButton:'btn btn-warning'} });
        return;
    }

    btn.setAttribute('data-kt-indicator','on'); btn.disabled = true;

    const fd = new FormData();
    fd.append('message', message);
    for (const file of photos) fd.append('photos[]', file);

    $.ajax({
        url: SD_POST_URL, type: 'POST', data: fd, processData: false, contentType: false,
        success(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            if (res.success) {
                document.getElementById('sd_message').value = '';
                document.getElementById('sd_photos').value  = '';
                sdSelectedFiles = [];
                const prev = document.getElementById('sd_photo_preview');
                prev.innerHTML = ''; prev.style.display = 'none';

                const empty = document.getElementById('sd_empty_state');
                if (empty) empty.remove();

                document.getElementById('sd_feed').insertAdjacentHTML('afterbegin', renderPost(res.post));

                const cnt = document.getElementById('sd_post_count');
                if (cnt) cnt.textContent = parseInt(cnt.textContent||'0') + 1;
            } else {
                Swal.fire({ title:'Failed', text:res.message, icon:'error',
                    buttonsStyling:false, confirmButtonText:'Close', customClass:{confirmButton:'btn btn-danger'} });
            }
        },
        error() {
            btn.removeAttribute('data-kt-indicator'); btn.disabled = false;
            Swal.fire({ title:'Error', text:'An error occurred.', icon:'error',
                buttonsStyling:false, confirmButtonText:'Close', customClass:{confirmButton:'btn btn-danger'} });
        }
    });
});

// Post on Enter (Ctrl+Enter) in textarea
document.getElementById('sd_message').addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        document.getElementById('btn_sd_post').click();
    }
});

<?php endif; // canPost ?>

// ── Toggle comments ───────────────────────────────────────────────────
$(document).on('click', '.sd-toggle-comments', function() {
    const id  = $(this).data('id');
    const sec = $(`#sd_comments_${id}`);
    sec.toggleClass('d-none');
    if (!sec.hasClass('d-none')) {
        $(`#sd_comment_input_${id}`).focus();
    }
});

// ── Delete post ───────────────────────────────────────────────────────
$(document).on('click', '.sd-delete-post', function() {
    const id = $(this).data('id');
    Swal.fire({
        title:'Delete this post?', text:'This cannot be undone.', icon:'warning',
        showCancelButton:true, buttonsStyling:false,
        confirmButtonText:'Yes, delete', cancelButtonText:'Cancel',
        customClass:{confirmButton:'btn btn-danger me-2', cancelButton:'btn btn-light'}
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(SD_LIKE_BASE + id + '/delete', {}, res => {
            if (res.success) {
                $(`#sd_post_${id}`).fadeOut(250, function(){ $(this).remove(); });
                const cnt = document.getElementById('sd_post_count');
                if (cnt) cnt.textContent = Math.max(0, parseInt(cnt.textContent||'0') - 1);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });
});

// ── Post like/dislike ─────────────────────────────────────────────────
$(document).on('click', '.sd-post-react', function() {
    const btn  = $(this);
    const id   = btn.data('id');
    const type = btn.data('type');
    const post = btn.closest('.sd-post-card');

    $.post(SD_LIKE_BASE + id + '/like', {type}, res => {
        if (!res.success) return;
        const likeBtn  = post.find('.sd-post-react[data-type="like"]');
        const dlikBtn  = post.find('.sd-post-react[data-type="dislike"]');

        likeBtn.find('.sd-like-count').text(res.likes);
        dlikBtn.find('.sd-dislike-count').text(res.dislikes);
        sdUpdateReactSummary(post, res.likes, res.dislikes, id);

        likeBtn.removeClass('btn-light-primary btn-light sd-reacted');
        dlikBtn.removeClass('btn-light-danger btn-light sd-reacted');

        if (res.reaction === 'like') {
            likeBtn.addClass('btn-light-primary sd-reacted');
            dlikBtn.addClass('btn-light');
        } else if (res.reaction === 'dislike') {
            likeBtn.addClass('btn-light');
            dlikBtn.addClass('btn-light-danger sd-reacted');
        } else {
            likeBtn.addClass('btn-light');
            dlikBtn.addClass('btn-light');
        }
    });
});

// ── Comment submit ────────────────────────────────────────────────────
$(document).on('click', '.sd-comment-submit', function() {
    const btn     = $(this);
    const id      = btn.data('id');
    const input   = $(`#sd_comment_input_${id}`);
    const comment = input.val().trim();
    if (!comment) { input.focus(); return; }

    btn.prop('disabled', true);
    $.post(SD_COMMENT_BASE + id + '/comment', {comment}, res => {
        btn.prop('disabled', false);
        if (res.success) {
            input.val('');
            $(`#sd_comment_list_${id}`).append(renderComment(res.comment));
            const cnt = $(`#sd_post_${id} .sd-comment-count`);
            cnt.text(parseInt(cnt.text()||'0') + 1);
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    }).fail(() => { btn.prop('disabled',false); });
});

// Enter key in comment input
$(document).on('keydown', '.sd-comment-input', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        $(this).closest('.input-group').find('.sd-comment-submit').click();
    }
});

// ── Comment like/dislike ──────────────────────────────────────────────
$(document).on('click', '.sd-comment-react', function() {
    const btn  = $(this);
    const id   = btn.data('id');
    const type = btn.data('type');
    const wrap = btn.closest('[data-comment-id]');

    $.post(SD_CLIKE_BASE + id + '/like', {type}, res => {
        if (!res.success) return;
        const lBtn = wrap.find('.sd-comment-react[data-type="like"]');
        const dBtn = wrap.find('.sd-comment-react[data-type="dislike"]');
        lBtn.find('.sd-clike-count').text(res.likes);
        dBtn.find('.sd-cdislike-count').text(res.dislikes);

        lBtn.removeClass('text-primary fw-bold sd-reacted text-muted');
        dBtn.removeClass('text-danger fw-bold sd-reacted text-muted');

        if (res.reaction === 'like')    { lBtn.addClass('text-primary fw-bold sd-reacted'); dBtn.addClass('text-muted'); }
        else if (res.reaction === 'dislike') { dBtn.addClass('text-danger fw-bold sd-reacted'); lBtn.addClass('text-muted'); }
        else { lBtn.addClass('text-muted'); dBtn.addClass('text-muted'); }
    });
});

// ── Delete comment ────────────────────────────────────────────────────
$(document).on('click', '.sd-comment-delete', function() {
    const id = $(this).data('id');
    Swal.fire({
        title:'Delete comment?', icon:'warning', showCancelButton:true,
        buttonsStyling:false,
        confirmButtonText:'Delete', cancelButtonText:'Cancel',
        customClass:{confirmButton:'btn btn-danger me-2', cancelButton:'btn btn-light'}
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(SD_CLIKE_BASE + id + '/delete', {}, res => {
            if (res.success) {
                const el = $(`#sd_comment_${id}`);
                const postCard = el.closest('.sd-post-card');
                el.fadeOut(200, function() { $(this).remove(); });
                const cnt = postCard.find('.sd-comment-count');
                cnt.text(Math.max(0, parseInt(cnt.text()||'0') - 1));
            }
        });
    });
});

// ── Reply toggle ──────────────────────────────────────────────────────
$(document).on('click', '.sd-reply-toggle', function() {
    const cid  = $(this).data('comment-id');
    const form = $(`#sd_reply_form_${cid}`);
    form.toggleClass('d-none');
    if (!form.hasClass('d-none')) form.find('input').focus();
});

// ── Reply submit ──────────────────────────────────────────────────────
$(document).on('click', '.sd-reply-submit', function() {
    const btn   = $(this);
    const cid   = btn.data('comment-id');
    const input = $(`#sd_reply_input_${cid}`);
    const reply = input.val().trim();
    if (!reply) { input.focus(); return; }

    btn.prop('disabled', true);
    $.post(SD_REPLY_BASE + cid + '/reply', {reply}, res => {
        btn.prop('disabled', false);
        if (res.success) {
            input.val('');
            res.reply.sdc_id_fk = cid;
            $(`#sd_reply_form_${cid}`).before(renderReply(res.reply));
            $(`#sd_reply_form_${cid}`).addClass('d-none');
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    }).fail(() => { btn.prop('disabled',false); });
});

// Enter key in reply input
$(document).on('keydown', '.sd-reply-input', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        $(this).closest('.input-group').find('.sd-reply-submit').click();
    }
});

// ── Reply like/dislike ────────────────────────────────────────────────
$(document).on('click', '.sd-reply-react', function() {
    const btn  = $(this);
    const id   = btn.data('id');
    const type = btn.data('type');
    const wrap = btn.closest('.sd-reply');

    $.post(SD_RLIKE_BASE + id + '/like', {type}, res => {
        if (!res.success) return;
        const lBtn = wrap.find('.sd-reply-react[data-type="like"]');
        const dBtn = wrap.find('.sd-reply-react[data-type="dislike"]');
        lBtn.find('.sd-rlike-count').text(res.likes);
        dBtn.find('.sd-rdislike-count').text(res.dislikes);

        lBtn.removeClass('text-primary fw-bold sd-reacted text-muted');
        dBtn.removeClass('text-danger fw-bold sd-reacted text-muted');

        if (res.reaction === 'like')    { lBtn.addClass('text-primary fw-bold sd-reacted'); dBtn.addClass('text-muted'); }
        else if (res.reaction === 'dislike') { dBtn.addClass('text-danger fw-bold sd-reacted'); lBtn.addClass('text-muted'); }
        else { lBtn.addClass('text-muted'); dBtn.addClass('text-muted'); }
    });
});

// ── Reply on reply (@mention) ─────────────────────────────────────────
$(document).on('click', '.sd-reply-on-reply', function() {
    const cid     = $(this).data('comment-id');
    const mention = $(this).data('mention');
    const form    = $(`#sd_reply_form_${cid}`);
    form.removeClass('d-none');
    const input = $(`#sd_reply_input_${cid}`);
    input.val('@' + mention + ' ');
    input.focus();
    const len = input.val().length;
    input[0].setSelectionRange(len, len);
    // Scroll form into view
    form[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
});

// ── Delete reply ──────────────────────────────────────────────────────
$(document).on('click', '.sd-reply-delete', function() {
    const id = $(this).data('id');
    Swal.fire({
        title:'Delete reply?', icon:'warning', showCancelButton:true,
        buttonsStyling:false,
        confirmButtonText:'Delete', cancelButtonText:'Cancel',
        customClass:{confirmButton:'btn btn-danger me-2', cancelButton:'btn btn-light'}
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(SD_RLIKE_BASE + id + '/delete', {}, res => {
            if (res.success) $(`#sd_reply_${id}`).fadeOut(200, function() { $(this).remove(); });
        });
    });
});

// ── Reactions summary row (shows icon counts + "See reactions" link) ──
function sdUpdateReactSummary(postEl, likes, dislikes, postId) {
    const row = postEl.find('.sd-react-summary');
    const total = likes + dislikes;
    if (total === 0) { row.hide(); return; }
    let html = '';
    if (likes    > 0) html += `<span class="d-flex align-items-center gap-1 text-muted fs-9"><i class="ki-duotone ki-like-2 fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i>${likes}</span>`;
    if (dislikes > 0) html += `<span class="d-flex align-items-center gap-1 text-muted fs-9"><i class="ki-duotone ki-dislike-2 fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i>${dislikes}</span>`;
    html += `<button class="btn btn-link p-0 text-muted fs-9 ms-auto text-hover-primary sd-view-reactions" data-id="${postId}" data-scope="post">See reactions</button>`;
    row.html(html).css('display','flex');
}

// ── Reactions modal ───────────────────────────────────────────────────
let sdReactionsCache = { all:[], likes:[], dislikes:[] };
let sdReactFilter    = 'all';

function sdRenderReactions() {
    const list = sdReactionsCache[sdReactFilter] || [];
    const el   = document.getElementById('sd_reactions_list');
    if (!list.length) {
        el.innerHTML = '<div class="text-center py-8 text-muted fs-8">No reactions yet.</div>';
        return;
    }
    el.innerHTML = list.map(r => {
        const src  = r.photo ? `${SD_PROF_BASE}${r.photo}` : '';
        const icon = r.like_type === 'like'
            ? `<i class="ki-duotone ki-like-2 fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i>`
            : `<i class="ki-duotone ki-dislike-2 fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i>`;
        const avatar = src
            ? `<img src="${src}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;" />`
            : `<div class="symbol symbol-36px"><div class="symbol-label bg-light-primary fw-bold text-primary fs-8">${sdEsc(r.name).slice(0,2).toUpperCase()}</div></div>`;
        return `<div class="d-flex align-items-center gap-3 py-2" style="border-bottom:1px solid #f8f9fa;">
            ${avatar}
            <span class="fw-semibold text-gray-700 fs-7 flex-grow-1">${sdEsc(r.name)}</span>
            ${icon}
        </div>`;
    }).join('');
}

$(document).on('click', '.sd-view-reactions', function() {
    const id    = $(this).data('id');
    const scope = $(this).data('scope') || 'post';
    const url   = scope === 'post'    ? SD_LIKE_BASE  + id + '/reactions'
                : scope === 'comment' ? SD_CLIKE_BASE + id + '/reactions'
                :                       SD_RLIKE_BASE  + id + '/reactions';

    document.getElementById('sd_reactions_list').innerHTML =
        '<div class="text-center py-8"><span class="spinner-border spinner-border-sm text-primary"></span></div>';
    sdReactFilter = 'all';
    $('#sd_react_tab_all').addClass('active');
    $('#sd_react_tab_like,#sd_react_tab_dislike').removeClass('active');

    new bootstrap.Modal(document.getElementById('sd_reactions_modal')).show();

    $.get(url, res => {
        if (!res.success) return;
        const all    = res.reactions || [];
        const likes  = all.filter(r => r.like_type === 'like');
        const dlikes = all.filter(r => r.like_type === 'dislike');
        sdReactionsCache = { all, likes, dislikes: dlikes };
        document.getElementById('sd_react_all_cnt').textContent     = all.length;
        document.getElementById('sd_react_like_cnt').textContent    = likes.length;
        document.getElementById('sd_react_dislike_cnt').textContent = dlikes.length;
        sdRenderReactions();
    });
});

// Reaction tab switching
$('#sd_react_tabs').on('click', 'a', function(e) {
    e.preventDefault();
    $('#sd_react_tabs a').removeClass('active');
    $(this).addClass('active');
    if (this.id === 'sd_react_tab_like')    sdReactFilter = 'likes';
    else if (this.id === 'sd_react_tab_dislike') sdReactFilter = 'dislikes';
    else sdReactFilter = 'all';
    sdRenderReactions();
});
</script>
