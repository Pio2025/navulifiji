<?php if (!empty($streams)): ?>
    <?php if ($show_count): ?>
        <div class="mb-2 text-muted">
            <small>Found <?= $total_count ?> stream(s)</small>
        </div>
    <?php endif; ?>
    
    <ul class="list-group">
        <?php foreach($streams as $stream): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <i class="ki-duotone ki-right fs-4 text-primary me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <?= esc($stream['stream_name']) ?>
                </div>
                <span class="badge bg-light text-dark">ID: <?= $stream['stream_id'] ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="alert alert-light border">
        <i class="ki-duotone ki-information fs-3 text-muted me-2">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
        </i>
        <?= esc($empty_message) ?>
    </div>
<?php endif; ?>