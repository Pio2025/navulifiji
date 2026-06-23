<select class="form-select" name="stream_id" id="stream_select_<?= $level_id ?>">
    <option value="">-- Select Stream --</option>
    <?php if (!empty($streams)): ?>
        <?php foreach($streams as $stream): ?>
            <option value="<?= $stream['stream_id'] ?>">
                <?= esc($stream['stream_name']) ?>
            </option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="" disabled><?= esc($empty_message) ?></option>
    <?php endif; ?>
</select>