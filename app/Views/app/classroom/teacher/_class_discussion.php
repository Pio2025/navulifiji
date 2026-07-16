<?php
// $sdPfx       — unique prefix for all DOM IDs (defaults to 'cd'). Set per-classroom when embedding multiple times.
// $sdShowShared — when false, skip modals, CSS, and global JS (the parent page includes them once).
$sdPfx       = $sdPfx       ?? 'cd';
$sdShowShared = $sdShowShared ?? true;

if (!function_exists('cdAvatar')) {
    function cdAvatar(string $photo = '', string $name = '', int $size = 40): string {
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
}

if (!function_exists('cdPhotoGrid')) {
    function cdPhotoGrid(array $photos, int $cdId, string $pfx = 'cd'): string {
        $total = count($photos);
        if ($total === 0) return '';
        $show  = min($total, 4);
        $extra = $total > 4 ? $total - 4 : 0;
        $cols  = $total === 1 ? 1 : ($total === 2 ? 2 : ($total === 3 ? 3 : 4));
        $all   = json_encode(array_column($photos, 'photo_path'), JSON_HEX_APOS);
        $html  = '<div class="sd-photo-grid sd-photo-grid-' . $cols . ' mt-3">';
        for ($i = 0; $i < $show; $i++) {
            $path = $photos[$i]['photo_path'];
            $html .= '<div class="sd-photo-item" data-index="' . $i . '" data-photos=\'' . $all . '\' data-pfx="' . $pfx . '" onclick="cdOpenViewer(this)">';
            $html .= '<img src="' . base_url('uploads/class_discussion/' . $path) . '" alt="" class="sd-photo-thumb" />';
            if ($i === 3 && $extra > 0) {
                $html .= '<div class="sd-photo-more-overlay"><span>+' . $extra . ' more</span></div>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('cdRoleBadge')) {
    function cdRoleBadge(?int $catId, ?string $catName): string {
        if (!$catId || !$catName) return '';
        $color = match($catId) {
            3       => 'success',
            4       => 'primary',
            6       => 'info',
            default => 'secondary',
        };
        return '<span class="badge badge-light-' . $color . ' fs-9 ms-1">' . esc($catName) . '</span>';
    }
}

if (!function_exists('cdFormatTextPhp')) {
    function cdFormatTextPhp(string $text): string {
        return preg_replace('/@(\S+)/', '<span class="sd-mention">@$1</span>', nl2br(esc($text)));
    }
}

if (!function_exists('cdTimeAgo')) {
    function cdTimeAgo(string $dt): string {
        $diff = time() - strtotime($dt);
        if ($diff < 60)     return 'just now';
        if ($diff < 3600)   return floor($diff / 60) . 'm ago';
        if ($diff < 86400)  return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        return date('d M Y', strtotime($dt));
    }
}
?>

<!--begin::Class Discussion-->
<div class="d-flex align-items-center justify-content-between mb-5">
    <div class="d-flex align-items-center gap-2">
        <i class="ki-duotone ki-message-edit fs-2 text-primary me-1">
            <span class="path1"></span><span class="path2"></span>
        </i>
        <h4 class="fw-bold text-gray-800 mb-0">Class Discussion</h4>
        <span class="badge badge-light-primary ms-2 fs-8" id="<?= $sdPfx ?>_post_count"><?= count($discussions ?? []) ?></span>
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
                <?= cdAvatar('', $sessionFname, 40) ?>
                <?php endif; ?>
            </div>
            <div class="flex-grow-1">
                <textarea class="form-control border-0 bg-light-secondary fs-7" id="<?= $sdPfx ?>_message"
                          placeholder="Share something with your class..." rows="2"
                          style="resize:none;border-radius:.75rem;padding:.75rem 1rem;"></textarea>
                <div id="<?= $sdPfx ?>_photo_preview" class="d-flex flex-wrap gap-2 mt-3" style="display:none!important;"></div>
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <label for="<?= $sdPfx ?>_photos" class="btn btn-sm btn-light-success mb-0 cursor-pointer">
                        <i class="ki-duotone ki-picture fs-5 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Photos <span class="text-muted fw-normal fs-9">(max 10, images only)</span>
                    </label>
                    <input type="file" id="<?= $sdPfx ?>_photos" accept="image/*" multiple class="d-none" />
                    <button type="button" class="btn btn-sm btn-primary" id="<?= $sdPfx ?>_btn_post">
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
<div id="<?= $sdPfx ?>_feed">
<?php if (empty($discussions)): ?>
<div class="text-center py-14 text-muted" id="<?= $sdPfx ?>_empty_state">
    <i class="ki-duotone ki-message-edit fs-4x text-gray-200 mb-3">
        <span class="path1"></span><span class="path2"></span>
    </i>
    <div class="fs-6 fw-semibold mb-1">No posts yet</div>
    <div class="fs-8"><?= ($canPost ?? true) ? 'Be the first to share something with your class.' : 'No posts have been shared in this classroom.' ?></div>
</div>
<?php else: ?>
<?php foreach ($discussions as $post):
    $isOwnPost = (int)$post['author_id'] === (int)$sessionUserId;
?>
<div class="card border-0 shadow-sm mb-4 sd-post-card" id="<?= $sdPfx ?>_post_<?= $post['cd_id'] ?>" data-post-id="<?= $post['cd_id'] ?>" data-pfx="<?= $sdPfx ?>">
    <div class="card-body p-5">
        <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="d-flex align-items-start gap-3">
                <?= cdAvatar($post['author_photo'] ?? '', $post['author_name'] ?? '', 40) ?>
                <div>
                    <div class="d-flex align-items-center gap-1">
                        <span class="fw-bold text-gray-800 fs-7"><?= esc($post['author_name']) ?></span>
                        <?= cdRoleBadge((int)($post['author_role_cat_id'] ?? 0), $post['author_role_cat_name'] ?? '') ?>
                    </div>
                    <div class="text-muted fs-9"><?= cdTimeAgo($post['created_at']) ?></div>
                </div>
            </div>
            <?php if ($isOwnPost && ($canPost ?? true)): ?>
            <button class="btn btn-sm btn-icon btn-light-danger sd-delete-post" data-id="<?= $post['cd_id'] ?>" data-pfx="<?= $sdPfx ?>"
                    title="Delete post" style="width:30px;height:30px;">
                <i class="ki-duotone ki-trash fs-5">
                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    <span class="path4"></span><span class="path5"></span>
                </i>
            </button>
            <?php endif; ?>
        </div>

        <?php if (!empty($post['message'])): ?>
        <div class="text-gray-700 fs-7 lh-lg mb-3" style="white-space:pre-line;"><?= nl2br(esc($post['message'])) ?></div>
        <?php endif; ?>

        <?= cdPhotoGrid($post['photos'] ?? [], $post['cd_id'], $sdPfx) ?>

        <?php
            $likeActive    = ($post['user_reaction'] ?? '') === 'like'    ? 'btn-light-primary sd-reacted' : 'btn-light';
            $dislikeActive = ($post['user_reaction'] ?? '') === 'dislike' ? 'btn-light-danger sd-reacted'  : 'btn-light';
            $totalReactions = (int)$post['like_count'] + (int)$post['dislike_count'];
        ?>
        <?php if ($totalReactions > 0): ?>
        <div class="d-flex align-items-center gap-2 mt-3 mb-1 px-1">
            <?php if ((int)$post['like_count'] > 0): ?>
            <span class="d-flex align-items-center gap-1 text-muted fs-9">
                <i class="ki-duotone ki-like fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i>
                <?= (int)$post['like_count'] ?>
            </span>
            <?php endif; ?>
            <?php if ((int)$post['dislike_count'] > 0): ?>
            <span class="d-flex align-items-center gap-1 text-muted fs-9">
                <i class="ki-duotone ki-dislike fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i>
                <?= (int)$post['dislike_count'] ?>
            </span>
            <?php endif; ?>
            <button class="btn btn-link p-0 text-muted fs-9 ms-auto text-hover-primary sd-view-reactions"
                    data-id="<?= $post['cd_id'] ?>" data-scope="post">See reactions</button>
        </div>
        <?php endif; ?>
        <div class="d-flex align-items-center gap-2 pt-2 mt-1" style="border-top:1px solid #f1f1f4;">
            <button class="btn btn-sm <?= $likeActive ?> sd-post-react d-flex align-items-center gap-1"
                    data-id="<?= $post['cd_id'] ?>" data-type="like" data-pfx="<?= $sdPfx ?>">
                <i class="ki-duotone ki-like fs-5"><span class="path1"></span><span class="path2"></span></i>
                <span class="sd-like-count"><?= (int)$post['like_count'] ?></span>
            </button>
            <button class="btn btn-sm <?= $dislikeActive ?> sd-post-react d-flex align-items-center gap-1"
                    data-id="<?= $post['cd_id'] ?>" data-type="dislike" data-pfx="<?= $sdPfx ?>">
                <i class="ki-duotone ki-dislike fs-5"><span class="path1"></span><span class="path2"></span></i>
                <span class="sd-dislike-count"><?= (int)$post['dislike_count'] ?></span>
            </button>
            <button class="btn btn-sm btn-light sd-toggle-comments d-flex align-items-center gap-1 ms-1"
                    data-id="<?= $post['cd_id'] ?>" data-pfx="<?= $sdPfx ?>">
                <i class="ki-duotone ki-message fs-5"><span class="path1"></span><span class="path2"></span></i>
                <span class="sd-comment-count"><?= (int)$post['comment_count'] ?></span>
                <span class="ms-1 fs-8">Comment<?= (int)$post['comment_count'] !== 1 ? 's' : '' ?></span>
            </button>
        </div>

        <div class="sd-comments-section mt-4 d-none" id="<?= $sdPfx ?>_comments_<?= $post['cd_id'] ?>">
            <div class="d-flex flex-column gap-3 mb-3" id="<?= $sdPfx ?>_comment_list_<?= $post['cd_id'] ?>">
            <?php foreach ($post['comments'] as $comment):
                $isOwnComment   = (int)$comment['author_id'] === (int)$sessionUserId;
                $cLikeActive    = ($comment['user_reaction'] ?? '') === 'like'    ? 'text-primary fw-bold sd-reacted' : 'text-muted';
                $cDislikeActive = ($comment['user_reaction'] ?? '') === 'dislike' ? 'text-danger fw-bold sd-reacted'  : 'text-muted';
            ?>
            <div class="sd-comment d-flex align-items-start gap-2" id="<?= $sdPfx ?>_comment_<?= $comment['cdc_id'] ?>" data-comment-id="<?= $comment['cdc_id'] ?>">
                <?= cdAvatar($comment['author_photo'] ?? '', $comment['author_name'] ?? '', 32) ?>
                <div class="flex-grow-1 min-w-0">
                    <div class="p-3 rounded-3" style="background:#f0f4ff;">
                        <div class="d-flex align-items-center gap-1 mb-1">
                            <span class="fw-bold text-gray-800 fs-8"><?= esc($comment['author_name']) ?></span>
                            <?= cdRoleBadge((int)($comment['author_role_cat_id'] ?? 0), $comment['author_role_cat_name'] ?? '') ?>
                        </div>
                        <div class="text-gray-700 fs-8 lh-lg" style="white-space:pre-line;"><?= cdFormatTextPhp($comment['comment']) ?></div>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-3 mt-1 ms-1">
                        <span class="text-muted" style="font-size:.72rem;"><?= cdTimeAgo($comment['created_at']) ?></span>
                        <button class="btn btn-link btn-sm p-0 fs-8 sd-comment-react <?= $cLikeActive ?>"
                                data-id="<?= $comment['cdc_id'] ?>" data-type="like">
                            <i class="ki-duotone ki-like fs-6"><span class="path1"></span><span class="path2"></span></i>
                            <span class="sd-clike-count"><?= (int)$comment['like_count'] ?></span>
                        </button>
                        <button class="btn btn-link btn-sm p-0 fs-8 sd-comment-react <?= $cDislikeActive ?>"
                                data-id="<?= $comment['cdc_id'] ?>" data-type="dislike">
                            <i class="ki-duotone ki-dislike fs-6"><span class="path1"></span><span class="path2"></span></i>
                            <span class="sd-cdislike-count"><?= (int)$comment['dislike_count'] ?></span>
                        </button>
                        <?php if ($canPost ?? true): ?>
                        <button class="btn btn-link btn-sm p-0 text-primary fs-8 sd-reply-toggle"
                                data-comment-id="<?= $comment['cdc_id'] ?>" data-pfx="<?= $sdPfx ?>">Reply</button>
                        <?php endif; ?>
                        <button class="btn btn-link btn-sm p-0 text-muted fs-9 sd-view-reactions"
                                data-id="<?= $comment['cdc_id'] ?>" data-scope="comment">Reactions</button>
                        <?php if ($isOwnComment && ($canPost ?? true)): ?>
                        <button class="btn btn-link btn-sm p-0 text-danger fs-8 sd-comment-delete"
                                data-id="<?= $comment['cdc_id'] ?>">Delete</button>
                        <?php endif; ?>
                    </div>

                    <div class="ms-1 mt-2" id="<?= $sdPfx ?>_replies_<?= $comment['cdc_id'] ?>">
                    <?php foreach ($comment['replies'] as $reply):
                        $isOwnReply     = (int)$reply['author_id'] === (int)$sessionUserId;
                        $rLikeActive    = ($reply['user_reaction'] ?? '') === 'like'    ? 'text-primary fw-bold sd-reacted' : 'text-muted';
                        $rDislikeActive = ($reply['user_reaction'] ?? '') === 'dislike' ? 'text-danger fw-bold sd-reacted'  : 'text-muted';
                    ?>
                    <div class="sd-reply d-flex align-items-start gap-2 mb-2" id="<?= $sdPfx ?>_reply_<?= $reply['cdcr_id'] ?>">
                        <?= cdAvatar($reply['author_photo'] ?? '', $reply['author_name'] ?? '', 28) ?>
                        <div class="flex-grow-1 min-w-0">
                            <div class="p-2 px-3 rounded-3" style="background:#f8f9fa;">
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <span class="fw-bold text-gray-800 fs-9"><?= esc($reply['author_name']) ?></span>
                                    <?= cdRoleBadge((int)($reply['author_role_cat_id'] ?? 0), $reply['author_role_cat_name'] ?? '') ?>
                                </div>
                                <div class="text-gray-700 fs-9 lh-lg" style="white-space:pre-line;"><?= cdFormatTextPhp($reply['reply']) ?></div>
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-2 mt-1 ms-1">
                                <span class="text-muted" style="font-size:.68rem;"><?= cdTimeAgo($reply['created_at']) ?></span>
                                <button class="btn btn-link p-0 fs-9 sd-reply-react <?= $rLikeActive ?>"
                                        data-id="<?= $reply['cdcr_id'] ?>" data-type="like">
                                    <i class="ki-duotone ki-like fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="sd-rlike-count"><?= (int)$reply['like_count'] ?></span>
                                </button>
                                <button class="btn btn-link p-0 fs-9 sd-reply-react <?= $rDislikeActive ?>"
                                        data-id="<?= $reply['cdcr_id'] ?>" data-type="dislike">
                                    <i class="ki-duotone ki-dislike fs-7"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="sd-rdislike-count"><?= (int)$reply['dislike_count'] ?></span>
                                </button>
                                <button class="btn btn-link p-0 text-muted fs-9 sd-view-reactions"
                                        data-id="<?= $reply['cdcr_id'] ?>" data-scope="reply">Reactions</button>
                                <?php if ($canPost ?? true): ?>
                                <button class="btn btn-link p-0 text-primary fs-9 fw-semibold sd-reply-on-reply"
                                        data-comment-id="<?= $comment['cdc_id'] ?>" data-pfx="<?= $sdPfx ?>"
                                        data-mention="<?= esc($reply['author_name']) ?>">Reply</button>
                                <?php endif; ?>
                                <?php if ($isOwnReply && ($canPost ?? true)): ?>
                                <button class="btn btn-link p-0 text-danger fs-9 sd-reply-delete"
                                        data-id="<?= $reply['cdcr_id'] ?>">Delete</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if ($canPost ?? true): ?>
                    <div class="sd-reply-form d-none mt-2" id="<?= $sdPfx ?>_reply_form_<?= $comment['cdc_id'] ?>">
                        <div class="d-flex align-items-start gap-2">
                            <?php if (!empty($sessionPhotoUrl)): ?>
                            <img src="<?= $sessionPhotoUrl ?>" class="rounded-circle flex-shrink-0"
                                 style="width:28px;height:28px;object-fit:cover;" />
                            <?php else: ?>
                            <?= cdAvatar('', $sessionFname, 28) ?>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control form-control-sm sd-reply-input"
                                           id="<?= $sdPfx ?>_reply_input_<?= $comment['cdc_id'] ?>"
                                           placeholder="Write a reply..." maxlength="1000" />
                                    <button class="btn btn-sm btn-primary sd-reply-submit"
                                            data-comment-id="<?= $comment['cdc_id'] ?>" data-pfx="<?= $sdPfx ?>">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            </div>

            <?php if ($canPost ?? true): ?>
            <div class="d-flex align-items-start gap-2 pt-3" style="border-top:1px solid #f8f9fa;">
                <?php if (!empty($sessionPhotoUrl)): ?>
                <img src="<?= $sessionPhotoUrl ?>" class="rounded-circle flex-shrink-0"
                     style="width:32px;height:32px;object-fit:cover;" />
                <?php else: ?>
                <?= cdAvatar('', $sessionFname, 32) ?>
                <?php endif; ?>
                <div class="flex-grow-1">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control form-control-sm sd-comment-input"
                               id="<?= $sdPfx ?>_comment_input_<?= $post['cd_id'] ?>"
                               placeholder="Write a comment..." maxlength="1000" />
                        <button class="btn btn-sm btn-primary sd-comment-submit"
                                data-id="<?= $post['cd_id'] ?>" data-pfx="<?= $sdPfx ?>">Send</button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
<!--end::Posts Feed-->

<?php if ($sdShowShared): ?>
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
.sd-photo-grid-1 .sd-photo-item { aspect-ratio:unset; display:flex; align-items:center; justify-content:center; background:#f5f8fa; }
.sd-photo-grid-1 .sd-photo-thumb { width:auto; max-width:100%; height:auto; max-height:480px; object-fit:contain; }
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
<?php endif; // sdShowShared ?>

<script>
(function() {
// ── Per-instance constants ────────────────────────────────────────────
const SD_PFX         = '<?= $sdPfx ?>';
const SD_POST_URL    = '<?= $sdPostUrl ?? '' ?>';
const SD_LIKE_BASE   = '<?= base_url('classroom/discussion/') ?>';
const SD_COMMENT_BASE= '<?= base_url('classroom/discussion/') ?>';
const SD_CLIKE_BASE  = '<?= base_url('classroom/discussion/comment/') ?>';
const SD_REPLY_BASE  = '<?= base_url('classroom/discussion/comment/') ?>';
const SD_RLIKE_BASE  = '<?= base_url('classroom/discussion/reply/') ?>';
const SD_UPLOAD_BASE = '<?= base_url('uploads/class_discussion/') ?>';
const SD_PROF_BASE   = '<?= base_url('uploads/profilePhoto/') ?>';
const SD_ME_ID       = <?= (int)($sessionUserId ?? 0) ?>;
const SD_ME_NAME     = '<?= esc($sessionFname ?? '', 'js') ?>';
const SD_ME_PHOTO    = '<?= !empty($sessionPhotoUrl) ? esc($sessionPhotoUrl, 'js') : '' ?>';

function cdRoleBadge(catId, catName) {
    if (!catId || !catName) return '';
    const map = {3:'success', 4:'primary', 6:'info'};
    const color = map[catId] || 'secondary';
    return `<span class="badge badge-light-${color} fs-9 ms-1">${sdEsc(catName)}</span>`;
}

function sdEsc(s) {
    const d = document.createElement('div');
    d.textContent = s || '';
    return d.innerHTML;
}
function sdTimeAgo(dt) {
    const diff = Math.floor((Date.now() - new Date(dt).getTime()) / 1000);
    if (diff < 60)     return 'just now';
    if (diff < 3600)   return Math.floor(diff/60)   + 'm ago';
    if (diff < 86400)  return Math.floor(diff/3600)  + 'h ago';
    if (diff < 604800) return Math.floor(diff/86400) + 'd ago';
    return new Date(dt).toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'});
}
function sdAvatar(photo, name, size) {
    size = size || 40;
    if (photo) return `<img src="${SD_PROF_BASE}${photo}" class="rounded-circle flex-shrink-0" style="width:${size}px;height:${size}px;object-fit:cover;" />`;
    const words    = (name||'').trim().split(/\s+/).filter(Boolean);
    const initials = words.map(w=>w[0]).join('').toUpperCase().slice(0,2) || '?';
    const colors   = ['primary','success','info','warning','danger'];
    const color    = colors[Math.abs(name.split('').reduce((a,c)=>a+c.charCodeAt(0),0)) % 5];
    const fs       = size >= 40 ? '7' : '9';
    return `<div class="symbol symbol-${size}px flex-shrink-0"><div class="symbol-label bg-light-${color} fw-bold text-${color} fs-${fs}">${initials}</div></div>`;
}
function sdPhotoGrid(photos, cdId) {
    if (!photos||!photos.length) return '';
    const total = photos.length, show = Math.min(total,4), extra = total>4?total-4:0;
    const cols  = total===1?1:total===2?2:total===3?3:4;
    const all   = JSON.stringify(photos.map(p=>p.photo_path));
    let html = `<div class="sd-photo-grid sd-photo-grid-${cols} mt-3">`;
    for (let i=0;i<show;i++) {
        const path = photos[i].photo_path;
        const src  = path.startsWith('http') ? path : (SD_UPLOAD_BASE + path);
        html += `<div class="sd-photo-item" data-index="${i}" data-photos='${all}' data-pfx="${SD_PFX}" onclick="cdOpenViewer(this)">`;
        html += `<img src="${src}" alt="" class="sd-photo-thumb" />`;
        if (i===3&&extra>0) html += `<div class="sd-photo-more-overlay"><span>+${extra} more</span></div>`;
        html += '</div>';
    }
    return html + '</div>';
}
function sdFormatText(s) {
    return sdEsc(s||'').replace(/@(\S+)/g,'<span class="sd-mention">@$1</span>').replace(/\n/g,'<br>');
}

function renderReply(r) {
    const isOwn  = r.author_id == SD_ME_ID;
    const rLike  = (r.user_reaction==='like')    ? 'text-primary fw-bold sd-reacted' : 'text-muted';
    const rDlike = (r.user_reaction==='dislike') ? 'text-danger fw-bold sd-reacted'  : 'text-muted';
    return `<div class="sd-reply d-flex align-items-start gap-2 mb-2" id="${SD_PFX}_reply_${r.cdcr_id}">
        ${sdAvatar(r.author_photo||'', r.author_name, 28)}
        <div class="flex-grow-1 min-w-0">
            <div class="p-2 px-3 rounded-3" style="background:#f8f9fa;">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <span class="fw-bold text-gray-800 fs-9">${sdEsc(r.author_name)}</span>
                    ${cdRoleBadge(r.author_role_cat_id, r.author_role_cat_name)}
                </div>
                <div class="text-gray-700 fs-9 lh-lg" style="white-space:pre-line;">${sdFormatText(r.reply)}</div>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2 mt-1 ms-1">
                <span class="text-muted" style="font-size:.68rem;">${sdTimeAgo(r.created_at)}</span>
                <button class="btn btn-link p-0 fs-9 sd-reply-react ${rLike}" data-id="${r.cdcr_id}" data-type="like">
                    <i class="ki-duotone ki-like fs-7"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-rlike-count">${r.like_count||0}</span>
                </button>
                <button class="btn btn-link p-0 fs-9 sd-reply-react ${rDlike}" data-id="${r.cdcr_id}" data-type="dislike">
                    <i class="ki-duotone ki-dislike fs-7"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-rdislike-count">${r.dislike_count||0}</span>
                </button>
                <button class="btn btn-link p-0 text-muted fs-9 sd-view-reactions" data-id="${r.cdcr_id}" data-scope="reply">Reactions</button>
                ${isOwn ? `<button class="btn btn-link p-0 text-danger fs-9 sd-reply-delete" data-id="${r.cdcr_id}">Delete</button>` : ''}
            </div>
        </div>
    </div>`;
}

function renderComment(c) {
    const isOwn  = c.author_id == SD_ME_ID;
    const cLike  = (c.user_reaction==='like')    ? 'text-primary fw-bold sd-reacted' : 'text-muted';
    const cDlike = (c.user_reaction==='dislike') ? 'text-danger fw-bold sd-reacted'  : 'text-muted';
    const replies= (c.replies||[]).map(renderReply).join('');
    const sessAvatar = SD_ME_PHOTO ? `<img src="${SD_ME_PHOTO}" class="rounded-circle flex-shrink-0" style="width:28px;height:28px;object-fit:cover;" />` : sdAvatar('', SD_ME_NAME, 28);
    return `<div class="sd-comment d-flex align-items-start gap-2" id="${SD_PFX}_comment_${c.cdc_id}" data-comment-id="${c.cdc_id}">
        ${sdAvatar(c.author_photo||'', c.author_name, 32)}
        <div class="flex-grow-1 min-w-0">
            <div class="p-3 rounded-3" style="background:#f0f4ff;">
                <div class="d-flex align-items-center gap-1 mb-1">
                    <span class="fw-bold text-gray-800 fs-8">${sdEsc(c.author_name)}</span>
                    ${cdRoleBadge(c.author_role_cat_id, c.author_role_cat_name)}
                </div>
                <div class="text-gray-700 fs-8 lh-lg" style="white-space:pre-line;">${sdFormatText(c.comment)}</div>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-3 mt-1 ms-1">
                <span class="text-muted" style="font-size:.72rem;">${sdTimeAgo(c.created_at)}</span>
                <button class="btn btn-link btn-sm p-0 fs-8 sd-comment-react ${cLike}" data-id="${c.cdc_id}" data-type="like">
                    <i class="ki-duotone ki-like fs-6"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-clike-count">${c.like_count||0}</span>
                </button>
                <button class="btn btn-link btn-sm p-0 fs-8 sd-comment-react ${cDlike}" data-id="${c.cdc_id}" data-type="dislike">
                    <i class="ki-duotone ki-dislike fs-6"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-cdislike-count">${c.dislike_count||0}</span>
                </button>
                <button class="btn btn-link btn-sm p-0 text-primary fs-8 sd-reply-toggle" data-comment-id="${c.cdc_id}" data-pfx="${SD_PFX}">Reply</button>
                <button class="btn btn-link btn-sm p-0 text-muted fs-9 sd-view-reactions" data-id="${c.cdc_id}" data-scope="comment">Reactions</button>
                ${isOwn ? `<button class="btn btn-link btn-sm p-0 text-danger fs-8 sd-comment-delete" data-id="${c.cdc_id}">Delete</button>` : ''}
            </div>
            <div class="ms-1 mt-2" id="${SD_PFX}_replies_${c.cdc_id}">
                ${replies}
                <div class="sd-reply-form d-none mt-2" id="${SD_PFX}_reply_form_${c.cdc_id}">
                    <div class="d-flex align-items-start gap-2">
                        ${sessAvatar}
                        <div class="flex-grow-1">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-sm sd-reply-input"
                                       id="${SD_PFX}_reply_input_${c.cdc_id}" placeholder="Write a reply..." maxlength="1000" />
                                <button class="btn btn-sm btn-primary sd-reply-submit" data-comment-id="${c.cdc_id}" data-pfx="${SD_PFX}">Reply</button>
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
    const grid   = sdPhotoGrid(p.photos||[], p.cd_id);
    const msg    = p.message ? `<div class="text-gray-700 fs-7 lh-lg mb-3" style="white-space:pre-line;">${sdEsc(p.message)}</div>` : '';
    const roleBadge = cdRoleBadge(p.author_role_cat_id, p.author_role_cat_name);
    const delBtn    = isOwn ? `<button class="btn btn-sm btn-icon btn-light-danger sd-delete-post" data-id="${p.cd_id}" data-pfx="${SD_PFX}" title="Delete post" style="width:30px;height:30px;"><i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></button>` : '';
    const sessAvatar = SD_ME_PHOTO ? `<img src="${SD_ME_PHOTO}" class="rounded-circle flex-shrink-0" style="width:32px;height:32px;object-fit:cover;" />` : sdAvatar('', SD_ME_NAME, 32);
    return `<div class="card border-0 shadow-sm mb-4 sd-post-card" id="${SD_PFX}_post_${p.cd_id}" data-post-id="${p.cd_id}" data-pfx="${SD_PFX}">
        <div class="card-body p-5">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-start gap-3">
                    ${sdAvatar(p.author_photo||'', p.author_name, 40)}
                    <div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="fw-bold text-gray-800 fs-7">${sdEsc(p.author_name)}</span>
                            ${roleBadge}
                        </div>
                        <div class="text-muted fs-9">${sdTimeAgo(p.created_at)}</div>
                    </div>
                </div>
                ${delBtn}
            </div>
            ${msg}
            ${grid}
            <div class="d-flex align-items-center gap-2 mt-3 mb-1 px-1 sd-react-summary" style="display:none!important;"></div>
            <div class="d-flex align-items-center gap-2 pt-2 mt-1" style="border-top:1px solid #f1f1f4;">
                <button class="btn btn-sm ${pLike} sd-post-react d-flex align-items-center gap-1" data-id="${p.cd_id}" data-type="like" data-pfx="${SD_PFX}">
                    <i class="ki-duotone ki-like fs-5"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-like-count">${p.like_count||0}</span>
                </button>
                <button class="btn btn-sm ${pDlike} sd-post-react d-flex align-items-center gap-1" data-id="${p.cd_id}" data-type="dislike" data-pfx="${SD_PFX}">
                    <i class="ki-duotone ki-dislike fs-5"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-dislike-count">${p.dislike_count||0}</span>
                </button>
                <button class="btn btn-sm btn-light sd-toggle-comments d-flex align-items-center gap-1 ms-1" data-id="${p.cd_id}" data-pfx="${SD_PFX}">
                    <i class="ki-duotone ki-message fs-5"><span class="path1"></span><span class="path2"></span></i>
                    <span class="sd-comment-count">${p.comment_count||0}</span>
                    <span class="ms-1 fs-8">Comments</span>
                </button>
            </div>
            <div class="sd-comments-section mt-4 d-none" id="${SD_PFX}_comments_${p.cd_id}">
                <div class="d-flex flex-column gap-3 mb-3" id="${SD_PFX}_comment_list_${p.cd_id}"></div>
                <div class="d-flex align-items-start gap-2 pt-3" style="border-top:1px solid #f8f9fa;">
                    ${sessAvatar}
                    <div class="flex-grow-1">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control form-control-sm sd-comment-input"
                                   id="${SD_PFX}_comment_input_${p.cd_id}" placeholder="Write a comment..." maxlength="1000" />
                            <button class="btn btn-sm btn-primary sd-comment-submit" data-id="${p.cd_id}" data-pfx="${SD_PFX}">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
}

<?php if ($canPost ?? true): ?>
// ── Photo upload ──────────────────────────────────────────────────────
let sdFiles_<?= $sdPfx ?> = [];
document.getElementById('<?= $sdPfx ?>_photos').addEventListener('change', function() {
    const files = Array.from(this.files);
    if (files.length > 10) {
        Swal.fire({title:'Too many photos',text:'Maximum 10 photos allowed.',icon:'warning',buttonsStyling:false,confirmButtonText:'OK',customClass:{confirmButton:'btn btn-warning'}});
        this.value = ''; sdFiles_<?= $sdPfx ?> = [];
        document.getElementById('<?= $sdPfx ?>_photo_preview').innerHTML = '';
        document.getElementById('<?= $sdPfx ?>_photo_preview').style.display = 'none'; return;
    }
    sdFiles_<?= $sdPfx ?> = files;
    const preview = document.getElementById('<?= $sdPfx ?>_photo_preview');
    preview.innerHTML = '';
    if (!files.length) { preview.style.display='none'; return; }
    preview.style.display = 'flex';
    files.forEach((file,i) => {
        const reader = new FileReader();
        reader.onload = e => {
            const wrap = document.createElement('div');
            wrap.className = 'sd-preview-wrap'; wrap.dataset.idx = i;
            wrap.innerHTML = `<img src="${e.target.result}" alt="" /><button type="button" class="sd-preview-remove" data-idx="${i}" title="Remove">×</button>`;
            preview.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
});
document.getElementById('<?= $sdPfx ?>_photo_preview').addEventListener('click', function(e) {
    const btn = e.target.closest('.sd-preview-remove');
    if (!btn) return;
    const idx = parseInt(btn.dataset.idx);
    sdFiles_<?= $sdPfx ?> = sdFiles_<?= $sdPfx ?>.filter((_,i)=>i!==idx);
    const dt = new DataTransfer();
    sdFiles_<?= $sdPfx ?>.forEach(f=>dt.items.add(f));
    document.getElementById('<?= $sdPfx ?>_photos').files = dt.files;
    const preview = document.getElementById('<?= $sdPfx ?>_photo_preview');
    preview.innerHTML = '';
    if (!sdFiles_<?= $sdPfx ?>.length) { preview.style.display='none'; return; }
    preview.style.display='flex';
    sdFiles_<?= $sdPfx ?>.forEach((file,i) => {
        const reader = new FileReader();
        reader.onload = e2 => {
            const wrap = document.createElement('div');
            wrap.className='sd-preview-wrap';
            wrap.innerHTML=`<img src="${e2.target.result}" alt="" /><button type="button" class="sd-preview-remove" data-idx="${i}" title="Remove">×</button>`;
            preview.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });
});

// ── Post submit ───────────────────────────────────────────────────────
document.getElementById('<?= $sdPfx ?>_btn_post').addEventListener('click', function() {
    const btn     = this;
    const message = document.getElementById('<?= $sdPfx ?>_message').value.trim();
    const photos  = document.getElementById('<?= $sdPfx ?>_photos').files;
    if (!message && !photos.length) {
        Swal.fire({title:'Nothing to post',text:'Write a message or upload at least one photo.',icon:'warning',buttonsStyling:false,confirmButtonText:'OK',customClass:{confirmButton:'btn btn-warning'}});
        return;
    }
    btn.setAttribute('data-kt-indicator','on'); btn.disabled=true;
    const fd = new FormData();
    fd.append('message', message);
    for (const file of photos) fd.append('photos[]', file);
    $.ajax({
        url:SD_POST_URL, type:'POST', data:fd, processData:false, contentType:false,
        success(res) {
            btn.removeAttribute('data-kt-indicator'); btn.disabled=false;
            if (res.success) {
                document.getElementById('<?= $sdPfx ?>_message').value='';
                document.getElementById('<?= $sdPfx ?>_photos').value='';
                sdFiles_<?= $sdPfx ?>=[];
                const prev=document.getElementById('<?= $sdPfx ?>_photo_preview');
                prev.innerHTML=''; prev.style.display='none';
                const empty=document.getElementById('<?= $sdPfx ?>_empty_state');
                if (empty) empty.remove();
                document.getElementById('<?= $sdPfx ?>_feed').insertAdjacentHTML('afterbegin', renderPost(res.post));
                const cnt=document.getElementById('<?= $sdPfx ?>_post_count');
                if (cnt) cnt.textContent=parseInt(cnt.textContent||'0')+1;
            } else {
                Swal.fire({title:'Failed',text:res.message,icon:'error',buttonsStyling:false,confirmButtonText:'Close',customClass:{confirmButton:'btn btn-danger'}});
            }
        },
        error() { btn.removeAttribute('data-kt-indicator'); btn.disabled=false; Swal.fire({title:'Error',text:'An error occurred.',icon:'error',buttonsStyling:false,confirmButtonText:'Close',customClass:{confirmButton:'btn btn-danger'}}); }
    });
});
document.getElementById('<?= $sdPfx ?>_message').addEventListener('keydown', function(e) {
    if ((e.ctrlKey||e.metaKey) && e.key==='Enter') document.getElementById('<?= $sdPfx ?>_btn_post').click();
});
<?php endif; ?>

// ── Toggle comments ───────────────────────────────────────────────────
$(document).on('click', '.sd-toggle-comments', function() {
    const pfx = $(this).data('pfx') || SD_PFX;
    if (pfx !== SD_PFX) return;
    const id  = $(this).data('id');
    const sec = $(`#${SD_PFX}_comments_${id}`);
    sec.toggleClass('d-none');
    if (!sec.hasClass('d-none')) $(`#${SD_PFX}_comment_input_${id}`).focus();
});

// ── Delete post ───────────────────────────────────────────────────────
$(document).on('click', '.sd-delete-post', function() {
    if ($(this).data('pfx') !== SD_PFX) return;
    const id = $(this).data('id');
    Swal.fire({title:'Delete this post?',text:'This cannot be undone.',icon:'warning',showCancelButton:true,buttonsStyling:false,
        confirmButtonText:'Yes, delete',cancelButtonText:'Cancel',customClass:{confirmButton:'btn btn-danger me-2',cancelButton:'btn btn-light'}
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(SD_LIKE_BASE+id+'/delete', {}, res => {
            if (res.success) {
                $(`#${SD_PFX}_post_${id}`).fadeOut(250,function(){$(this).remove();});
                const cnt=document.getElementById(`${SD_PFX}_post_count`);
                if (cnt) cnt.textContent=Math.max(0,parseInt(cnt.textContent||'0')-1);
            } else Swal.fire('Error',res.message,'error');
        });
    });
});

// ── Post like/dislike ─────────────────────────────────────────────────
$(document).on('click', '.sd-post-react', function() {
    if ($(this).data('pfx') !== SD_PFX) return;
    const btn=$(this), id=btn.data('id'), type=btn.data('type'), post=btn.closest('.sd-post-card');
    $.post(SD_LIKE_BASE+id+'/like', {type}, res => {
        if (!res.success) return;
        const lBtn=post.find('.sd-post-react[data-type="like"]'), dBtn=post.find('.sd-post-react[data-type="dislike"]');
        lBtn.find('.sd-like-count').text(res.likes);
        dBtn.find('.sd-dislike-count').text(res.dislikes);
        lBtn.removeClass('btn-light-primary btn-light sd-reacted');
        dBtn.removeClass('btn-light-danger btn-light sd-reacted');
        if (res.reaction==='like')    { lBtn.addClass('btn-light-primary sd-reacted'); dBtn.addClass('btn-light'); }
        else if (res.reaction==='dislike') { lBtn.addClass('btn-light'); dBtn.addClass('btn-light-danger sd-reacted'); }
        else { lBtn.addClass('btn-light'); dBtn.addClass('btn-light'); }
    });
});

// ── Comment submit ────────────────────────────────────────────────────
$(document).on('click', '.sd-comment-submit', function() {
    if ($(this).data('pfx') !== SD_PFX) return;
    const btn=$(this), id=btn.data('id'), input=$(`#${SD_PFX}_comment_input_${id}`), comment=input.val().trim();
    if (!comment) { input.focus(); return; }
    btn.prop('disabled',true);
    $.post(SD_COMMENT_BASE+id+'/comment', {comment}, res => {
        btn.prop('disabled',false);
        if (res.success) {
            input.val('');
            $(`#${SD_PFX}_comment_list_${id}`).append(renderComment(res.comment));
            const cnt=$(`#${SD_PFX}_post_${id} .sd-comment-count`);
            cnt.text(parseInt(cnt.text()||'0')+1);
        } else Swal.fire('Error',res.message,'error');
    }).fail(()=>btn.prop('disabled',false));
});
$(document).on('keydown', '.sd-comment-input', function(e) {
    if (e.key==='Enter'&&!e.shiftKey) { e.preventDefault(); $(this).closest('.input-group').find('.sd-comment-submit').click(); }
});

// ── Comment like ──────────────────────────────────────────────────────
$(document).on('click', '.sd-comment-react', function() {
    const btn=$(this), id=btn.data('id'), type=btn.data('type'), wrap=btn.closest('[data-comment-id]');
    $.post(SD_CLIKE_BASE+id+'/like', {type}, res => {
        if (!res.success) return;
        const lBtn=wrap.find('.sd-comment-react[data-type="like"]'), dBtn=wrap.find('.sd-comment-react[data-type="dislike"]');
        lBtn.find('.sd-clike-count').text(res.likes); dBtn.find('.sd-cdislike-count').text(res.dislikes);
        lBtn.removeClass('text-primary fw-bold sd-reacted text-muted');
        dBtn.removeClass('text-danger fw-bold sd-reacted text-muted');
        if (res.reaction==='like')    { lBtn.addClass('text-primary fw-bold sd-reacted'); dBtn.addClass('text-muted'); }
        else if (res.reaction==='dislike') { dBtn.addClass('text-danger fw-bold sd-reacted'); lBtn.addClass('text-muted'); }
        else { lBtn.addClass('text-muted'); dBtn.addClass('text-muted'); }
    });
});

// ── Comment delete ────────────────────────────────────────────────────
$(document).on('click', '.sd-comment-delete', function() {
    const id=$(this).data('id');
    Swal.fire({title:'Delete comment?',icon:'warning',showCancelButton:true,buttonsStyling:false,
        confirmButtonText:'Delete',cancelButtonText:'Cancel',customClass:{confirmButton:'btn btn-danger me-2',cancelButton:'btn btn-light'}
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(SD_CLIKE_BASE+id+'/delete', {}, res => {
            if (res.success) {
                const el=document.getElementById(`${SD_PFX}_comment_${id}`);
                if (!el) return;
                const postCard=$(el).closest('.sd-post-card');
                $(el).fadeOut(200,function(){$(this).remove();});
                const cnt=postCard.find('.sd-comment-count');
                cnt.text(Math.max(0,parseInt(cnt.text()||'0')-1));
            }
        });
    });
});

// ── Reply toggle ──────────────────────────────────────────────────────
$(document).on('click', '.sd-reply-toggle', function() {
    if ($(this).data('pfx') !== SD_PFX) return;
    const cid=$(this).data('comment-id'), form=$(`#${SD_PFX}_reply_form_${cid}`);
    form.toggleClass('d-none');
    if (!form.hasClass('d-none')) form.find('input').focus();
});

// ── Reply submit ──────────────────────────────────────────────────────
$(document).on('click', '.sd-reply-submit', function() {
    if ($(this).data('pfx') !== SD_PFX) return;
    const btn=$(this), cid=btn.data('comment-id'), input=$(`#${SD_PFX}_reply_input_${cid}`), reply=input.val().trim();
    if (!reply) { input.focus(); return; }
    btn.prop('disabled',true);
    $.post(SD_REPLY_BASE+cid+'/reply', {reply}, res => {
        btn.prop('disabled',false);
        if (res.success) {
            input.val('');
            $(`#${SD_PFX}_reply_form_${cid}`).before(renderReply(res.reply));
            $(`#${SD_PFX}_reply_form_${cid}`).addClass('d-none');
        } else Swal.fire('Error',res.message,'error');
    }).fail(()=>btn.prop('disabled',false));
});
$(document).on('keydown', '.sd-reply-input', function(e) {
    if (e.key==='Enter'&&!e.shiftKey) { e.preventDefault(); $(this).closest('.input-group').find('.sd-reply-submit').click(); }
});

// ── Reply like ────────────────────────────────────────────────────────
$(document).on('click', '.sd-reply-react', function() {
    const btn=$(this), id=btn.data('id'), type=btn.data('type'), wrap=btn.closest('.sd-reply');
    $.post(SD_RLIKE_BASE+id+'/like', {type}, res => {
        if (!res.success) return;
        const lBtn=wrap.find('.sd-reply-react[data-type="like"]'), dBtn=wrap.find('.sd-reply-react[data-type="dislike"]');
        lBtn.find('.sd-rlike-count').text(res.likes); dBtn.find('.sd-rdislike-count').text(res.dislikes);
        lBtn.removeClass('text-primary fw-bold sd-reacted text-muted');
        dBtn.removeClass('text-danger fw-bold sd-reacted text-muted');
        if (res.reaction==='like')    { lBtn.addClass('text-primary fw-bold sd-reacted'); dBtn.addClass('text-muted'); }
        else if (res.reaction==='dislike') { dBtn.addClass('text-danger fw-bold sd-reacted'); lBtn.addClass('text-muted'); }
        else { lBtn.addClass('text-muted'); dBtn.addClass('text-muted'); }
    });
});

// ── Reply on reply ────────────────────────────────────────────────────
$(document).on('click', '.sd-reply-on-reply', function() {
    if ($(this).data('pfx') !== SD_PFX) return;
    const cid=$(this).data('comment-id'), mention=$(this).data('mention');
    const form=$(`#${SD_PFX}_reply_form_${cid}`);
    form.removeClass('d-none');
    const input=$(`#${SD_PFX}_reply_input_${cid}`);
    input.val('@'+mention+' ').focus();
    const len=input.val().length; input[0].setSelectionRange(len,len);
    form[0].scrollIntoView({behavior:'smooth',block:'nearest'});
});

// ── Reply delete ──────────────────────────────────────────────────────
$(document).on('click', '.sd-reply-delete', function() {
    const id=$(this).data('id');
    Swal.fire({title:'Delete reply?',icon:'warning',showCancelButton:true,buttonsStyling:false,
        confirmButtonText:'Delete',cancelButtonText:'Cancel',customClass:{confirmButton:'btn btn-danger me-2',cancelButton:'btn btn-light'}
    }).then(r => {
        if (!r.isConfirmed) return;
        $.post(SD_RLIKE_BASE+id+'/delete', {}, res => {
            if (res.success) $(`#${SD_PFX}_reply_${id}`).fadeOut(200,function(){$(this).remove();});
        });
    });
});

// ── Reactions modal ───────────────────────────────────────────────────
let cdReactionsCache = {all:[],likes:[],dislikes:[]};
let cdReactFilter    = 'all';
function cdRenderReactions() {
    const list = cdReactionsCache[cdReactFilter] || [];
    const el   = document.getElementById('cd_reactions_list');
    if (!el) return;
    if (!list.length) { el.innerHTML='<div class="text-center py-8 text-muted fs-8">No reactions yet.</div>'; return; }
    el.innerHTML = list.map(r => {
        const src = r.photo ? `${SD_PROF_BASE}${r.photo}` : '';
        const icon = r.like_type==='like'
            ? `<i class="ki-duotone ki-like fs-7 text-primary"><span class="path1"></span><span class="path2"></span></i>`
            : `<i class="ki-duotone ki-dislike fs-7 text-danger"><span class="path1"></span><span class="path2"></span></i>`;
        const avatar = src ? `<img src="${src}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;" />`
            : `<div class="symbol symbol-36px"><div class="symbol-label bg-light-primary fw-bold text-primary fs-8">${sdEsc(r.name).slice(0,2).toUpperCase()}</div></div>`;
        return `<div class="d-flex align-items-center gap-3 py-2" style="border-bottom:1px solid #f8f9fa;">${avatar}<span class="fw-semibold text-gray-700 fs-7 flex-grow-1">${sdEsc(r.name)}</span>${icon}</div>`;
    }).join('');
}
$(document).on('click', '.sd-view-reactions', function() {
    const id=$(this).data('id'), scope=$(this).data('scope')||'post';
    const url = scope==='post'    ? SD_LIKE_BASE+id+'/reactions'
              : scope==='comment' ? SD_CLIKE_BASE+id+'/reactions'
              :                     SD_RLIKE_BASE+id+'/reactions';
    const listEl=document.getElementById('cd_reactions_list');
    if (listEl) listEl.innerHTML='<div class="text-center py-8"><span class="spinner-border spinner-border-sm text-primary"></span></div>';
    cdReactFilter='all';
    $('#cd_react_tab_all').addClass('active');
    $('#cd_react_tab_like,#cd_react_tab_dislike').removeClass('active');
    new bootstrap.Modal(document.getElementById('cd_reactions_modal')).show();
    $.get(url, res => {
        if (!res.success) return;
        const all=res.reactions||[], likes=all.filter(r=>r.like_type==='like'), dlikes=all.filter(r=>r.like_type==='dislike');
        cdReactionsCache={all,likes,dislikes:dlikes};
        document.getElementById('cd_react_all_cnt').textContent=all.length;
        document.getElementById('cd_react_like_cnt').textContent=likes.length;
        document.getElementById('cd_react_dislike_cnt').textContent=dlikes.length;
        cdRenderReactions();
    });
});
$('#cd_react_tabs').on('click','a',function(e){
    e.preventDefault(); $('#cd_react_tabs a').removeClass('active'); $(this).addClass('active');
    if (this.id==='cd_react_tab_like') cdReactFilter='likes';
    else if (this.id==='cd_react_tab_dislike') cdReactFilter='dislikes';
    else cdReactFilter='all';
    cdRenderReactions();
});

})(); // end IIFE
</script>

<?php if ($sdShowShared): ?>
<script>
// ── Photo viewer (shared, one instance per page) ──────────────────────
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
    document.getElementById('cd_viewer_img').src     = src;
    document.getElementById('cd_viewer_counter').textContent = (cdViewerIdx+1) + ' / ' + cdViewerPhotos.length;
    document.getElementById('cd_viewer_prev').style.display = cdViewerPhotos.length > 1 ? '' : 'none';
    document.getElementById('cd_viewer_next').style.display = cdViewerPhotos.length > 1 ? '' : 'none';
}
document.getElementById('cd_viewer_prev').addEventListener('click', function() {
    cdViewerIdx = (cdViewerIdx - 1 + cdViewerPhotos.length) % cdViewerPhotos.length;
    cdShowViewerPhoto();
});
document.getElementById('cd_viewer_next').addEventListener('click', function() {
    cdViewerIdx = (cdViewerIdx + 1) % cdViewerPhotos.length;
    cdShowViewerPhoto();
});
</script>
<?php endif; ?>
