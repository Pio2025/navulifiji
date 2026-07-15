<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Something Went Wrong | Navuli</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Inter, "Helvetica Neue", Helvetica, Arial, sans-serif;
            background: #f5f8fa;
            color: #3f4254;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .wrap {
            text-align: center;
            padding: 3rem 2rem;
            max-width: 480px;
            width: 100%;
        }
        .logo { font-size: 1.4rem; font-weight: 700; color: #009ef7; margin-bottom: 2.5rem; letter-spacing: -.5px; }
        .num { font-size: 8rem; font-weight: 900; line-height: 1; color: #e4e6ea; letter-spacing: -4px; }
        .icon-wrap {
            width: 72px; height: 72px; border-radius: 50%;
            background: #fff5f8;
            display: flex; align-items: center; justify-content: center;
            margin: 1.25rem auto;
        }
        .icon-wrap svg { width: 36px; height: 36px; color: #f1416c; fill: currentColor; }
        h1 { font-size: 1.75rem; font-weight: 700; color: #181c32; margin-bottom: .75rem; }
        p  { font-size: .95rem; color: #7e8299; line-height: 1.6; margin-bottom: 2rem; }
        .btns { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }
        .btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .65rem 1.4rem; border-radius: .475rem;
            font-size: .9rem; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer;
            transition: opacity .15s;
        }
        .btn:hover { opacity: .85; }
        .btn-light   { background: #f1f1f4; color: #3f4254; }
        .btn-primary { background: #009ef7; color: #fff; }
        .footer { margin-top: 3rem; font-size: .78rem; color: #b5b5c3; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="logo">Navuli</div>
    <div class="num">500</div>
    <div class="icon-wrap">
        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
    </div>
    <h1>Something Went Wrong</h1>
    <p>We hit an unexpected error. Our team has been notified and we're working on a fix. Please try again shortly.</p>
    <div class="btns">
        <a href="javascript:history.back()" class="btn btn-light">&#8592; Go Back</a>
        <a href="/" class="btn btn-primary">&#8962; Home</a>
    </div>
    <div class="footer">&copy; <?= date('Y') ?> Navuli – School Management Information System</div>
</div>
</body>
</html>
