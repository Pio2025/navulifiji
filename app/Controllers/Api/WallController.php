<?php

namespace App\Controllers\Api;

use App\Libraries\ApiAuth;
use App\Libraries\SchoolAccess;
use App\Models\UserModel;
use App\Models\WallModel;
use CodeIgniter\Controller;

/**
 * Mobile Wall — reuses WallModel directly (same query logic as the web
 * WallController). Supports the same multi-school tab switching as the web
 * app (own current-admission school for Students/Teachers, plus each
 * linked child's active-admission school for Parents/parent-flagged staff).
 */
class WallController extends Controller
{
    private const UPLOAD_DIR  = 'uploads/wall/';
    private const MAX_FILE_MB = 20;
    private const IMAGE_MIME  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private const FILE_MIME   = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];

    protected $wallModel;
    protected $userModel;
    protected $schoolAccess;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->wallModel    = new WallModel();
        $this->userModel    = new UserModel();
        $this->schoolAccess = new SchoolAccess();
    }

    /**
     * @return array{0: array, 1: int} [$schools, $resolvedSchId]
     */
    private function resolveSchools(?int $requestedSchId): array
    {
        $claims    = ApiAuth::claims();
        $myId      = ApiAuth::userId();
        $roleCatId = (int) ($claims['roleCatID'] ?? 0);
        $ownSchId  = (int) ($claims['schID'] ?? 0);

        $schools = $this->schoolAccess->accessibleSchools($myId, $roleCatId, $ownSchId);
        $schId   = $this->schoolAccess->resolveActiveSchoolId($schools, $requestedSchId);

        return [$schools, $schId];
    }

    private function myName(int $userId): string
    {
        $user = $this->userModel->find($userId);
        if (!$user) return '';
        return !empty($user['oname'])
            ? trim($user['fname'] . ' ' . $user['oname'] . ' ' . $user['lname'])
            : trim($user['fname'] . ' ' . $user['lname']);
    }

    private function age(string $dt): string
    {
        $s = time() - strtotime($dt);
        if ($s < 60) return 'Just now';
        if ($s < 3600) return floor($s / 60) . 'm ago';
        if ($s < 86400) return floor($s / 3600) . 'h ago';
        if ($s < 604800) return floor($s / 86400) . 'd ago';
        return date('d M Y', strtotime($dt));
    }

    private function photoUrl(?string $photo): string
    {
        if ($photo && file_exists(FCPATH . 'uploads/profilePhoto/' . $photo)) {
            return base_url('uploads/profilePhoto/' . $photo);
        }
        return base_url('app/assets/media/avatars/blank.png');
    }

    private function mediaOut(array $mediaRows): array
    {
        return array_map(fn ($m) => [
            'wallMediaId' => (int) $m['wall_media_id'],
            'mediaType'   => $m['media_type'],
            'fileSrc'     => $m['media_type'] === 'video_url'
                              ? $m['file_src']
                              : base_url(self::UPLOAD_DIR . $m['file_src']),
            'fileName'    => $m['file_name'] ?: $m['file_src'],
        ], $mediaRows);
    }

    /**
     * GET /api/wall/feed?offset=0&sch_id= — requires Bearer token (apijwt filter).
     */
    public function feed()
    {
        $myId       = ApiAuth::userId();
        $offset     = max(0, (int) $this->request->getGet('offset'));
        $limit      = 10;
        $reqSchId   = $this->request->getGet('sch_id');
        [$schools, $schID] = $this->resolveSchools($reqSchId !== null ? (int) $reqSchId : null);

        if ($schID === 0) {
            return $this->response->setJSON(['success' => true, 'posts' => [], 'hasMore' => false, 'schools' => [], 'activeSchoolId' => 0]);
        }

        $posts   = $this->wallModel->getPosts($schID, $offset, $limit + 1);
        $hasMore = count($posts) > $limit;
        if ($hasMore) array_pop($posts);

        if (empty($posts)) {
            return $this->response->setJSON(['success' => true, 'posts' => [], 'hasMore' => false, 'schools' => $schools, 'activeSchoolId' => $schID]);
        }

        $postIds = array_column($posts, 'wall_post_id');

        $mediaRaw = $this->wallModel->getMediaForPosts($postIds);
        $mediaMap = [];
        foreach ($mediaRaw as $m) $mediaMap[$m['wall_post_id_fk']][] = $m;

        $postReactions = $this->wallModel->getReactionSummaryBulk('post', $postIds, $myId);

        $out = [];
        foreach ($posts as $p) {
            $pid = (int) $p['wall_post_id'];
            $out[] = [
                'wallPostId'    => $pid,
                'userId'        => (int) $p['user_id_fk'],
                'content'       => $p['content'],
                'age'           => $this->age($p['created_at']),
                'createdAt'     => $p['created_at'],
                'authorName'    => trim($p['fname'] . ' ' . $p['lname']),
                'authorPhoto'   => $this->photoUrl($p['photo']),
                'authorRole'    => $p['role_cat_name'] ?? null,
                'commentCount'  => (int) $p['comment_count'],
                'reactionCount' => (int) $p['reaction_count'],
                'reactions'     => $postReactions[$pid] ?? ['summary' => [], 'my_emoji' => null],
                'media'         => $this->mediaOut($mediaMap[$pid] ?? []),
                'isMine'        => (int) $p['user_id_fk'] === $myId,
            ];
        }

        return $this->response->setJSON([
            'success'        => true,
            'posts'          => $out,
            'hasMore'        => $hasMore,
            'schools'        => $schools,
            'activeSchoolId' => $schID,
        ]);
    }

    /**
     * POST /api/wall/post — multipart: content, media[] files, video_urls[], sch_id?
     */
    public function createPost()
    {
        $claims   = ApiAuth::claims();
        $myId     = ApiAuth::userId();
        $reqSchId = $this->request->getPost('sch_id');
        [, $schID] = $this->resolveSchools($reqSchId !== null && $reqSchId !== '' ? (int) $reqSchId : null);

        if ($schID === 0) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'No school linked to your account.']);
        }

        $content   = trim($this->request->getPost('content') ?? '');
        $videoUrls = array_filter(array_map('trim', (array) ($this->request->getPost('video_urls') ?? [])));

        $hasFiles = false;
        $files    = $this->request->getFileMultiple('media') ?? [];
        foreach ($files as $f) { if ($f && $f->isValid()) { $hasFiles = true; break; } }

        if ($content === '' && empty($videoUrls) && !$hasFiles) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Post must have some content, media, or a video link.']);
        }

        $postId = $this->wallModel->createPost($schID, $myId, $content);

        $dir = FCPATH . self::UPLOAD_DIR;
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        foreach ($files as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) continue;
            if ($file->getSize() > self::MAX_FILE_MB * 1024 * 1024) continue;
            $mime = $file->getMimeType();
            $type = in_array($mime, self::IMAGE_MIME) ? 'image' : (in_array($mime, self::FILE_MIME) ? 'file' : null);
            if (!$type) continue;
            $ext     = strtolower($file->getExtension());
            $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $file->move($dir, $newName);
            $this->wallModel->addMedia($postId, $type, $newName, $file->getClientName());
        }

        foreach ($videoUrls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $this->wallModel->addMedia($postId, 'video_url', $url);
            }
        }

        $post  = $this->wallModel->getPost($postId);
        $media = $this->wallModel->getMediaForPosts([$postId]);

        return $this->response->setJSON([
            'success' => true,
            'post' => [
                'wallPostId'    => $postId,
                'userId'        => $myId,
                'content'       => $post['content'],
                'age'           => 'Just now',
                'createdAt'     => $post['created_at'] ?? date('Y-m-d H:i:s'),
                'authorName'    => $this->myName($myId),
                'authorPhoto'   => $this->photoUrl(($this->userModel->find($myId))['profile_photo'] ?? null),
                'authorRole'    => $claims['roleCatName'] ?? null,
                'commentCount'  => 0,
                'reactionCount' => 0,
                'reactions'     => ['summary' => [], 'my_emoji' => null],
                'media'         => $this->mediaOut($media),
                'isMine'        => true,
            ],
        ]);
    }

    /**
     * GET /api/wall/post/(:num)/comments
     */
    public function comments(int $postId)
    {
        $myId     = ApiAuth::userId();
        $comments = $this->wallModel->getComments($postId);

        if (empty($comments)) {
            return $this->response->setJSON(['success' => true, 'comments' => []]);
        }

        $commentIds = array_column($comments, 'wall_comment_id');
        $reactions  = $this->wallModel->getReactionSummaryBulk('comment', $commentIds, $myId);

        $out = [];
        foreach ($comments as $c) {
            $cid = (int) $c['wall_comment_id'];
            $out[] = [
                'wallCommentId'   => $cid,
                'parentCommentId' => $c['parent_comment_id'] ? (int) $c['parent_comment_id'] : null,
                'userId'          => (int) $c['user_id_fk'],
                'content'         => $c['content'],
                'age'             => $this->age($c['created_at']),
                'authorName'      => trim($c['fname'] . ' ' . $c['lname']),
                'authorPhoto'     => $this->photoUrl($c['photo']),
                'authorRole'      => $c['role_cat_name'] ?? null,
                'reactionCount'   => (int) $c['reaction_count'],
                'reactions'       => $reactions[$cid] ?? ['summary' => [], 'my_emoji' => null],
                'isMine'          => (int) $c['user_id_fk'] === $myId,
            ];
        }

        return $this->response->setJSON(['success' => true, 'comments' => $out]);
    }

    /**
     * POST /api/wall/post/(:num)/comment — body: content, parent_comment_id?
     */
    public function addComment(int $postId)
    {
        $claims   = ApiAuth::claims();
        $myId     = ApiAuth::userId();
        $body     = $this->request->getJSON(true) ?? [];
        $content  = trim($this->request->getPost('content') ?? ($body['content'] ?? ''));
        $parentId = $this->request->getPost('parent_comment_id') ?? ($body['parent_comment_id'] ?? null);
        $parentId = ($parentId !== null && $parentId !== '') ? (int) $parentId : null;

        if ($content === '') {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Comment cannot be empty.']);
        }

        $commentId = $this->wallModel->addComment($postId, $myId, $content, $parentId);

        return $this->response->setJSON([
            'success' => true,
            'comment' => [
                'wallCommentId'   => $commentId,
                'parentCommentId' => $parentId,
                'userId'          => $myId,
                'content'         => $content,
                'age'             => 'Just now',
                'authorName'      => $this->myName($myId),
                'authorPhoto'     => $this->photoUrl(($this->userModel->find($myId))['profile_photo'] ?? null),
                'authorRole'      => $claims['roleCatName'] ?? null,
                'reactionCount'   => 0,
                'reactions'       => ['summary' => [], 'my_emoji' => null],
                'isMine'          => true,
            ],
        ]);
    }

    /**
     * POST /api/wall/react — body: target_type ('post'|'comment'), target_id, emoji
     */
    public function react()
    {
        $myId   = ApiAuth::userId();
        $body   = $this->request->getJSON(true) ?? [];
        $targetType = $this->request->getPost('target_type') ?? ($body['target_type'] ?? null);
        $targetId   = (int) ($this->request->getPost('target_id') ?? ($body['target_id'] ?? 0));
        $emoji      = $this->request->getPost('emoji') ?? ($body['emoji'] ?? null);

        if (!in_array($targetType, ['post', 'comment'], true)) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Invalid target']);
        }
        if (!$emoji) {
            return $this->response->setStatusCode(422)->setJSON(['success' => false, 'message' => 'Emoji required']);
        }

        $result  = $this->wallModel->toggleReaction($targetType, $targetId, $myId, $emoji);
        $summary = $this->wallModel->getReactionSummary($targetType, $targetId, $myId);

        return $this->response->setJSON(array_merge(['success' => true], $result, $summary));
    }
}
