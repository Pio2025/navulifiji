<style>
/* ── Parent dashboard ───────────────────────────────────────────────────────── */
.pd-hero { background: linear-gradient(135deg, #5f27cd 0%, #7c3aed 50%, #a855f7 100%); border-radius: 16px; overflow: hidden; position: relative; margin-bottom: 1.5rem; }
.pd-hero::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='500' height='200' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='420' cy='30' r='130' fill='rgba(255,255,255,.05)'/%3E%3Ccircle cx='60' cy='180' r='90' fill='rgba(255,255,255,.04)'/%3E%3Ccircle cx='240' cy='100' r='60' fill='rgba(255,255,255,.03)'/%3E%3C/svg%3E") no-repeat right top; }
.pd-hero-inner { position:relative; z-index:1; padding:2rem 2.25rem; display:flex; align-items:center; gap:1.75rem; flex-wrap:wrap; }
.pd-hero-avatar { width:88px; height:88px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,.3); flex-shrink:0; box-shadow:0 4px 20px rgba(0,0,0,.25); }
.pd-hero-name { font-size:1.6rem; font-weight:800; color:#fff; line-height:1.2; margin-bottom:.3rem; }
.pd-hero-sub { color:rgba(255,255,255,.78); font-size:.9rem; }
.pd-hero-badge { display:inline-flex; align-items:center; gap:.4rem; background:rgba(255,255,255,.15); backdrop-filter:blur(4px); border:1px solid rgba(255,255,255,.2); border-radius:20px; padding:.22rem .8rem; font-size:.78rem; color:#fff; font-weight:500; margin-top:.4rem; margin-right:.3rem; }
.pd-hero-right { margin-left:auto; text-align:right; }
.pd-hero-date { color:rgba(255,255,255,.55); font-size:.72rem; text-transform:uppercase; letter-spacing:.8px; }
.pd-hero-dateval { color:#fff; font-size:1.1rem; font-weight:700; margin-top:.1rem; }

/* ── Child tabs ──────────────────────────────────────────────────────────── */
.pd-child-tabs { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1.25rem; }
.pd-child-tab { display:flex; align-items:center; gap:.55rem; padding:.5rem 1rem; border-radius:10px; border:2px solid #e9edf0; background:#fff; cursor:pointer; transition:.15s; font-weight:600; font-size:.88rem; color:#5e6278; }
.pd-child-tab:hover { border-color:#c5c7d4; color:#181c32; }
.pd-child-tab.active { border-color:#7c3aed; background:#f5f0ff; color:#7c3aed; }
.pd-child-tab img { width:32px; height:32px; border-radius:50%; object-fit:cover; flex-shrink:0; }
.pd-child-panel { display:none; }
.pd-child-panel.active { display:block; }

/* ── Child info bar ──────────────────────────────────────────────────────── */
.pd-child-bar { display:flex; align-items:center; gap:1rem; flex-wrap:wrap; background:#fff; border:1px solid #e9edf0; border-radius:14px; padding:1rem 1.5rem; margin-bottom:1.25rem; box-shadow:0 2px 8px rgba(0,0,0,.04); }
.pd-child-bar-avatar { width:52px; height:52px; border-radius:50%; object-fit:cover; border:2px solid #e9edf0; flex-shrink:0; }
.pd-child-bar-name { font-size:1.05rem; font-weight:700; color:#181c32; }
.pd-child-bar-meta { font-size:.8rem; color:#7e8299; margin-top:.1rem; }
.pd-child-bar-chips { margin-left:auto; display:flex; gap:.5rem; flex-wrap:wrap; }

/* ── KPI cards ───────────────────────────────────────────────────────────── */
.pd-kpi { border-radius:14px; border:1px solid #e9edf0; background:#fff; padding:1.3rem 1.5rem; box-shadow:0 2px 8px rgba(0,0,0,.04); display:flex; align-items:center; gap:1rem; height:100%; }
.pd-kpi-icon { width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pd-kpi-val { font-size:1.75rem; font-weight:800; line-height:1; letter-spacing:-.4px; }
.pd-kpi-label { font-size:.74rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#a1a5b7; margin-top:.2rem; }
.pd-kpi-sub { font-size:.76rem; color:#7e8299; margin-top:.25rem; }

/* ── Section cards ───────────────────────────────────────────────────────── */
.pd-card { background:#fff; border-radius:14px; border:1px solid #e9edf0; box-shadow:0 2px 8px rgba(0,0,0,.04); overflow:hidden; }
.pd-card-head { padding:1rem 1.4rem; border-bottom:1px solid #f1f3f5; display:flex; align-items:center; gap:.55rem; }
.pd-card-title { font-weight:700; font-size:.93rem; color:#181c32; }
.pd-card-body { padding:1.2rem 1.4rem; }

/* ── Subject marks ───────────────────────────────────────────────────────── */
.pd-sm-row { display:flex; align-items:center; gap:.75rem; padding:.5rem 0; border-bottom:1px solid #f5f5f5; }
.pd-sm-row:last-child { border-bottom:none; }
.pd-sm-name { flex:1; font-size:.86rem; font-weight:600; color:#3f4254; min-width:0; }
.pd-sm-bar-wrap { width:120px; flex-shrink:0; }
.pd-sm-bar-bg { height:5px; border-radius:3px; background:#f1f3f5; overflow:hidden; }
.pd-sm-bar-fill { height:100%; border-radius:3px; transition:width .5s; }
.pd-sm-score { width:50px; text-align:right; font-size:.83rem; font-weight:700; flex-shrink:0; }
.pd-sm-grade { width:34px; text-align:center; flex-shrink:0; }

/* ── Term trend mini bars ────────────────────────────────────────────────── */
.pd-trend { display:flex; align-items:flex-end; gap:8px; height:60px; }
.pd-trend-bar-wrap { flex:1; display:flex; flex-direction:column; align-items:center; gap:3px; }
.pd-trend-bar-bg { width:100%; flex:1; background:#f1f3f5; border-radius:3px 3px 0 0; display:flex; align-items:flex-end; overflow:hidden; }
.pd-trend-bar-fill { width:100%; border-radius:3px 3px 0 0; transition:height .5s; }
.pd-trend-label { font-size:.68rem; color:#a1a5b7; font-weight:600; white-space:nowrap; }

/* ── Attendance ──────────────────────────────────────────────────────────── */
.pd-att-pills { display:flex; gap:.6rem; margin-bottom:1rem; }
.pd-att-pill { flex:1; text-align:center; padding:.6rem .5rem; border-radius:10px; }
.pd-att-pill-val { font-size:1.35rem; font-weight:800; line-height:1; }
.pd-att-pill-lbl { font-size:.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin-top:.25rem; color:#7e8299; }

/* ── Attendance radial ring ─────────────────────────────────────────────── */
.pd-att-ring-wrap { display:flex; justify-content:center; margin-bottom:.75rem; }
.pd-att-ring { width:110px; height:110px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pd-att-ring-inner { width:80px; height:80px; border-radius:50%; background:#fff; display:flex; flex-direction:column; align-items:center; justify-content:center; }
.pd-att-ring-pct { font-size:1.15rem; font-weight:800; line-height:1; }
.pd-att-ring-lbl { font-size:.62rem; font-weight:600; color:#a1a5b7; text-transform:uppercase; letter-spacing:.3px; margin-top:1px; }

/* ── Subject attendance bars ─────────────────────────────────────────────── */
.pd-sub-att-row { display:flex; align-items:center; gap:.6rem; padding:.35rem 0; }
.pd-sub-att-name { width:110px; font-size:.77rem; color:#5e6278; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.pd-sub-att-bar { flex:1; height:5px; border-radius:3px; background:#f1f3f5; overflow:hidden; }
.pd-sub-att-fill { height:100%; border-radius:3px; }
.pd-sub-att-pct { width:36px; text-align:right; font-size:.76rem; font-weight:600; color:#5e6278; }

/* ── Monthly mini bar ────────────────────────────────────────────────────── */
.pd-monthly { display:flex; align-items:flex-end; gap:4px; height:56px; margin-top:.5rem; }
.pd-monthly-col { flex:1; display:flex; flex-direction:column; align-items:center; gap:2px; }
.pd-monthly-bar-bg { width:100%; flex:1; background:#f1f3f5; border-radius:2px 2px 0 0; display:flex; align-items:flex-end; overflow:hidden; }
.pd-monthly-bar-fill { width:100%; border-radius:2px 2px 0 0; }
.pd-monthly-lbl { font-size:.58rem; color:#c4c7d5; font-weight:500; white-space:nowrap; overflow:hidden; width:100%; text-align:center; }

/* ── Conduct ──────────────────────────────────────────────────────────────── */
.pd-ci-row { display:flex; align-items:flex-start; gap:.75rem; padding:.6rem 0; border-bottom:1px solid #f5f5f5; }
.pd-ci-row:last-child { border-bottom:none; }
.pd-ci-dot { width:9px; height:9px; border-radius:50%; flex-shrink:0; margin-top:.35rem; }
.pd-ci-main { flex:1; min-width:0; }
.pd-ci-type { font-size:.83rem; font-weight:600; color:#181c32; }
.pd-ci-meta { font-size:.74rem; color:#a1a5b7; margin-top:.1rem; }
.pd-ci-pts { font-size:.83rem; font-weight:700; flex-shrink:0; }

/* ── Announcements ───────────────────────────────────────────────────────── */
.pd-ann-row { display:flex; align-items:flex-start; gap:.7rem; padding:.55rem 0; border-bottom:1px solid #f5f5f5; }
.pd-ann-row:last-child { border-bottom:none; }
.pd-ann-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; margin-top:.4rem; }
.pd-ann-title { font-size:.85rem; font-weight:600; color:#181c32; line-height:1.3; }
.pd-ann-date { font-size:.72rem; color:#a1a5b7; margin-top:.12rem; }

/* ── Empty state ─────────────────────────────────────────────────────────── */
.pd-empty { text-align:center; padding:2rem 1rem; color:#a1a5b7; }

/* ── Responsive ──────────────────────────────────────────────────────────── */
@media (max-width:767px) {
    .pd-hero-inner { padding:1.4rem; gap:.9rem; }
    .pd-hero-avatar { width:64px; height:64px; }
    .pd-hero-name { font-size:1.2rem; }
    .pd-hero-right { margin-left:0; text-align:left; }
    .pd-sm-bar-wrap { width:70px; }
    .pd-child-bar-chips { margin-left:0; }
}
</style>
