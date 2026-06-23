<?php if (!empty($streams)): ?>
    <div class="d-flex flex-wrap gap-2">
        <?php foreach($streams as $stream): ?>
            <span class="badge badge-light-primary py-2 px-3">
                <i class="ki-duotone ki-check fs-4 me-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
                <?= esc($stream['stream_name']) ?>
            </span>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <span class="badge badge-light-warning py-2 px-3">
        <i class="ki-duotone ki-exclamation fs-4 me-1">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
        <?= esc($empty_message) ?>
    </span>
<?php endif; ?>