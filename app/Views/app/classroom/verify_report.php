<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Report Card Verification | Navuli</title>
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
        .score { display: inline-flex; align-items: center; gap: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 18px; margin: 4px 0 16px; }
        .score .pct { font-size: 22px; font-weight: 800; color: #1d4ed8; }
        .score .grade { font-size: 14px; font-weight: 700; color: #6b7280; }
        .footer { margin-top: 20px; font-size: 11px; color: #b5b5c3; }
        .logo { height: 34px; margin-bottom: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <img class="logo" src="<?= base_url('icon.png') ?>" alt="Navuli" />
        <?php if ($valid && $info): ?>
            <div class="badge ok">&#10003;</div>
            <h1>Valid Report Card</h1>
            <div class="sub">This report card was issued and published by the school shown below.</div>

            <?php if ($info['overallPct'] !== null): ?>
            <div class="score">
                <span class="pct"><?= esc((string) $info['overallPct']) ?>%</span>
                <?php if ($info['grade']): ?><span class="grade">Grade <?= esc($info['grade']) ?></span><?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="rows">
                <div class="row"><span class="label">Student</span><span class="value"><?= esc($info['studentName']) ?></span></div>
                <div class="row"><span class="label">School</span><span class="value"><?= esc($info['schoolName']) ?></span></div>
                <div class="row"><span class="label">Class</span><span class="value"><?= esc($info['className']) ?> &middot; <?= esc((string) $info['classYear']) ?></span></div>
                <div class="row"><span class="label">Term</span><span class="value">Term <?= esc((string) $info['term']) ?></span></div>
                <?php if (!empty($info['publishedAt'])): ?>
                <div class="row"><span class="label">Published</span><span class="value"><?= esc(date('d M Y', strtotime($info['publishedAt']))) ?></span></div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="badge bad">&#10007;</div>
            <h1>Not a Valid Report</h1>
            <div class="sub">This verification link is invalid, has expired, or the report it refers to is no longer published.</div>
        <?php endif; ?>

        <div class="footer">Navuli Fiji School Management System</div>
    </div>
</body>
</html>
