<?php

namespace App\Controllers;

class WallController extends BaseController
{
    private const UPLOAD_DIR   = 'uploads/wall/';
    private const MAX_FILE_MB  = 20;
    private const IMAGE_MIME   = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private const FILE_MIME    = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ];

    // ─── helpers ──────────────────────────────────────────────────────────────

    private function json(array $data, int $code = 200): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->response->setStatusCode($code)->setJSON($data);
    }

    private function resolveSchoolId(): int
    {
        $schId  = (int) $this->session->get('schID');
        $userId = (int) $this->session->get('userID');
        if ($schId !== 0) return $schId;

        $db = \Config\Database::connect();

        $row = $db->table('admission')
            ->select('sch_id_fk')
            ->where('user_id_fk', $userId)
            ->where('admission_status', 'Active')
            ->orderBy('admission_id', 'DESC')
            ->limit(1)->get()->getRowArray();
        if ($row) { $this->session->set('schID', (int)$row['sch_id_fk']); return (int)$row['sch_id_fk']; }

        $row = $db->table('staff')
            ->select('sch_id_fk')
            ->where('user_id_fk', $userId)
            ->where('staff_status', 'Active')
            ->limit(1)->get()->getRowArray();
        if ($row) { $this->session->set('schID', (int)$row['sch_id_fk']); return (int)$row['sch_id_fk']; }

        return 0;
    }

    private function uploadDir(): string
    {
        $dir = FCPATH . self::UPLOAD_DIR;
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        return $dir;
    }

    private function age(string $dt): string
    {
        $s = time() - strtotime($dt);
        if ($s < 60)    return 'Just now';
        if ($s < 3600)  return floor($s / 60) . 'm ago';
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

    // ─── INDEX ────────────────────────────────────────────────────────────────

    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $schId = $this->resolveSchoolId();
        if (!$schId) return redirect()->to('school/dashboard')->with('error', 'No school linked to your account.');

        $db = \Config\Database::connect();

        $schoolRow  = $db->table('school')->select('sch_name, sch_motto, sch_logo')->where('sch_id', $schId)->get()->getRowArray();
        $postCount  = (int) $db->table('wall_post')->where('sch_id_fk', $schId)->where('post_status', 'Active')->countAllResults();
        $memberCount = (int) $db->table('admission')->where('sch_id_fk', $schId)->where('admission_status', 'Active')->countAllResults();

        $this->setPageData('School Wall', 'Wall', 'School Wall');
        $data = $this->loadCommonData('app/wall/index', [
            'schId'       => $schId,
            'myId'        => (int) $this->session->get('userID'),
            'myName'      => trim($this->session->get('fname') . ' ' . $this->session->get('name')),
            'myPhoto'     => $this->photoUrl($this->session->get('photo')),
            'schoolName'  => $schoolRow['sch_name']  ?? 'School Community',
            'schoolMotto' => $schoolRow['sch_motto']  ?? '',
            'schoolLogo'  => $schoolRow['sch_logo']   ? base_url('uploads/schoolLogo/' . $schoolRow['sch_logo']) : '',
            'wallPostCount'  => $postCount,
            'wallMemberCount'=> $memberCount,
        ]);

        return view('app/layouts/main', $data);
    }

    // ─── FEED (AJAX) ──────────────────────────────────────────────────────────

    public function feed(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $schId  = $this->resolveSchoolId();
        $myId   = (int) $this->session->get('userID');
        $offset = max(0, (int) $this->request->getGet('offset'));
        $limit  = 10;

        $posts   = $this->wallModel->getPosts($schId, $offset, $limit + 1);
        $hasMore = count($posts) > $limit;
        if ($hasMore) array_pop($posts);

        if (empty($posts)) {
            return $this->json(['posts' => [], 'has_more' => false]);
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
                'wall_post_id'   => $pid,
                'user_id_fk'     => (int) $p['user_id_fk'],
                'content'        => $p['content'],
                'age'            => $this->age($p['created_at']),
                'created_at'     => $p['created_at'],
                'author_name'    => trim($p['fname'] . ' ' . $p['lname']),
                'author_photo'   => $this->photoUrl($p['photo']),
                'comment_count'  => (int) $p['comment_count'],
                'reaction_count' => (int) $p['reaction_count'],
                'reactions'      => $postReactions[$pid] ?? ['summary' => [], 'my_emoji' => null],
                'media'          => array_map(fn($m) => [
                    'wall_media_id' => (int) $m['wall_media_id'],
                    'media_type'    => $m['media_type'],
                    'file_src'      => $m['media_type'] === 'video_url'
                                        ? $m['file_src']
                                        : base_url(self::UPLOAD_DIR . $m['file_src']),
                    'file_name'     => $m['file_name'] ?: $m['file_src'],
                ], $mediaMap[$pid] ?? []),
                'is_mine' => (int) $p['user_id_fk'] === $myId,
            ];
        }

        return $this->json(['posts' => $out, 'has_more' => $hasMore]);
    }

    // ─── CREATE POST (AJAX) ──────────────────────────────────────────────────

    public function createPost(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $schId   = $this->resolveSchoolId();
        $myId    = (int) $this->session->get('userID');
        $content = trim($this->request->getPost('content') ?? '');

        // Collect video URLs
        $videoUrls = array_filter(array_map('trim', (array)($this->request->getPost('video_urls') ?? [])));

        // Validate: must have content or media
        $hasFiles = false;
        $files    = $this->request->getFileMultiple('media') ?? [];
        foreach ($files as $f) { if ($f && $f->isValid()) { $hasFiles = true; break; } }

        if ($content === '' && empty($videoUrls) && !$hasFiles) {
            return $this->json(['error' => 'Post must have some content, media, or a video link.'], 422);
        }

        $postId = $this->wallModel->createPost($schId, $myId, $content);

        // Handle file uploads
        foreach ($files as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) continue;
            if ($file->getSize() > self::MAX_FILE_MB * 1024 * 1024) continue;
            $mime = $file->getMimeType();
            $type = in_array($mime, self::IMAGE_MIME) ? 'image' : (in_array($mime, self::FILE_MIME) ? 'file' : null);
            if (!$type) continue;
            $ext     = strtolower($file->getExtension());
            $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $file->move($this->uploadDir(), $newName);
            $this->wallModel->addMedia($postId, $type, $newName, $file->getClientName());
        }

        // Handle video URLs
        foreach ($videoUrls as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $this->wallModel->addMedia($postId, 'video_url', $url);
            }
        }

        // Return the newly created post for immediate rendering
        $post  = $this->wallModel->getPost($postId);
        $media = $this->wallModel->getMediaForPosts([$postId]);

        return $this->json([
            'success' => true,
            'post' => [
                'wall_post_id'   => $postId,
                'user_id_fk'     => $myId,
                'content'        => $post['content'],
                'age'            => 'Just now',
                'author_name'    => trim($this->session->get('fname') . ' ' . $this->session->get('name')),
                'author_photo'   => $this->photoUrl($this->session->get('photo')),
                'comment_count'  => 0,
                'reaction_count' => 0,
                'reactions'      => ['summary' => [], 'my_emoji' => null],
                'media'          => array_map(fn($m) => [
                    'wall_media_id' => (int) $m['wall_media_id'],
                    'media_type'    => $m['media_type'],
                    'file_src'      => $m['media_type'] === 'video_url'
                                        ? $m['file_src']
                                        : base_url(self::UPLOAD_DIR . $m['file_src']),
                    'file_name'     => $m['file_name'] ?: $m['file_src'],
                ], $media),
                'is_mine' => true,
            ],
        ]);
    }

    // ─── DELETE POST (AJAX) ──────────────────────────────────────────────────

    public function deletePost(int $postId): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $myId    = (int) $this->session->get('userID');
        $post    = $this->wallModel->getPost($postId);
        if (!$post) return $this->json(['error' => 'Not found'], 404);

        $isAdmin = in_array((int) $this->session->get('roleCatID'), [1, 2, 7]);
        if ((int) $post['user_id_fk'] !== $myId && !$isAdmin) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        // Delete physical media files
        $media = $this->wallModel->getMediaForPosts([$postId]);
        foreach ($media as $m) {
            if ($m['media_type'] !== 'video_url') {
                $path = FCPATH . self::UPLOAD_DIR . $m['file_src'];
                if (file_exists($path)) unlink($path);
            }
            $this->wallModel->deleteMedia((int) $m['wall_media_id']);
        }

        $this->wallModel->deletePost($postId);
        return $this->json(['success' => true]);
    }

    // ─── COMMENTS (AJAX) ─────────────────────────────────────────────────────

    public function getComments(int $postId): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $myId    = (int) $this->session->get('userID');
        $comments = $this->wallModel->getComments($postId);

        if (empty($comments)) return $this->json(['comments' => []]);

        $commentIds = array_column($comments, 'wall_comment_id');
        $reactions  = $this->wallModel->getReactionSummaryBulk('comment', $commentIds, $myId);

        $out = [];
        foreach ($comments as $c) {
            $cid = (int) $c['wall_comment_id'];
            $out[] = [
                'wall_comment_id'   => $cid,
                'parent_comment_id' => $c['parent_comment_id'] ? (int) $c['parent_comment_id'] : null,
                'user_id_fk'        => (int) $c['user_id_fk'],
                'content'           => $c['content'],
                'age'               => $this->age($c['created_at']),
                'author_name'       => trim($c['fname'] . ' ' . $c['lname']),
                'author_photo'      => $this->photoUrl($c['photo']),
                'reaction_count'    => (int) $c['reaction_count'],
                'reactions'         => $reactions[$cid] ?? ['summary' => [], 'my_emoji' => null],
                'is_mine'           => (int) $c['user_id_fk'] === $myId,
            ];
        }

        return $this->json(['comments' => $out]);
    }

    public function addComment(int $postId): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $myId     = (int) $this->session->get('userID');
        $content  = trim($this->request->getPost('content') ?? '');
        $parentId = $this->request->getPost('parent_comment_id');
        $parentId = ($parentId !== null && $parentId !== '') ? (int) $parentId : null;

        if ($content === '') return $this->json(['error' => 'Comment cannot be empty.'], 422);

        $commentId = $this->wallModel->addComment($postId, $myId, $content, $parentId);

        return $this->json([
            'success' => true,
            'comment' => [
                'wall_comment_id'   => $commentId,
                'parent_comment_id' => $parentId,
                'user_id_fk'        => $myId,
                'content'           => $content,
                'age'               => 'Just now',
                'author_name'       => trim($this->session->get('fname') . ' ' . $this->session->get('name')),
                'author_photo'      => $this->photoUrl($this->session->get('photo')),
                'reaction_count'    => 0,
                'reactions'         => ['summary' => [], 'my_emoji' => null],
                'is_mine'           => true,
            ],
        ]);
    }

    public function deleteComment(int $commentId): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $myId    = (int) $this->session->get('userID');
        $comment = $this->wallModel->getComment($commentId);
        if (!$comment) return $this->json(['error' => 'Not found'], 404);

        $isAdmin = in_array((int) $this->session->get('roleCatID'), [1, 2, 7]);
        if ((int) $comment['user_id_fk'] !== $myId && !$isAdmin) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $this->wallModel->deleteComment($commentId);
        return $this->json(['success' => true]);
    }

    // ─── REACTIONS (AJAX) ────────────────────────────────────────────────────

    public function react(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $myId       = (int) $this->session->get('userID');
        $targetType = $this->request->getPost('target_type'); // 'post' or 'comment'
        $targetId   = (int) $this->request->getPost('target_id');
        $emoji      = $this->request->getPost('emoji');

        if (!in_array($targetType, ['post', 'comment'])) return $this->json(['error' => 'Invalid target'], 422);
        if (!$emoji) return $this->json(['error' => 'Emoji required'], 422);

        $result  = $this->wallModel->toggleReaction($targetType, $targetId, $myId, $emoji);
        $summary = $this->wallModel->getReactionSummary($targetType, $targetId, $myId);

        return $this->json(array_merge($result, $summary));
    }

    // ─── REACTION DETAIL (AJAX – who reacted) ───────────────────────────────

    public function reactionDetail(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $targetType = $this->request->getGet('target_type');
        $targetId   = (int) $this->request->getGet('target_id');

        if (!in_array($targetType, ['post', 'comment'])) {
            return $this->json(['error' => 'Invalid target type'], 422);
        }

        $rows    = $this->wallModel->getReactionDetail($targetType, $targetId);
        $grouped = [];
        foreach ($rows as $r) {
            $grouped[$r['emoji']][] = [
                'name'  => trim($r['fname'] . ' ' . $r['lname']),
                'photo' => $this->photoUrl($r['photo']),
            ];
        }

        return $this->json(['success' => true, 'reactions' => $grouped]);
    }

    // ─── GET POST DATA (AJAX – for edit modal) ──────────────────────────────

    public function getPostData(int $postId): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $myId = (int) $this->session->get('userID');
        $post = $this->wallModel->getPost($postId);
        if (!$post) return $this->json(['error' => 'Not found'], 404);
        if ((int) $post['user_id_fk'] !== $myId) return $this->json(['error' => 'Access denied'], 403);

        $mediaRaw = $this->wallModel->getMediaForPosts([$postId]);
        $media = array_map(fn($m) => [
            'wall_media_id' => (int) $m['wall_media_id'],
            'media_type'    => $m['media_type'],
            'file_src'      => $m['media_type'] === 'video_url'
                                ? $m['file_src']
                                : base_url(self::UPLOAD_DIR . $m['file_src']),
            'file_name'     => $m['file_name'] ?: $m['file_src'],
            'file_src_raw'  => $m['file_src'],
        ], $mediaRaw);

        return $this->json([
            'success' => true,
            'content' => $post['content'],
            'media'   => $media,
        ]);
    }

    // ─── UPDATE POST (AJAX) ──────────────────────────────────────────────────

    public function updatePost(int $postId): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->isLoggedIn()) return $this->json(['error' => 'Unauthorized'], 401);

        $myId = (int) $this->session->get('userID');
        $post = $this->wallModel->getPost($postId);
        if (!$post) return $this->json(['error' => 'Not found'], 404);
        if ((int) $post['user_id_fk'] !== $myId) return $this->json(['error' => 'Access denied'], 403);

        $content    = trim($this->request->getPost('content') ?? '');
        $deleteIds  = array_map('intval', array_filter((array)($this->request->getPost('delete_media_ids') ?? [])));
        $videoUrls  = array_filter(array_map('trim', (array)($this->request->getPost('video_urls') ?? [])));
        $newFiles   = $this->request->getFileMultiple('media') ?? [];

        // Get current media counts (before deletion)
        $allMedia     = $this->wallModel->getMediaForPosts([$postId]);
        $existImages  = count(array_filter($allMedia, fn($m) => $m['media_type'] === 'image'));
        $existFiles   = count(array_filter($allMedia, fn($m) => $m['media_type'] === 'file'));
        $existVideos  = count(array_filter($allMedia, fn($m) => $m['media_type'] === 'video_url'));

        // Count deletions by type
        $delById = [];
        foreach ($allMedia as $m) {
            if (in_array((int)$m['wall_media_id'], $deleteIds)) $delById[$m['media_type']] = ($delById[$m['media_type']] ?? 0) + 1;
        }
        $remainImages = $existImages - ($delById['image']     ?? 0);
        $remainFiles  = $existFiles  - ($delById['file']      ?? 0);
        $remainVideos = $existVideos - ($delById['video_url'] ?? 0);

        // Count new uploads being added
        $newImageCount = 0;
        $newFileCount  = 0;
        $validNewFiles = [];
        foreach ($newFiles as $file) {
            if (!$file || !$file->isValid() || $file->hasMoved()) continue;
            if ($file->getSize() > self::MAX_FILE_MB * 1024 * 1024) continue;
            $mime = $file->getMimeType();
            $type = in_array($mime, self::IMAGE_MIME) ? 'image' : (in_array($mime, self::FILE_MIME) ? 'file' : null);
            if (!$type) continue;
            if ($type === 'image' && ($remainImages + $newImageCount) >= 50) continue;
            if ($type === 'file'  && ($remainFiles  + $newFileCount)  >= 20) continue;
            if ($type === 'image') $newImageCount++;
            else $newFileCount++;
            $validNewFiles[] = ['file' => $file, 'type' => $type];
        }

        // Validate new video URLs
        $validNewVideos = [];
        foreach ($videoUrls as $url) {
            if (($remainVideos + count($validNewVideos)) >= 10) break;
            if (filter_var($url, FILTER_VALIDATE_URL)) $validNewVideos[] = $url;
        }

        // Nothing left after edit?
        $totalAfter = ($remainImages + $newImageCount) + ($remainFiles + $newFileCount) + ($remainVideos + count($validNewVideos));
        if ($content === '' && $totalAfter === 0) {
            return $this->json(['error' => 'Post must have content or media.'], 422);
        }

        // Apply deletions
        foreach ($allMedia as $m) {
            if (!in_array((int)$m['wall_media_id'], $deleteIds)) continue;
            if ($m['media_type'] !== 'video_url') {
                $path = FCPATH . self::UPLOAD_DIR . $m['file_src'];
                if (file_exists($path)) unlink($path);
            }
            $this->wallModel->deleteMedia((int) $m['wall_media_id']);
        }

        // Upload new files
        foreach ($validNewFiles as $item) {
            $file    = $item['file'];
            $type    = $item['type'];
            $ext     = strtolower($file->getExtension());
            $newName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $file->move($this->uploadDir(), $newName);
            $this->wallModel->addMedia($postId, $type, $newName, $file->getClientName());
        }

        // Add new video URLs
        foreach ($validNewVideos as $url) {
            $this->wallModel->addMedia($postId, 'video_url', $url);
        }

        // Update content
        $this->wallModel->updatePost($postId, $content);

        // Return refreshed post for re-rendering
        $updatedPost  = $this->wallModel->getPost($postId);
        $updatedMedia = $this->wallModel->getMediaForPosts([$postId]);

        return $this->json([
            'success' => true,
            'post' => [
                'wall_post_id'   => $postId,
                'user_id_fk'     => $myId,
                'content'        => $updatedPost['content'],
                'age'            => $this->age($updatedPost['created_at']),
                'author_name'    => trim($this->session->get('fname') . ' ' . $this->session->get('name')),
                'author_photo'   => $this->photoUrl($this->session->get('photo')),
                'comment_count'  => (int) ($updatedPost['comment_count'] ?? 0),
                'reaction_count' => 0,
                'reactions'      => ['summary' => [], 'my_emoji' => null],
                'media'          => array_map(fn($m) => [
                    'wall_media_id' => (int) $m['wall_media_id'],
                    'media_type'    => $m['media_type'],
                    'file_src'      => $m['media_type'] === 'video_url'
                                        ? $m['file_src']
                                        : base_url(self::UPLOAD_DIR . $m['file_src']),
                    'file_name'     => $m['file_name'] ?: $m['file_src'],
                ], $updatedMedia),
                'is_mine' => true,
            ],
        ]);
    }

    // ─── MEDIA VIEW ──────────────────────────────────────────────────────────

    public function viewMedia(int $mediaId): \CodeIgniter\HTTP\ResponseInterface|\CodeIgniter\HTTP\RedirectResponse
    {
        if (!$this->isLoggedIn()) return redirect()->to('auth/login');

        $m = $this->wallModel->getMedia($mediaId);
        if (!$m || $m['media_type'] === 'video_url') return redirect()->back();

        $path = FCPATH . self::UPLOAD_DIR . $m['file_src'];
        if (!file_exists($path)) return redirect()->back()->with('error', 'File not found.');

        $mime = mime_content_type($path) ?: 'application/octet-stream';
        $disp = in_array($mime, self::IMAGE_MIME) ? 'inline' : 'attachment';

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', $disp . '; filename="' . ($m['file_name'] ?: $m['file_src']) . '"')
            ->setHeader('Content-Length', (string) filesize($path))
            ->setBody(file_get_contents($path));
    }
}
