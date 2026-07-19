"use strict";

/**
 * Single client-side source of truth for every unread/badge surface in the app:
 * sidebar module badges (notice, announcement, conduct appeal, event, wall, message)
 * and the top-nav bell dropdown (Activity/Alert feed).
 *
 * Design: optimistic local updates, reconciled against dashboard/unread-counts (the
 * server's authoritative snapshot), kept in sync across tabs via BroadcastChannel +
 * localStorage, and pushed live over the shared chat.js Socket.IO connection via the
 * "navuli:notification" DOM event. Falls back to polling only while that socket is
 * disconnected, mirroring chat.js's own startPolling/stopPolling gating.
 */
var NavuliBadges = (function () {

    const STORAGE_KEY  = "navuli_badges";
    const CHANNEL_NAME = "navuli-badges";

    const BASE_URL           = (window.NAVULI_BASE_URL || "").replace(/\/$/, "");
    const COUNTS_URL         = BASE_URL + "/dashboard/unread-counts";
    const MARK_READ_URL      = BASE_URL + "/dashboard/mark-read";
    const NOTIF_URL          = BASE_URL + "/user/getNotifications";
    const NOTIF_MARK_READ_URL = BASE_URL + "/user/markNotificationsRead";
    const CSRF_TOKEN         = window.NAVULI_CSRF_TOKEN || "";

    const RESYNC_INTERVAL_MS = 4 * 60 * 1000; // drift-correction resync, regardless of connection state
    const FALLBACK_POLL_MS   = 45 * 1000;      // only while the shared socket is disconnected

    const DEFAULT_STATE = {
        notices: 0, announcements: 0, conduct_appeals: 0,
        events: 0, wall: 0, messages: 0, activity_alerts: 0,
    };

    // Server push / mark-read domain name -> state key
    const DOMAIN_TO_KEY = {
        notice: "notices",
        announcement: "announcements",
        conduct_appeal: "conduct_appeals",
        event: "events",
        wall: "wall",
        activity_alert: "activity_alerts",
    };

    // State key -> sidebar link href fragment, for badge rendering only
    // (messages has no mark-read domain of its own — chat.js already owns read-state there).
    const SIDEBAR_URL_PARTS = {
        notices: "dashboard/notice",
        announcements: "dashboard/announcement",
        conduct_appeals: "conduct/appeals",
        events: "event",
        wall: "wall",
        messages: "message",
    };

    const BADGE_STYLE = 'display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;'
        + 'border-radius:50%;background:#f1416c;color:#fff;font-size:9px;font-weight:700;line-height:1;'
        + 'flex-shrink:0;margin-left:6px;';

    let state         = Object.assign({}, DEFAULT_STATE);
    let channel        = null;
    let fallbackTimer   = null;
    let notifDropdownLoaded = false;

    // ------------------------------------------------------------------ persistence

    function loadFromStorage() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            state = Object.assign({}, DEFAULT_STATE, JSON.parse(raw));
        } catch (e) { /* corrupt/unavailable storage — start from defaults */ }
    }

    function saveToStorage() {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); } catch (e) {}
    }

    function broadcastState() {
        if (!channel) return;
        try { channel.postMessage({ type: "state", state: state }); } catch (e) {}
    }

    function persist() {
        saveToStorage();
        broadcastState();
    }

    // ------------------------------------------------------------------ rendering

    function zeroForCurrentPage(data) {
        const path = window.location.pathname;
        const out  = Object.assign({}, data);
        if (path.includes('dashboard/notice'))       out.notices         = 0;
        if (path.includes('dashboard/announcement')) out.announcements   = 0;
        if (path.includes('conduct/appeals'))        out.conduct_appeals = 0;
        if (path.includes('event'))                  out.events          = 0;
        if (path.includes('wall'))                   out.wall            = 0;
        if (path.includes('message'))                out.messages        = 0;
        return out;
    }

    function applySidebarBadge(urlPart, count) {
        document.querySelectorAll('[data-navuli-badge="' + urlPart + '"]').forEach(el => el.remove());
        if (!count || count < 1) return;
        const text = count >= 10 ? '9+' : String(count);
        document.querySelectorAll('a[href*="' + urlPart + '"]').forEach(function (link) {
            const badge = document.createElement('span');
            badge.setAttribute('data-navuli-badge', urlPart);
            badge.setAttribute('data-navuli-count', String(count));
            badge.style.cssText = BADGE_STYLE;
            badge.textContent = text;
            const title = link.querySelector('.menu-title');
            if (title) title.after(badge); else link.appendChild(badge);
        });
    }

    // Roll every submenu badge up into a single badge on its parent module menu item.
    function updateModuleBadges() {
        document.querySelectorAll('.menu-item.menu-accordion').forEach(function (moduleEl) {
            const moduleLink = moduleEl.querySelector(':scope > .menu-link');
            if (!moduleLink) return;

            const existing = moduleLink.querySelector(':scope > [data-navuli-module-badge]');
            if (existing) existing.remove();

            let total = 0;
            moduleEl.querySelectorAll('.menu-sub [data-navuli-badge]').forEach(function (b) {
                total += parseInt(b.getAttribute('data-navuli-count') || '0', 10);
            });
            if (total < 1) return;

            const badge = document.createElement('span');
            badge.setAttribute('data-navuli-module-badge', '1');
            badge.style.cssText = BADGE_STYLE;
            badge.textContent = total >= 10 ? '9+' : String(total);
            const title = moduleLink.querySelector(':scope > .menu-title');
            if (title) title.after(badge); else moduleLink.appendChild(badge);
        });
    }

    function renderBellBadge() {
        const badge = document.getElementById('notif-badge');
        const label = document.getElementById('notif-unread-label');
        if (!badge || !label) return;
        const count = state.activity_alerts || 0;
        if (count > 0) {
            badge.textContent   = count >= 10 ? '9+' : String(count);
            badge.style.display = '';
            label.textContent    = count + ' unread';
            label.style.display  = '';
        } else {
            badge.style.display = 'none';
            label.style.display = 'none';
        }
    }

    function render() {
        const display = zeroForCurrentPage(state);
        Object.keys(SIDEBAR_URL_PARTS).forEach(function (key) {
            applySidebarBadge(SIDEBAR_URL_PARTS[key], display[key]);
        });
        updateModuleBadges();
        renderBellBadge();
    }

    // ------------------------------------------------------------------ reconciliation (authoritative snapshot)

    function reconcile() {
        return fetch(COUNTS_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                state = Object.assign({}, state, {
                    notices:         data.notices         || 0,
                    announcements:   data.announcements   || 0,
                    conduct_appeals: data.conduct_appeals  || 0,
                    events:          data.events          || 0,
                    wall:            data.wall            || 0,
                    messages:        data.messages        || 0,
                });
                return fetch(NOTIF_URL, { credentials: 'same-origin' }).then(r => r.json());
            })
            .then(data => {
                if (data && data.success) state.activity_alerts = data.unread_count || 0;
                render();
                persist();
            })
            .catch(() => {});
    }

    // ------------------------------------------------------------------ optimistic increments (event-driven push)

    function increment(domain) {
        const key = DOMAIN_TO_KEY[domain];
        if (!key) return;
        state[key] = (state[key] || 0) + 1;
        render();
        persist();
    }

    // ------------------------------------------------------------------ optimistic mark-read (with retry/backoff)

    function postJson(url, body, attempt) {
        attempt = attempt || 0;
        return fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify(body || {}),
        })
            .then(r => r.json())
            .then(data => {
                if (!data || !data.success) throw new Error('mark-read failed');
                return true;
            })
            .catch(() => {
                if (attempt >= 3) {
                    // Retries exhausted — don't guess, just reconcile against server truth.
                    reconcile();
                    return false;
                }
                const delay = Math.min(8000, 500 * Math.pow(2, attempt));
                return new Promise(resolve => setTimeout(resolve, delay))
                    .then(() => postJson(url, body, attempt + 1));
            });
    }

    function markRead(domain) {
        const key = DOMAIN_TO_KEY[domain];
        if (key) {
            state[key] = 0;
            render();
            persist();
        }
        postJson(MARK_READ_URL, { domain: domain });
    }

    function markAllActivityRead() {
        state.activity_alerts = 0;
        render();
        persist();
        postJson(NOTIF_MARK_READ_URL, {});
    }

    // Last-resort delivery for a mark-read that was still in flight when the tab unloads.
    function sendBeaconMarkRead(domain) {
        if (!navigator.sendBeacon) return;
        const blob = new Blob([JSON.stringify({ domain: domain })], { type: 'application/json' });
        navigator.sendBeacon(MARK_READ_URL, blob);
    }

    // ------------------------------------------------------------------ bell dropdown list (Activity/Alert tabs)

    function renderNotifItem(n) {
        const unread = n.status === 'Unread'
            ? '<span class="bullet bullet-dot bg-danger h-6px w-6px ms-1"></span>'
            : '';
        return '<div class="d-flex flex-stack py-3">'
            + '<div class="d-flex align-items-center">'
            + '<div class="symbol symbol-35px me-4"><span class="symbol-label bg-light-' + n.theme + '">' + n.icon + '</span></div>'
            + '<div class="mb-0 me-2"><span class="fw-bold text-gray-800 fs-7">' + n.title + unread + '</span>'
            + '<div class="text-gray-500 fs-8">' + n.desc + '</div></div></div>'
            + '<span class="badge badge-light fs-9 text-nowrap">' + n.age + '</span></div>';
    }

    function renderNotifList(items, containerId) {
        const el = document.getElementById(containerId);
        if (!el) return;
        if (!items || !items.length) {
            el.innerHTML = '<div class="text-center text-muted fs-8 py-8">No entries found.</div>';
            return;
        }
        el.innerHTML = items.map(renderNotifItem).join('');
    }

    function loadNotifDropdown() {
        if (notifDropdownLoaded) return;
        notifDropdownLoaded = true;
        fetch(NOTIF_URL, { credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                renderNotifList(data.activities, 'notif-activity-list');
                renderNotifList(data.alerts, 'notif-alert-list');
            })
            .catch(() => {});
    }

    // ------------------------------------------------------------------ resilience (fallback polling)

    function isSocketConnected() {
        return !!(window.NavuliChat && typeof window.NavuliChat.isSocketConnected === 'function'
            && window.NavuliChat.isSocketConnected());
    }

    function startFallbackPolling() {
        if (fallbackTimer) return;
        fallbackTimer = setInterval(reconcile, FALLBACK_POLL_MS);
    }

    function stopFallbackPolling() {
        if (fallbackTimer) { clearInterval(fallbackTimer); fallbackTimer = null; }
    }

    // ------------------------------------------------------------------ wiring

    function wireEvents() {
        // Live push from the server (via chat.js's shared socket connection).
        document.addEventListener('navuli:notification', function (e) {
            const detail = e.detail || {};
            if (detail.domain) increment(detail.domain);
        });

        // Authoritative message count, pushed whenever chat.js recalculates it.
        document.addEventListener('navuli:messagesCount', function (e) {
            const count = (e.detail && e.detail.count) || 0;
            if (count === state.messages) return;
            state.messages = count;
            render();
            persist();
        });

        document.addEventListener('navuli:connectionStatus', function (e) {
            const status = e.detail && e.detail.status;
            if (status === 'live') { stopFallbackPolling(); reconcile(); }
            else if (status === 'offline') { startFallbackPolling(); }
        });

        if (channel) {
            channel.onmessage = function (e) {
                if (e.data && e.data.type === 'state') {
                    state = Object.assign({}, DEFAULT_STATE, e.data.state);
                    render();
                }
            };
        }

        const bellTrigger = document.getElementById('kt_menu_item_wow');
        if (bellTrigger) {
            bellTrigger.addEventListener('click', function () {
                loadNotifDropdown();
                markAllActivityRead();
            });
        }

        // Explicit "mark all as read" affordances (bell dropdown header button, etc.)
        document.querySelectorAll('[data-navuli-mark-read]').forEach(function (el) {
            el.addEventListener('click', function (evt) {
                evt.preventDefault();
                const domain = el.getAttribute('data-navuli-mark-read');
                if (domain === 'activity_alert') markAllActivityRead();
                else markRead(domain);
            });
        });

        // Visiting a badge-bearing sidebar link implies its contents are about to be
        // read — zero the badge immediately rather than waiting for the next page's reconcile.
        Object.keys(DOMAIN_TO_KEY).forEach(function (domain) {
            const key     = DOMAIN_TO_KEY[domain];
            const urlPart = SIDEBAR_URL_PARTS[key];
            if (!urlPart) return;
            document.querySelectorAll('a[href*="' + urlPart + '"]').forEach(function (link) {
                link.addEventListener('click', function () { markRead(domain); });
            });
        });

        window.addEventListener('pagehide', function () {
            saveToStorage();
        });
    }

    function init() {
        if (typeof BroadcastChannel !== 'undefined') {
            channel = new BroadcastChannel(CHANNEL_NAME);
        }

        loadFromStorage();
        render();          // instant paint from last known state — no flash-to-zero
        wireEvents();
        reconcile();        // reconcile once against the authoritative snapshot
        setInterval(reconcile, RESYNC_INTERVAL_MS);

        if (!isSocketConnected()) startFallbackPolling();
    }

    return { init, markRead, markAllActivityRead, sendBeaconMarkRead };
})();

KTUtil.onDOMContentLoaded(() => NavuliBadges.init());
