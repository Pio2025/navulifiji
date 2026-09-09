<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow" />
    <title>ID Card Verification | Navuli</title>
    <link rel="shortcut icon" href="<?= base_url('icon.png') ?>" />
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 24px rgba(0,0,0,.08);
            padding: 28px 24px;
            text-align: center;
        }
        .photo {
            width: 84px; height: 84px; border-radius: 50%;
            object-fit: cover; margin: 0 auto 14px; display: block;
            border: 3px solid #eef1f5;
        }
        .badge {
            width: 64px; height: 64px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-size: 32px; color: #fff;
        }
        .badge.ok { background: #50cd89; }
        .badge.bad { background: #f1416c; }
        h1 { font-size: 18px; margin: 0 0 4px; color: #181c32; }
        .sub { color: #7e8299; font-size: 13px; margin-bottom: 20px; }
        .rows { text-align: left; border-top: 1px solid #eef1f5; margin-top: 8px; }
        .row { display: flex; justify-content: space-between; padding: 10px 2px; border-bottom: 1px solid #eef1f5; font-size: 13.5px; }
        .row .label { color: #7e8299; }
        .row .value { color: #181c32; font-weight: 600; text-align: right; }
        .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .status-pill.active { background: #e8fff3; color: #17c653; }
        .status-pill.inactive { background: #fff1f1; color: #f1416c; }
        .footer { margin-top: 20px; font-size: 11px; color: #b5b5c3; }
        .logo { height: 34px; margin-bottom: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <img class="logo" src="<?= base_url('icon.png') ?>" alt="Navuli" />
        <?php if ($valid && $info): ?>
            <?php if (!empty($info['photo'])): ?>
                <img class="photo" src="<?= esc($info['photo']) ?>" alt="<?= esc($info['name']) ?>" />
            <?php else: ?>
                <div class="badge ok">&#10003;</div>
            <?php endif; ?>
            <h1>Valid Navuli ID Card</h1>
            <div class="sub">This card was issued through Navuli's school management system.</div>

            <div class="rows">
                <div class="row"><span class="label">Name</span><span class="value"><?= esc($info['name']) ?></span></div>
                <div class="row"><span class="label">Designation</span><span class="value"><?= esc($info['designation']) ?></span></div>
                <div class="row">
                    <span class="label">Status</span>
                    <span class="value">
                        <span class="status-pill <?= $info['status'] === 'Active' ? 'active' : 'inactive' ?>">
                            <?= esc($info['status']) ?>
                        </span>
                    </span>
                </div>
            </div>
        <?php else: ?>
            <div class="badge bad">&#10007;</div>
            <h1>Not a Valid Card</h1>
            <div class="sub">This verification link is invalid, has expired, or the card it refers to no longer exists.</div>
        <?php endif; ?>

        <div class="footer">Navuli Fiji &middot; School Management Information System</div>
    </div>
</body>
</html>
