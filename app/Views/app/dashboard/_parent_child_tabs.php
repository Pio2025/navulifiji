<?php
// Expects $pr_children (array) and $activeIndex (int|null) to be set by the caller before including.
foreach ($pr_children as $i => $c):
    $cPhoto = $c['photo'] ?? null;
    $cPhotoUrl = ($cPhoto && file_exists(FCPATH . 'uploads/profilePhoto/' . $cPhoto))
                 ? base_url('uploads/profilePhoto/' . $cPhoto)
                 : base_url('app/assets/media/avatars/blank.png');
?>
<button type="button" class="pd-child-tab <?= $activeIndex === $i ? 'active' : '' ?>" data-child="<?= $i ?>">
    <img src="<?= esc($cPhotoUrl) ?>" alt="">
    <?= esc($c['fname']) ?> <?= esc($c['lname']) ?>
</button>
<?php endforeach; ?>
