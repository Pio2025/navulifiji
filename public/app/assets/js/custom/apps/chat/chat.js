"use strict";

var NavuliChat = (function () {

    // ------------------------------------------------------------------ State

    let socket                = null;
    let socketConnected       = false;
    let currentUserId         = null;
    let currentConversationId = null;
    let currentTargetUserId   = null;
    let currentContactName    = "";
    let typingTimer           = null;
    let isTypingActive        = false;
    let totalUnreadCount      = 0;
    let lastKnownMessageId    = 0;
    let pollTimer             = null;

    const seenMessageIds    = new Set();
    const messageFilesCache = {};

    let currentContactPhotoUrl = null;

    const callState = {
        active: false, direction: null, callType: "voice",
        peerId: null, peerName: "", peerPhoto: null, conversationId: null,
        incomingOffer: null, startTime: null, durationTimer: null,
        callTimeout: null, incomingTimeout: null,
        localStream: null, pc: null, pendingCandidates: [], muted: false,
    };

    const ICE_SERVERS = [
        { urls: "stun:stun.l.google.com:19302" },
        { urls: "stun:stun1.l.google.com:19302" },
        { urls: "stun:stun2.l.google.com:19302" },
        { urls: "stun:stun3.l.google.com:19302" },
    ];

    const SOCKET_URL = (window.NAVULI_SOCKET_URL || "").replace(/\/$/, "");
    const BASE_URL   = (window.NAVULI_BASE_URL   || "").replace(/\/$/, "");

    // ------------------------------------------------------------------ API helper

    function api(method, path, body, isFormData) {
        const opts = { method, credentials: "same-origin" };
        if (body) {
            if (isFormData) { opts.body = body; }
            else { opts.headers = { "Content-Type": "application/json" }; opts.body = JSON.stringify(body); }
        }
        return fetch(BASE_URL + path, opts)
            .then(r => r.json())
            .catch(err => { console.error("[NavuliChat] API error:", err); return { success: false }; });
    }

    // ------------------------------------------------------------------ Connection status

    function setConnectionStatus(status) {
        document.dispatchEvent(new CustomEvent("navuli:connectionStatus", { detail: { status } }));

        const dotEl  = document.getElementById("kt_chat_conn_dot");
        const textEl = document.getElementById("kt_chat_conn_text");
        if (!dotEl || !textEl) return;
        if (status === "live") {
            dotEl.className    = "badge badge-circle w-8px h-8px bg-success me-1";
            textEl.textContent = "Live";
            textEl.className   = "fs-9 fw-semibold text-success";
        } else if (status === "offline") {
            dotEl.className    = "badge badge-circle w-8px h-8px bg-warning me-1";
            textEl.textContent = "Reconnecting…";
            textEl.className   = "fs-9 fw-semibold text-warning";
        } else {
            dotEl.className    = "badge badge-circle w-8px h-8px bg-secondary me-1";
            textEl.textContent = "Connecting…";
            textEl.className   = "fs-9 fw-semibold text-muted";
        }
    }

    // ------------------------------------------------------------------ Typing bubble (in messages area)

    function showTypingBubble() {
        document.querySelectorAll("[data-kt-element='messages']").forEach(container => {
            let bubble = container.querySelector("[data-kt-element='typing-bubble']");
            if (!bubble) return;
            // Set the initial letter of the other person
            const initialEl = bubble.querySelector("[data-kt-element='typing-initial']");
            if (initialEl) initialEl.textContent = (currentContactName || "?").charAt(0).toUpperCase();
            bubble.classList.remove("d-none");
            container.appendChild(bubble); // move to bottom
        });
        scrollToBottom();
    }

    function hideTypingBubble() {
        document.querySelectorAll("[data-kt-element='typing-bubble']").forEach(el => el.classList.add("d-none"));
    }

    // ------------------------------------------------------------------ Lightbox

    const lb = {
        images: [], index: 0,
        open(msgId, startIdx) {
            const images = messageFilesCache[msgId];
            if (!images?.length) return;
            this.images = images;
            this.index  = Math.max(0, Math.min(startIdx, images.length - 1));
            this._render();
            document.getElementById("navuli_lightbox").classList.add("lb-open");
            document.body.style.overflow = "hidden";
        },
        close() {
            document.getElementById("navuli_lightbox").classList.remove("lb-open");
            document.getElementById("lb_img").src = "";
            document.body.style.overflow = "";
        },
        prev() { if (this.images.length < 2) return; this.index = (this.index - 1 + this.images.length) % this.images.length; this._render(); },
        next() { if (this.images.length < 2) return; this.index = (this.index + 1) % this.images.length; this._render(); },
        _render() {
            const img = this.images[this.index];
            const imgEl = document.getElementById("lb_img");
            imgEl.style.opacity = "0";
            imgEl.onload  = () => imgEl.style.opacity = "1";
            imgEl.onerror = () => imgEl.style.opacity = "1";
            imgEl.src = img.url; imgEl.alt = img.name;
            document.getElementById("lb_counter").textContent = `${this.index + 1} / ${this.images.length}`;
            document.getElementById("lb_name").textContent    = img.name || "";
            const multi = this.images.length > 1;
            const p = document.getElementById("lb_prev"), n = document.getElementById("lb_next");
            if (p) p.style.display = multi ? "" : "none";
            if (n) n.style.display = multi ? "" : "none";
        },
    };

    function initLightbox() {
        document.getElementById("lb_close")?.addEventListener("click", () => lb.close());
        document.getElementById("lb_prev") ?.addEventListener("click", () => lb.prev());
        document.getElementById("lb_next") ?.addEventListener("click", () => lb.next());
        document.getElementById("navuli_lightbox")?.addEventListener("click", e => {
            if (e.target.id === "navuli_lightbox" || e.target.id === "lb_img_wrap") lb.close();
        });
        document.addEventListener("keydown", e => {
            if (!document.getElementById("navuli_lightbox")?.classList.contains("lb-open")) return;
            if (e.key === "Escape")     lb.close();
            if (e.key === "ArrowLeft")  lb.prev();
            if (e.key === "ArrowRight") lb.next();
        });
        let tx = 0;
        document.getElementById("lb_img_wrap")?.addEventListener("touchstart", e => { tx = e.changedTouches[0].clientX; }, { passive: true });
        document.getElementById("lb_img_wrap")?.addEventListener("touchend",   e => { const dx = e.changedTouches[0].clientX - tx; if (Math.abs(dx) > 50) dx < 0 ? lb.next() : lb.prev(); }, { passive: true });
    }

    // Delegated photo cell clicks
    document.addEventListener("click", e => {
        const cell = e.target.closest(".chat-photo-cell[data-lb-msg]");
        if (cell) lb.open(cell.dataset.lbMsg, parseInt(cell.dataset.lbIdx) || 0);
    });

    // ------------------------------------------------------------------ Delete / share context menu
    // NOTE: All getElementById calls are deferred to initDeleteMenu() called from init()
    // because this script loads in <head> before the DOM is ready.

    let delMenu        = null;
    let cdmCopy        = null;
    let cdmShare       = null;
    let cdmDelMe       = null;
    let cdmDelEveryone = null;
    let cdmEvSep       = null;

    let delMenuMsgId   = null;
    let delMenuIsMine  = false;
    let delMenuMsgEl   = null;   // reference to the actual message element
    let delMenuMsgType = "text"; // 'text' | 'image' | 'file' | 'deleted'

    function initDeleteMenu() {
        delMenu        = document.getElementById("chat_del_menu");
        cdmCopy        = document.getElementById("cdm_copy");
        cdmShare       = document.getElementById("cdm_share");
        cdmDelMe       = document.getElementById("cdm_del_me");
        cdmDelEveryone = document.getElementById("cdm_del_everyone");
        cdmEvSep       = document.getElementById("cdm_everyone_sep");
        if (!delMenu) return;

        // "..." button click — delegated on document
        document.addEventListener("click", e => {
            const btn = e.target.closest(".chat-msg-action");
            if (!btn) return;
            e.stopPropagation();
            const msgEl = btn.closest("[data-message-id]");
            if (!msgEl) return;
            // Toggle: click same button again → close
            if (!delMenu.classList.contains("d-none") && delMenuMsgId === msgEl.dataset.messageId) {
                closeDelMenu(); return;
            }
            openDelMenu(msgEl, btn);
        });

        // Close on outside click
        document.addEventListener("click", e => {
            if (!delMenu.classList.contains("d-none") &&
                !delMenu.contains(e.target) &&
                !e.target.closest(".chat-msg-action")) {
                closeDelMenu();
            }
        });

        // Close on Escape
        document.addEventListener("keydown", e => {
            if (e.key === "Escape") closeDelMenu();
        });

        // ── "Copy text"
        cdmCopy?.addEventListener("click", () => {
            if (!delMenuMsgEl) return;
            const textEl = delMenuMsgEl.querySelector("[data-kt-element='message-text']");
            const text   = textEl?.textContent?.trim() || "";
            closeDelMenu();
            if (!text) return;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => showCopiedToast()).catch(() => fallbackCopy(text));
            } else {
                fallbackCopy(text);
            }
        });

        // ── "Share message"
        cdmShare?.addEventListener("click", () => {
            if (!delMenuMsgEl) return;
            // Capture all state before closeDelMenu() nulls it out
            const type  = delMenuMsgType;
            const msgId = delMenuMsgId;
            const text  = delMenuMsgEl.querySelector("[data-kt-element='message-text']")?.textContent?.trim() || "";
            closeDelMenu();
            if (type === "image" || type === "file") {
                openShareModal({ type, messageId: msgId });
            } else if (text) {
                openShareModal({ type: "text", text });
            }
        });

        // ── "Remove for me"
        cdmDelMe?.addEventListener("click", () => {
            if (!delMenuMsgId) return;
            const mid = delMenuMsgId;
            closeDelMenu();
            doDeleteMessage(mid, "me");
        });

        // ── "Remove for everyone"
        cdmDelEveryone?.addEventListener("click", () => {
            if (!delMenuMsgId || !delMenuIsMine) return;
            const mid = delMenuMsgId;
            closeDelMenu();
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    title: "Remove for everyone?",
                    text: "This message will be removed for all participants.",
                    icon: "warning", showCancelButton: true,
                    confirmButtonText: "Remove", cancelButtonText: "Cancel",
                    buttonsStyling: false,
                    customClass: { confirmButton: "btn btn-danger me-2", cancelButton: "btn btn-light" },
                }).then(r => { if (r.isConfirmed) doDeleteMessage(mid, "everyone"); });
            } else {
                doDeleteMessage(mid, "everyone");
            }
        });
    }

    function openDelMenu(msgEl, btnEl) {
        if (!delMenu) return;

        delMenuMsgEl   = msgEl;
        delMenuMsgId   = msgEl.dataset.messageId;
        delMenuIsMine  = msgEl.dataset.senderId === String(currentUserId);
        delMenuMsgType = msgEl.dataset.messageType || "text";

        const isText    = delMenuMsgType === "text";
        const isDeleted = delMenuMsgType === "deleted";

        // Show/hide items based on context
        if (cdmCopy)        cdmCopy.style.display        = isText ? "" : "none";
        if (cdmShare)       cdmShare.style.display       = (isText || delMenuMsgType === "image" || delMenuMsgType === "file") && !isDeleted ? "" : "none";
        if (cdmDelEveryone) cdmDelEveryone.style.display = delMenuIsMine && !isDeleted ? "" : "none";
        if (cdmEvSep)       cdmEvSep.style.display       = delMenuIsMine && !isDeleted ? "" : "none";
        if (cdmDelMe) {
            cdmDelMe.innerHTML = `<span class="cdm-icon">🗑</span>${isDeleted ? "Remove from view" : "Remove for me"}`;
            cdmDelMe.classList.toggle("danger", isDeleted);
        }

        // Position the menu near the button, flipping if near edges
        const rect  = btnEl.getBoundingClientRect();
        const menuW = 224;
        const menuH = delMenu.offsetHeight || 120;
        let top  = rect.bottom + 6;
        let left = rect.right - menuW;
        if (top  + menuH  > window.innerHeight - 8) top  = rect.top - menuH - 6;
        if (left < 8)                                left = 8;
        if (left + menuW  > window.innerWidth  - 8) left = window.innerWidth - menuW - 8;

        delMenu.style.top  = top  + "px";
        delMenu.style.left = left + "px";
        delMenu.classList.remove("d-none");
    }

    function closeDelMenu() {
        delMenu?.classList.add("d-none");
        delMenuMsgId  = null;
        delMenuMsgEl  = null;
    }

    function fallbackCopy(text) {
        const ta = document.createElement("textarea");
        ta.value = text;
        ta.style.cssText = "position:fixed;top:-9999px;left:-9999px;";
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand("copy"); showCopiedToast(); } catch {}
        document.body.removeChild(ta);
    }

    // ------------------------------------------------------------------ Share modal

    let shareModalBs   = null;
    let sharePayload   = null;   // { type: "text", text } | { type: "image", messageId }
    let csmPage        = 1;
    let csmLoading     = false;
    let csmHasMore     = true;
    let csmSearchTimer = null;

    function openShareModal(payload) {
        sharePayload = payload;
        const modalEl = document.getElementById("chat_share_modal");
        if (!modalEl) return;
        shareModalBs = shareModalBs || new bootstrap.Modal(modalEl);

        // Build preview
        const preview = document.getElementById("csm_preview");
        if (preview) {
            if (payload.type === "text") {
                preview.innerHTML = "";
                preview.textContent = payload.text.length > 120 ? payload.text.substring(0, 117) + "…" : payload.text;
            } else if (payload.type === "image") {
                const files = messageFilesCache[payload.messageId] || [];
                if (files.length) {
                    const thumbs = files.slice(0, 4).map(f =>
                        `<img src="${escHtml(f.url)}" style="width:44px;height:44px;object-fit:cover;border-radius:6px;flex-shrink:0;" alt="">`
                    ).join("");
                    preview.innerHTML = `<div class="d-flex align-items-center gap-2">
                        ${thumbs}
                        ${files.length > 4 ? `<span class="text-muted fs-9">+${files.length - 4} more</span>` : ""}
                        <span class="text-muted fs-9 ms-auto">${files.length} photo${files.length > 1 ? "s" : ""}</span>
                    </div>`;
                } else {
                    preview.textContent = "📷 Photo";
                }
            } else if (payload.type === "file") {
                const files = messageFilesCache[payload.messageId] || [];
                if (files.length) {
                    preview.innerHTML = files.map(f => {
                        const icon = fileIcon(f.type);
                        return `<div class="d-flex align-items-center gap-2 py-1">
                            <i class="ki-duotone ${icon} fs-2 text-primary flex-shrink-0"><span class="path1"></span><span class="path2"></span></i>
                            <span class="text-gray-700 fs-8 text-truncate flex-grow-1">${escHtml(f.name)}</span>
                            <span class="text-muted fs-9 flex-shrink-0">${formatSize(f.size)}</span>
                        </div>`;
                    }).join("");
                } else {
                    preview.textContent = "📎 File attachment";
                }
            }
        }

        const searchEl = document.getElementById("csm_search");
        const listEl   = document.getElementById("csm_list");
        if (searchEl) searchEl.value = "";
        if (listEl)   listEl.querySelectorAll(".csm-row").forEach(el => el.remove());
        csmPage    = 1;
        csmHasMore = true;

        shareModalBs.show();
        csmLoadUsers();
    }

    function csmLoadUsers() {
        if (csmLoading || !csmHasMore) return;
        csmLoading = true;

        const listEl    = document.getElementById("csm_list");
        const loadingEl = document.getElementById("csm_loading");
        const emptyEl   = document.getElementById("csm_empty");
        const searchEl  = document.getElementById("csm_search");
        if (loadingEl) loadingEl.style.display = "block";
        if (emptyEl)   emptyEl.style.display   = "none";

        const q = encodeURIComponent(searchEl?.value.trim() || "");
        fetch(`${BASE_URL}/user/chatUserList?page=${csmPage}&search=${q}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }, credentials: "same-origin"
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            let added = 0;
            data.users.forEach(u => {
                // Exclude the user currently being chatted with
                if (String(u.user_id) === String(currentTargetUserId)) return;
                listEl.insertBefore(csmBuildRow(u), loadingEl);
                added++;
            });
            csmHasMore = data.hasMore;
            csmPage    = data.nextPage;
            if (emptyEl && csmPage === 2 && added === 0 && !csmHasMore) {
                emptyEl.style.display = "";
            }
        })
        .catch(() => {})
        .finally(() => {
            if (loadingEl) loadingEl.style.display = "none";
            csmLoading = false;
        });
    }

    function csmBuildRow(u) {
        const initials = ((u.fname || "").charAt(0) + (u.lname || "").charAt(0)).toUpperCase();
        const avatar   = u.profile_photo
            ? `<img src="${BASE_URL}/uploads/profilePhoto/${escHtml(u.profile_photo)}" class="rounded-circle" style="width:38px;height:38px;object-fit:cover;" alt="">`
            : `<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary bg-light-primary fs-8" style="width:38px;height:38px;flex-shrink:0;">${initials}</div>`;
        const dot = `<span style="position:absolute;bottom:1px;right:1px;width:9px;height:9px;border-radius:50%;background:${u.online_status === "Online" ? "#50cd89" : "#a1a5b7"};border:2px solid #fff;"></span>`;

        const row = document.createElement("div");
        row.className        = "csm-row d-flex align-items-center gap-3 px-2 py-2 rounded-2 mb-1";
        row.style.transition = "background .15s";
        row.dataset.csmUserId = String(u.user_id);   // used by delegated click
        row.innerHTML = `
            <div style="position:relative;flex-shrink:0;">${avatar}${dot}</div>
            <div style="min-width:0;flex:1;">
                <div class="fw-semibold text-gray-800 fs-7 text-truncate">${escHtml(u.fname)} ${escHtml(u.lname)}</div>
                <div class="fs-9 ${u.online_status === "Online" ? "text-success" : "text-muted"}">${escHtml(u.online_status)}</div>
            </div>
            <button type="button" class="btn btn-sm btn-primary csm-send-btn py-1 px-3 fs-9 flex-shrink-0">
                <i class="ki-duotone ki-send fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>Send
            </button>`;
        return row;
    }

    // Delegated send — one listener on the container, works for all dynamically added rows
    function csmSend(targetUserId, btn) {
        if (!sharePayload) return;
        btn.disabled  = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm align-middle"></span>`;

        fetch(`${BASE_URL}/chat/conversation/${targetUserId}`, { credentials: "same-origin" })
            .then(r => r.json())
            .then(res => {
                if (!res.success || !res.conversation_id) { csmResetBtn(btn); return; }
                const convId = res.conversation_id;
                return sharePayload.type === "text"
                    ? csmSendText(convId, sharePayload.text, targetUserId, btn)
                    : csmForwardAttachment(convId, sharePayload.messageId, sharePayload.type, targetUserId, btn);
            })
            .catch(err => { console.error("[CSM] conversation error:", err); csmResetBtn(btn); });
    }

    function csmSendText(convId, text, targetUserId, btn) {
        const form = new FormData();
        form.append("conversation_id", convId);
        form.append("content", text);
        return fetch(`${BASE_URL}/chat/messages`, { method: "POST", body: form, credentials: "same-origin" })
            .then(r => r.json())
            .then(res => {
                if (!res.success || !res.message) { csmResetBtn(btn); return; }
                csmBroadcast(convId, res.message, targetUserId);
                csmMarkSent(btn);
            })
            .catch(err => { console.error("[CSM] send error:", err); csmResetBtn(btn); });
    }

    async function csmForwardAttachment(convId, messageId, msgType, targetUserId, btn) {
        const files = messageFilesCache[messageId];
        if (!files?.length) { csmResetBtn(btn); return; }
        try {
            const form = new FormData();
            form.append("conversation_id", convId);
            if (msgType === "image") {
                // Multiple images → files[] field
                for (const f of files) {
                    const blob = await fetch(f.url, { credentials: "same-origin" }).then(r => r.blob());
                    form.append("files[]", blob, f.name || "photo.jpg");
                }
            } else {
                // Single file → file field (server saves as doc/pdf/etc.)
                const f    = files[0];
                const blob = await fetch(f.url, { credentials: "same-origin" }).then(r => r.blob());
                form.append("file", blob, f.name || "file");
            }
            const res = await fetch(`${BASE_URL}/chat/upload`, { method: "POST", body: form, credentials: "same-origin" }).then(r => r.json());
            if (!res.success || !res.message) { csmResetBtn(btn); return; }
            csmBroadcast(convId, res.message, targetUserId);
            csmMarkSent(btn);
        } catch (err) { console.error("[CSM] attachment forward error:", err); csmResetBtn(btn); }
    }

    function csmBroadcast(convId, message, targetUserId) {
        if (socket && socketConnected) {
            socket.emit("new_message", { conversationId: convId, message, receiverUserId: targetUserId });
        }
    }

    function csmMarkSent(btn) {
        btn.disabled  = true;
        btn.className = "btn btn-sm btn-success py-1 px-3 fs-9 flex-shrink-0";
        btn.innerHTML = `<i class="ki-duotone ki-check fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>Sent`;
    }

    function csmResetBtn(btn) {
        btn.disabled  = false;
        btn.className = "btn btn-sm btn-primary csm-send-btn py-1 px-3 fs-9 flex-shrink-0";
        btn.innerHTML = `<i class="ki-duotone ki-send fs-6 me-1"><span class="path1"></span><span class="path2"></span></i>Send`;
    }

    function initShareModal() {
        const searchEl = document.getElementById("csm_search");
        const listEl   = document.getElementById("csm_list");
        if (!searchEl || !listEl) return;

        // Delegated click — catches all .csm-send-btn regardless of when rows were added
        listEl.addEventListener("click", e => {
            const btn = e.target.closest(".csm-send-btn");
            if (!btn || btn.disabled) return;
            const userId = btn.closest(".csm-row")?.dataset?.csmUserId;
            if (userId) csmSend(userId, btn);
        });

        searchEl.addEventListener("input", () => {
            clearTimeout(csmSearchTimer);
            csmSearchTimer = setTimeout(() => {
                listEl.querySelectorAll(".csm-row").forEach(el => el.remove());
                csmPage    = 1;
                csmHasMore = true;
                csmLoadUsers();
            }, 350);
        });

        listEl.addEventListener("scroll", () => {
            if (csmHasMore && !csmLoading && listEl.scrollTop + listEl.clientHeight >= listEl.scrollHeight - 60) {
                csmLoadUsers();
            }
        });
    }

    // ------------------------------------------------------------------ Minimized chat bar

    const MINI_TOTAL_MAX   = 6; // max individual slots before overflow kicks in
    const MINI_VISIBLE_MAX = 5; // when overflow active, show 5 individual + 1 overflow
    let minimizedChats     = []; // [{ userId, name, photo, initials, isOnline }]

    function minimizeCurrentChat() {
        if (!currentTargetUserId) return;
        const nameEl   = document.getElementById("kt_drawer_chat_name");
        const avatarImg= document.querySelector("#kt_drawer_chat_avatar img");
        const dotEl    = document.getElementById("kt_drawer_chat_status_dot");

        const name     = nameEl?.textContent?.trim() || "User";
        const photo    = avatarImg?.src || null;
        const words    = name.trim().split(/\s+/);
        const initials = ((words[0]?.[0] || "") + (words[1]?.[0] || "")).toUpperCase() || "?";
        const isOnline = dotEl?.classList.contains("bg-success") ?? false;
        const userId   = String(currentTargetUserId);

        if (!minimizedChats.find(c => c.userId === userId)) {
            minimizedChats.push({ userId, name, photo, initials, isOnline });
        }

        const drawer = KTDrawer.getInstance(document.getElementById("kt_drawer_chat"));
        if (drawer) drawer.hide();

        updateMiniBar();
    }

    function restoreChat(userId) {
        const chat = minimizedChats.find(c => c.userId === String(userId));
        if (!chat) return;
        minimizedChats = minimizedChats.filter(c => c.userId !== String(userId));
        updateMiniBar();

        // Update drawer header directly
        const avatarEl = document.getElementById("kt_drawer_chat_avatar");
        const nameEl   = document.getElementById("kt_drawer_chat_name");
        const dotEl    = document.getElementById("kt_drawer_chat_status_dot");
        const textEl   = document.getElementById("kt_drawer_chat_status_text");
        if (avatarEl) avatarEl.innerHTML = chat.photo
            ? `<img src="${chat.photo}" class="rounded-circle" style="width:45px;height:45px;object-fit:cover;" alt="">`
            : `<div class="symbol-label bg-light-primary rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary fs-5" style="width:45px;height:45px;">${chat.initials}</div>`;
        if (nameEl)  nameEl.textContent  = chat.name;
        if (dotEl)   dotEl.className     = `badge badge-circle w-10px h-10px me-1 ${chat.isOnline ? "bg-success" : "bg-secondary"}`;
        if (textEl)  textEl.textContent  = chat.isOnline ? "Online" : "Offline";

        // Open conversation then show drawer
        const syntheticTrigger = document.createElement("div");
        syntheticTrigger.dataset.userName  = chat.name;
        syntheticTrigger.dataset.userPhoto = chat.photo || "";
        openConversation(parseInt(userId), syntheticTrigger).then(() => {
            const drawer = KTDrawer.getInstance(document.getElementById("kt_drawer_chat"));
            if (drawer) drawer.show();
        });
    }

    function removeMiniChat(userId) {
        minimizedChats = minimizedChats.filter(c => c.userId !== String(userId));
        updateMiniBar();
    }

    function updateMiniBar() {
        const bar = document.getElementById("chat_minimized_bar");
        if (!bar) return;
        bar.innerHTML = "";
        document.getElementById("chat_mini_overflow_popup")?.remove();
        document.getElementById("chat_mini_bar_options_menu")?.remove();

        const total   = minimizedChats.length;
        if (total === 0) return;

        const visible  = total <= MINI_TOTAL_MAX
            ? minimizedChats
            : minimizedChats.slice(total - MINI_VISIBLE_MAX);
        const overflow = total > MINI_TOTAL_MAX
            ? minimizedChats.slice(0, total - MINI_VISIBLE_MAX)
            : [];

        // Three-dots options button — visible on bar hover via CSS
        const optsEl = document.createElement("div");
        optsEl.className = "chat-mini-item chat-mini-opts";
        optsEl.title = "Options";
        optsEl.innerHTML = `
            <div class="chat-mini-avatar-wrap">
                <div class="chat-mini-avatar d-flex align-items-center justify-content-center" style="font-size:1.3rem;font-weight:900;color:#5e6278;letter-spacing:-.5px;">⋯</div>
            </div>
            <div class="chat-mini-label">Options</div>`;
        optsEl.addEventListener("click", e => { e.stopPropagation(); showMiniBarOptions(optsEl); });
        bar.appendChild(optsEl);

        if (overflow.length) bar.appendChild(buildOverflowItem(overflow));
        visible.forEach(chat => bar.appendChild(buildMiniItem(chat)));
    }

    function showMiniBarOptions(btn) {
        const existing = document.getElementById("chat_mini_bar_options_menu");
        if (existing) { existing.remove(); return; }

        const rect = btn.getBoundingClientRect();
        const menu = document.createElement("div");
        menu.id = "chat_mini_bar_options_menu";
        menu.innerHTML = `
            <div class="chat-mini-bar-opt danger" id="chat_mini_close_all">
                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                Close all chats
            </div>`;
        menu.style.bottom = (window.innerHeight - rect.top + 8) + "px";
        menu.style.left   = rect.left + "px";
        document.body.appendChild(menu);

        menu.querySelector("#chat_mini_close_all")?.addEventListener("click", () => {
            minimizedChats = [];
            updateMiniBar();
            menu.remove();
        });

        setTimeout(() => {
            document.addEventListener("click", function closeOpts(e) {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.remove();
                    document.removeEventListener("click", closeOpts);
                }
            });
        }, 0);
    }

    function buildMiniItem(chat) {
        const avatarInner = chat.photo
            ? `<img src="${escHtml(chat.photo)}" class="chat-mini-avatar" alt="">`
            : `<div class="chat-mini-avatar d-flex align-items-center justify-content-center fw-bold text-primary bg-light-primary" style="font-size:.9rem;">${chat.initials}</div>`;

        const el = document.createElement("div");
        el.className = "chat-mini-item";
        el.dataset.miniUserId = chat.userId;
        el.innerHTML = `
            <div class="chat-mini-avatar-wrap">
                ${avatarInner}
                <span class="chat-mini-online-dot" style="background:${chat.isOnline ? "#50cd89" : "#a1a5b7"};"></span>
            </div>
            <button type="button" class="chat-mini-close" title="Close">✕</button>
            <div class="chat-mini-label">${escHtml(chat.name)}</div>`;

        el.addEventListener("click", e => {
            if (e.target.closest(".chat-mini-close")) removeMiniChat(chat.userId);
            else restoreChat(chat.userId);
        });
        return el;
    }

    function buildOverflowItem(overflow) {
        const el = document.createElement("div");
        el.className = "chat-mini-item";
        el.id = "chat_mini_overflow_btn";
        el.innerHTML = `
            <div class="chat-mini-avatar-wrap">
                <div class="chat-mini-avatar d-flex align-items-center justify-content-center fw-bold text-primary bg-light-primary" style="font-size:.85rem;">+${overflow.length}</div>
            </div>
            <div class="chat-mini-label">${overflow.length} more</div>`;
        el.addEventListener("click", () => toggleOverflowPopup(overflow));
        return el;
    }

    function toggleOverflowPopup(overflow) {
        const existing = document.getElementById("chat_mini_overflow_popup");
        if (existing) { existing.remove(); return; }

        const overflowBtn = document.getElementById("chat_mini_overflow_btn");
        if (!overflowBtn) return;
        const rect = overflowBtn.getBoundingClientRect();

        const popup = document.createElement("div");
        popup.id = "chat_mini_overflow_popup";
        popup.innerHTML = overflow.map(chat => `
            <div class="chat-mini-overflow-row d-flex align-items-center gap-3 px-4 py-2" data-user-id="${escHtml(chat.userId)}" style="cursor:pointer;">
                <div class="position-relative flex-shrink-0" style="width:36px;height:36px;">
                    ${chat.photo
                        ? `<img src="${escHtml(chat.photo)}" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;" alt="">`
                        : `<div class="rounded-circle bg-primary d-flex align-items-center justify-content-center fw-bold text-white" style="width:36px;height:36px;font-size:.8rem;">${chat.initials}</div>`}
                    <span style="position:absolute;bottom:1px;right:1px;width:10px;height:10px;border-radius:50%;background:${chat.isOnline ? "#50cd89" : "#a1a5b7"};border:2px solid #fff;"></span>
                </div>
                <span class="fw-semibold text-gray-800 fs-7 flex-grow-1 text-truncate">${escHtml(chat.name)}</span>
                <button type="button" class="btn btn-xs btn-icon btn-light btn-active-light-danger chat-mini-overflow-close" data-user-id="${escHtml(chat.userId)}" style="width:24px;height:24px;min-width:0;flex-shrink:0;">
                    <i class="ki-duotone ki-cross fs-7"><span class="path1"></span><span class="path2"></span></i>
                </button>
            </div>`).join("");

        popup.style.bottom = (window.innerHeight - rect.top + 8) + "px";
        popup.style.left   = rect.left + "px";
        document.body.appendChild(popup);

        popup.addEventListener("click", e => {
            const closeBtn = e.target.closest(".chat-mini-overflow-close");
            const row      = e.target.closest(".chat-mini-overflow-row");
            if (closeBtn) {
                removeMiniChat(closeBtn.dataset.userId);
                popup.remove();
            } else if (row) {
                restoreChat(row.dataset.userId);
                popup.remove();
            }
        });

        // Close on outside click
        setTimeout(() => {
            document.addEventListener("click", function outsideClose(e) {
                if (!popup.contains(e.target) && !overflowBtn.contains(e.target)) {
                    popup.remove();
                    document.removeEventListener("click", outsideClose);
                }
            });
        }, 0);
    }

    function initMinimize() {
        document.getElementById("kt_drawer_chat_minimize")?.addEventListener("click", minimizeCurrentChat);

        // Compose button → toggle the floating user list popup
        document.getElementById("chat_compose_btn")?.addEventListener("click", () => {
            if (typeof window.toggleUclPopup === "function") window.toggleUclPopup();
        });
    }

    function showCopiedToast() {
        if (typeof Swal === "undefined") return;
        Swal.mixin({ toast: true, position: "top", showConfirmButton: false, timer: 1800, timerProgressBar: true })
            .fire({ icon: "success", title: "Copied to clipboard" });
    }

    function doDeleteMessage(messageId, scope) {
        const form = new FormData();
        form.append("scope", scope);

        api("POST", `/chat/message/${messageId}/delete`, form, true).then(res => {
            if (!res.success) {
                console.warn("[NavuliChat] Delete failed:", res.message);
                return;
            }
            if (scope === "me") {
                // Remove from DOM for current user only — silent
                document.querySelectorAll(`[data-message-id="${messageId}"]`).forEach(el => el.remove());
            } else {
                // Replace with placeholder for current user immediately
                applyDeletedPlaceholder(String(messageId));
                // Broadcast to the other party via socket
                if (socket && socketConnected) {
                    socket.emit("message_deleted", {
                        conversationId: res.conversationId,
                        messageId:      res.messageId,
                        scope:          "everyone",
                    });
                }
            }
        });
    }

    function applyDeletedPlaceholder(messageId) {
        document.querySelectorAll(`[data-message-id="${messageId}"]`).forEach(msgEl => {
            const textEl = msgEl.querySelector("[data-kt-element='message-text']");
            if (textEl) {
                textEl.style.background = "transparent";
                textEl.style.padding    = "4px 0";
                textEl.innerHTML        = `<span class="chat-deleted-msg"><i class="ki-duotone ki-information-2 fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>This message was removed</span>`;
            }
            msgEl.dataset.messageType = "deleted";
        });
    }

    // ------------------------------------------------------------------ Short-poll fallback

    function pollForNewMessages() {
        if (!currentConversationId || socketConnected) return;
        api("GET", `/chat/messages/${currentConversationId}/new?after=${lastKnownMessageId}`).then(res => {
            if (!res.success || !res.messages?.length) return;
            let hasNew = false;
            res.messages.forEach(msg => {
                const key = String(msg.id);
                if (seenMessageIds.has(key)) return;
                seenMessageIds.add(key);
                if (seenMessageIds.size > 500) seenMessageIds.clear();
                renderMessage(msg);
                hasNew = true;
                const id = parseInt(msg.id);
                if (!isNaN(id) && id > lastKnownMessageId) lastKnownMessageId = id;
            });
            if (hasNew) { scrollToBottom(); fetchUnreadCount(); }
        });
    }

    function startPolling() { if (!pollTimer) pollTimer = setInterval(pollForNewMessages, 2500); }
    function stopPolling()  { if (pollTimer)  { clearInterval(pollTimer); pollTimer = null; } }

    // ------------------------------------------------------------------ Socket.IO

    function connectSocket() {
        return api("GET", "/chat/token").then(res => {
            if (!res.success) { setConnectionStatus("offline"); startPolling(); return; }
            currentUserId = res.userId;
            if (!SOCKET_URL) { setConnectionStatus("offline"); startPolling(); return; }
            setConnectionStatus("connecting");

            socket = io(SOCKET_URL, {
                auth: { token: res.token },
                transports: ["websocket", "polling"],
                reconnection: true, reconnectionAttempts: Infinity,
                reconnectionDelay: 1000, reconnectionDelayMax: 10000,
            });

            socket.on("connect", () => {
                socketConnected = true;
                setConnectionStatus("live");
                stopPolling();
                console.log("[NavuliChat] Socket connected, userId:", currentUserId, "socketId:", socket.id);
                if (currentConversationId) {
                    socket.emit("join_conversation", currentConversationId);
                    if (lastKnownMessageId > 0) {
                        api("GET", `/chat/messages/${currentConversationId}/new?after=${lastKnownMessageId}`).then(res => {
                            if (!res.success || !res.messages?.length) return;
                            res.messages.forEach(msg => {
                                const key = String(msg.id);
                                if (seenMessageIds.has(key)) return;
                                seenMessageIds.add(key);
                                renderMessage(msg);
                                const id = parseInt(msg.id);
                                if (!isNaN(id) && id > lastKnownMessageId) lastKnownMessageId = id;
                            });
                            scrollToBottom();
                        });
                    }
                }
            });

            socket.on("connect_error", (err) => { socketConnected = false; setConnectionStatus("offline"); startPolling(); console.error("[NavuliChat] Socket connect_error:", err.message); });
            socket.on("disconnect",    () => { socketConnected = false; setConnectionStatus("offline"); startPolling(); });

            // Generic badge/notification push (notices, announcements, events, wall, conduct appeals,
            // activity log) — re-dispatched as a DOM event so badge-store.js can stay decoupled from
            // the socket connection itself.
            socket.on("notification", (payload) => {
                document.dispatchEvent(new CustomEvent("navuli:notification", { detail: payload }));
            });

            socket.on("message_received", ({ conversationId, message }) => {
                const key = String(message.id);
                if (seenMessageIds.has(key)) return;
                seenMessageIds.add(key);
                if (seenMessageIds.size > 500) seenMessageIds.clear();
                const id = parseInt(message.id);
                if (!isNaN(id) && id > lastKnownMessageId) lastKnownMessageId = id;

                if (String(conversationId) === String(currentConversationId)) {
                    renderMessage(message);
                    scrollToBottom();
                    doMarkRead(conversationId);
                    hideEmptyState();
                } else {
                    fetchUnreadCount();
                    showToastNotification(message);
                    if (message.sender_id) {
                        document.dispatchEvent(new CustomEvent("navuli:unreadBadge", {
                            detail: { userId: String(message.sender_id), action: "increment" }
                        }));
                    }
                }
            });

            // Other party deleted a message for everyone — show placeholder
            socket.on("message_deleted", ({ messageId, scope }) => {
                if (scope === "everyone") {
                    applyDeletedPlaceholder(String(messageId));
                }
            });

            // Other party reacted to (or unreacted from) a message
            socket.on("message_reacted", ({ messageId, reactions }) => {
                renderReactions(String(messageId), reactions);
            });

            socket.on("user_typing", ({ userId, conversationId, isTyping }) => {
                if (String(conversationId) === String(currentConversationId)) {
                    if (isTyping) showTypingBubble(); else hideTypingBubble();
                }
            });

            socket.on("user_status", ({ userId, status }) => {
                const existingRows = document.querySelectorAll(`[data-user-id="${userId}"]`);
                existingRows.forEach(row => {
                    const dot = row.querySelector(`span[style*="border-radius:50%"]`);
                    if (dot) dot.style.background = status === "online" ? "#50cd89" : "#a1a5b7";
                });
                if (String(userId) === String(currentTargetUserId)) {
                    const dot  = document.getElementById("kt_drawer_chat_status_dot");
                    const text = document.getElementById("kt_drawer_chat_status_text");
                    if (dot)  dot.className   = `badge badge-circle w-10px h-10px me-1 ${status === "online" ? "bg-success" : "bg-secondary"}`;
                    if (text) text.textContent = status === "online" ? "Online" : "Offline";
                }

                // A user who just connected and isn't rendered in any chat user list yet
                // (e.g. they're beyond the currently-loaded page) — fetch their info, if
                // they're visible to me, and let the user-list pages add them live.
                if (status === "online" && existingRows.length === 0) {
                    api("GET", `/user/chatUserInfo/${userId}`).then(res => {
                        if (!res.success || !res.user) return;
                        document.dispatchEvent(new CustomEvent("navuli:userOnline", { detail: res.user }));
                    });
                }
            });

            // ---- Refresh JWT before each reconnect so the token never expires ----
            socket.io.on("reconnect_attempt", async () => {
                try {
                    const r = await api("GET", "/chat/token");
                    if (r.success) socket.auth = { token: r.token };
                } catch {}
            });

            // ---- Call signaling ----
            socket.on("incoming_call", ({ callerId, callerName, callerPhoto, callType, offer, conversationId }) => {
                handleIncomingCall({ callerId, callerName, callerPhoto, callType, offer, conversationId });
            });

            socket.on("call_answered", async ({ answer }) => {
                if (!callState.pc || callState.direction !== "outgoing") return;
                try {
                    await callState.pc.setRemoteDescription(new RTCSessionDescription(answer));
                    for (const c of callState.pendingCandidates) {
                        await callState.pc.addIceCandidate(new RTCIceCandidate(c));
                    }
                    callState.pendingCandidates = [];
                } catch (e) { console.warn("[NavuliChat] call_answered SDP error:", e); }
                clearTimeout(callState.callTimeout);
                showActiveCallUI();
            });

            socket.on("call_declined", ({ reason } = {}) => {
                if (callState.direction !== "outgoing") return;
                const { peerName, conversationId, callType, peerId } = callState;
                saveCallEvent(conversationId, callType, "declined", 0, peerId);
                CallAudio.playDeclined();
                resetCallState();
                if (reason === "busy") {
                    Swal.fire({
                        icon: "info",
                        title: "Call not connected",
                        html: `<strong>${escHtml(peerName || "User")}</strong> is already on another call and could not be reached.<br><br>Please try again when they are available.`,
                        confirmButtonText: "OK",
                        confirmButtonColor: "#1a56db",
                        showClass: { popup: "animate__animated animate__fadeInDown animate__faster" },
                        hideClass: { popup: "animate__animated animate__fadeOutUp animate__faster" },
                    });
                } else {
                    showCallSwal("warning", (peerName || "User") + " declined the call.", "");
                }
            });

            socket.on("call_cancelled", () => {
                document.getElementById("navuli_incoming_call")?.classList.add("d-none");
                CallAudio.playBusy();
                resetCallState();
                showCallSwal("info", "Call was cancelled.", "");
            });

            socket.on("call_blocked", () => {
                document.getElementById("navuli_incoming_call")?.classList.add("d-none");
                CallAudio.playBusy();
                resetCallState();
                showCallSwal("warning", "You can't call this user.", "");
            });

            socket.on("call_already_in_call", () => {
                CallAudio.playBusy();
                resetCallState();
                Swal.fire({
                    icon: "warning",
                    title: "Already in a call",
                    html: "You are already on an active call in another tab or window.<br><br>Please end that call before starting a new one.",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#1a56db",
                    showClass: { popup: "animate__animated animate__fadeInDown animate__faster" },
                    hideClass: { popup: "animate__animated animate__fadeOutUp animate__faster" },
                });
            });

            socket.on("call_ended", () => {
                endCall("ended", false);
            });

            socket.on("ice_candidate", async ({ candidate }) => {
                if (!callState.pc) { callState.pendingCandidates.push(candidate); return; }
                try {
                    if (callState.pc.remoteDescription) {
                        await callState.pc.addIceCandidate(new RTCIceCandidate(candidate));
                    } else {
                        callState.pendingCandidates.push(candidate);
                    }
                } catch (e) { console.warn("[NavuliChat] ICE candidate error:", e); }
            });
        });
    }

    function showToastNotification(message) {
        fetchUnreadCount();
        if (typeof Swal === "undefined") return;
        const name    = `${message.fname ?? ""} ${message.lname ?? ""}`.trim() || "Someone";
        const preview = message.message_type === "text" ? (message.content || "").substring(0, 60)
                      : message.message_type === "image" ? "📷 Photo"
                      : message.message_type === "deleted" ? "Message removed"
                      : message.message_type === "call" ? (() => { try { const m = JSON.parse(message.content || "{}"); return m.call_type === "video" ? "📹 Video call" : "📞 Audio call"; } catch { return "📞 Call"; } })()
                      : "📎 File";
        Swal.mixin({ toast: true, position: "top", showConfirmButton: false, timer: 4000, timerProgressBar: true, showCloseButton: true })
            .fire({ icon: "info", title: name, text: preview });
    }

    // ------------------------------------------------------------------ Open conversation

    function openConversation(targetUserId, triggerEl) {
        return api("GET", `/chat/conversation/${targetUserId}`).then(res => {
            if (!res.success) {
                if (res.message && typeof Swal !== "undefined") {
                    Swal.fire({ icon: "info", title: "Can't start chat", text: res.message });
                }
                return;
            }
            currentConversationId = res.conversation_id;
            currentTargetUserId   = targetUserId;
            lastKnownMessageId    = 0;
            currentContactName     = triggerEl?.dataset?.userName ?? "";
            currentContactPhotoUrl = triggerEl?.dataset?.userPhoto || null;

            if (socket && socketConnected) socket.emit("join_conversation", currentConversationId);
            document.querySelectorAll("[data-kt-chat-user-id]").forEach(el => el.classList.remove("active", "bg-light"));
            (triggerEl?.closest("[data-kt-chat-user-id]") ?? triggerEl)?.classList.add("active", "bg-light");

            updateChatHeader(triggerEl);
            document.querySelectorAll("[data-kt-element='messages']").forEach(c => clearRenderedMessages(c));
            hideTypingBubble();
            fetchBlockStatus(targetUserId);
            return loadMessages();
        });
    }

    function updateChatHeader(triggerEl) {
        if (!triggerEl) return;
        document.querySelectorAll("[data-kt-element='chat-header-name']").forEach(el => el.textContent = triggerEl.dataset.userName || "");
        document.querySelectorAll("[data-kt-element='chat-header-photo']").forEach(el => {
            el.src = triggerEl.dataset.userPhoto || `${BASE_URL}/app/assets/media/avatars/blank.png`;
        });
    }

    // ------------------------------------------------------------------ Block

    let convBlocked = false;
    let blockedByMe = false;

    function fetchBlockStatus(targetUserId) {
        api("GET", `/chat/block-status/${targetUserId}`).then(res => {
            if (!res.success) return;
            convBlocked = res.blocked;
            blockedByMe = res.blockedByMe;
            updateBlockUi();
        });
    }

    function updateBlockUi() {
        document.querySelectorAll("[data-kt-element='block-toggle']").forEach(el => {
            el.textContent = blockedByMe ? "Unblock" : "Block";
        });
        setComposerDisabled(convBlocked);
    }

    function setComposerDisabled(disabled) {
        document.querySelectorAll("#kt_chat_messenger, #kt_drawer_chat_messenger").forEach(messenger => {
            ["input", "send", "attach", "photo-attach", "emoji"].forEach(name => {
                const target = messenger.querySelector(`[data-kt-element='${name}']`);
                if (target) target.disabled = disabled;
            });

            let notice = messenger.querySelector(".chat-blocked-notice");
            if (disabled) {
                if (!notice) {
                    notice = document.createElement("div");
                    notice.className = "chat-blocked-notice text-danger fs-8 fw-semibold mb-2";
                    notice.textContent = "You can't message this user.";
                    messenger.querySelector("[data-kt-element='input']")?.insertAdjacentElement("beforebegin", notice);
                }
            } else {
                notice?.remove();
            }
        });

        const callButtonsDisabled = disabled || callState.active;
        document.getElementById("kt_drawer_chat_voice_call")?.toggleAttribute("disabled", callButtonsDisabled);
        document.getElementById("kt_drawer_chat_video_call")?.toggleAttribute("disabled", callButtonsDisabled);
    }

    function initHeaderDropdown() {
        document.addEventListener("click", e => {
            const blockBtn = e.target.closest("[data-kt-element='block-toggle']");
            if (blockBtn) {
                e.preventDefault();
                if (!currentTargetUserId) return;

                const doToggle = () => {
                    api("POST", `/chat/block/${currentTargetUserId}`).then(res => {
                        if (!res.success) return;
                        blockedByMe = res.blocked;
                        fetchBlockStatus(currentTargetUserId);
                    });
                };

                if (!blockedByMe && typeof Swal !== "undefined") {
                    Swal.fire({
                        title: "Block this user?",
                        text: "They won't be able to message or call you, and you won't be able to message or call them.",
                        icon: "warning", showCancelButton: true,
                        confirmButtonText: "Block", cancelButtonText: "Cancel",
                        buttonsStyling: false,
                        customClass: { confirmButton: "btn btn-danger me-2", cancelButton: "btn btn-light" },
                    }).then(r => { if (r.isConfirmed) doToggle(); });
                } else {
                    doToggle();
                }
                return;
            }

            const transcriptBtn = e.target.closest("[data-kt-element='chat-transcript']");
            if (transcriptBtn) {
                e.preventDefault();
                if (currentConversationId) window.open(`${BASE_URL}/chat/transcript/${currentConversationId}`, "_blank");
                return;
            }

            const openMsgBtn = e.target.closest("[data-kt-element='open-in-message']");
            if (openMsgBtn) {
                e.preventDefault();
                if (currentTargetUserId) {
                    const params = new URLSearchParams({ name: currentContactName || "", photo: currentContactPhotoUrl || "" });
                    window.location.href = `${BASE_URL}/message/${currentTargetUserId}?${params.toString()}`;
                }
                return;
            }

            const clearBtn = e.target.closest("[data-kt-element='clear-conversation']");
            if (clearBtn) {
                e.preventDefault();
                if (!currentConversationId) return;
                const doClear = () => {
                    api("POST", `/chat/conversation/${currentConversationId}/clear`).then(res => {
                        if (!res.success) return;
                        document.querySelectorAll("[data-kt-element='messages']").forEach(c => clearRenderedMessages(c));
                        showEmptyState();
                    });
                };
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        title: "Clear this conversation?",
                        text: "All messages will be removed for you. The other person will still see them until they clear it too.",
                        icon: "warning", showCancelButton: true,
                        confirmButtonText: "Clear", cancelButtonText: "Cancel",
                        buttonsStyling: false,
                        customClass: { confirmButton: "btn btn-danger me-2", cancelButton: "btn btn-light" },
                    }).then(r => { if (r.isConfirmed) doClear(); });
                } else {
                    doClear();
                }
                return;
            }
        });
    }

    function initClickableHeader() {
        document.addEventListener("click", e => {
            const trigger = e.target.closest(".chat-header-clickable");
            if (!trigger) return;
            const toggle = trigger.closest(".card-header")?.querySelector(".dropdown-toggle");
            toggle?.click();
        });
    }

    // ------------------------------------------------------------------ Load history

    function clearRenderedMessages(container) {
        Array.from(container.children).forEach(child => {
            const el = child.dataset.ktElement;
            if (el !== "template-out" && el !== "template-in" && el !== "typing-bubble") child.remove();
        });
    }

    function loadMessages() {
        const containers = document.querySelectorAll("[data-kt-element='messages']");
        containers.forEach(c => {
            clearRenderedMessages(c);
            const s = document.createElement("div");
            s.className = "d-flex justify-content-center p-5"; s.dataset.chatSpinner = "1";
            s.innerHTML = '<div class="spinner-border spinner-border-sm text-muted"></div>';
            c.appendChild(s);
        });
        hideEmptyState();

        return api("GET", `/chat/messages/${currentConversationId}?page=1`).then(res => {
            containers.forEach(c => c.querySelectorAll("[data-chat-spinner]").forEach(el => el.remove()));
            if (!res.success) return;
            if (!res.messages?.length) {
                showEmptyState();
            } else {
                res.messages.forEach(msg => {
                    seenMessageIds.add(String(msg.id));
                    const id = parseInt(msg.id);
                    if (!isNaN(id) && id > lastKnownMessageId) lastKnownMessageId = id;
                    renderMessage(msg);
                });
                scrollToBottom();
            }
            doMarkRead(currentConversationId);
            if (!socketConnected) startPolling();
        });
    }

    function showEmptyState() {
        document.querySelectorAll("[data-kt-element='messages']").forEach(c => {
            if (c.querySelector("#kt_drawer_chat_empty")) return;
            const el = document.createElement("div");
            el.id = "kt_drawer_chat_empty"; el.className = "text-center py-10";
            el.innerHTML = `<i class="ki-duotone ki-message-text-2 fs-3x text-muted mb-3 d-block"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i><span class="text-muted fs-6">No messages yet. Say hello!</span>`;
            c.appendChild(el);
        });
    }

    function hideEmptyState() {
        document.querySelectorAll("#kt_drawer_chat_empty").forEach(el => el.remove());
    }

    // ------------------------------------------------------------------ Send text

    function sendText(messenger) {
        const input = messenger.querySelector("[data-kt-element='input']");
        const text  = input?.value?.trim() ?? "";
        if (!text || !currentConversationId) return;

        input.value = "";
        autoResizeTextarea(input);
        emitTyping(false);
        hideEmptyState();

        const optimistic = {
            id: "pending-" + Date.now(),
            conversation_id: currentConversationId,
            sender_id: currentUserId,
            message_type: "text",
            content: text,
            created_at: new Date().toISOString(),
            fname: "", lname: "", profile_photo: null, files: [],
        };
        renderMessage(optimistic);
        scrollToBottom();

        const form = new FormData();
        form.append("conversation_id", currentConversationId);
        form.append("content", text);

        api("POST", "/chat/messages", form, true).then(res => {
            if (!res.success) { document.querySelector(`[data-message-id="${optimistic.id}"]`)?.remove(); return; }
            const pending = document.querySelector(`[data-message-id="${optimistic.id}"]`);
            if (pending) {
                pending.dataset.messageId = res.message.id;
                pending.dataset.senderId  = res.message.sender_id;
                const timeEl = pending.querySelector("[data-kt-element='message-time']");
                if (timeEl) timeEl.textContent = formatTime(res.message.created_at);
            }
            seenMessageIds.add(String(res.message.id));
            const id = parseInt(res.message.id);
            if (!isNaN(id) && id > lastKnownMessageId) lastKnownMessageId = id;

            if (socket && socketConnected) {
                socket.emit("new_message", { conversationId: currentConversationId, message: res.message, receiverUserId: currentTargetUserId });
            }
        });
    }

    // ------------------------------------------------------------------ Send photos

    function sendPhotos(files, messenger) {
        if (!files?.length || !currentConversationId) return;
        const imageFiles = Array.from(files).filter(f => f.type.startsWith("image/")).slice(0, 10);
        if (!imageFiles.length) return;
        setUploadProgress(messenger, true);
        hideEmptyState();

        const form = new FormData();
        form.append("conversation_id", currentConversationId);
        imageFiles.forEach(f => form.append("files[]", f));

        api("POST", "/chat/upload", form, true).then(res => {
            setUploadProgress(messenger, false);
            if (!res.success) { if (typeof Swal !== "undefined") Swal.fire({ icon: "warning", title: "Upload failed", text: res.message, buttonsStyling: false, confirmButtonText: "OK", customClass: { confirmButton: "btn btn-warning" } }); return; }
            seenMessageIds.add(String(res.message.id));
            const id = parseInt(res.message.id);
            if (!isNaN(id) && id > lastKnownMessageId) lastKnownMessageId = id;
            renderMessage(res.message);
            scrollToBottom();
            if (socket && socketConnected) socket.emit("new_message", { conversationId: currentConversationId, message: res.message, receiverUserId: currentTargetUserId });
        });
    }

    // ------------------------------------------------------------------ Send single file

    function sendFile(file, messenger) {
        if (!file || !currentConversationId) return;
        setUploadProgress(messenger, true);
        hideEmptyState();

        const form = new FormData();
        form.append("conversation_id", currentConversationId);
        form.append("file", file);

        api("POST", "/chat/upload", form, true).then(res => {
            setUploadProgress(messenger, false);
            if (!res.success) { if (typeof Swal !== "undefined") Swal.fire({ icon: "warning", title: "Upload failed", text: res.message || "Max 10 MB.", buttonsStyling: false, confirmButtonText: "OK", customClass: { confirmButton: "btn btn-warning" } }); return; }
            seenMessageIds.add(String(res.message.id));
            const id = parseInt(res.message.id);
            if (!isNaN(id) && id > lastKnownMessageId) lastKnownMessageId = id;
            renderMessage(res.message);
            scrollToBottom();
            if (socket && socketConnected) socket.emit("new_message", { conversationId: currentConversationId, message: res.message, receiverUserId: currentTargetUserId });
        });
    }

    function setUploadProgress(messenger, show) {
        messenger.querySelector("[data-kt-element='upload-progress']")?.classList.toggle("d-none", !show);
        ["send", "attach", "photo-attach"].forEach(el => {
            const btn = messenger.querySelector(`[data-kt-element='${el}']`);
            if (btn) btn.disabled = show;
        });
    }

    // ------------------------------------------------------------------ Render message

    function renderMessage(msg) {
        if (msg.message_type === "call") { renderCallMessage(msg); return; }
        const isMine     = String(msg.sender_id) === String(currentUserId);
        const isDeleted  = msg.message_type === "deleted";
        const containers = document.querySelectorAll("[data-kt-element='messages']");

        containers.forEach(container => {
            const tplSel   = isMine ? "[data-kt-element='template-out']" : "[data-kt-element='template-in']";
            const template = container.querySelector(tplSel);
            if (!template) return;

            const clone = template.cloneNode(true);
            clone.classList.remove("d-none");
            clone.removeAttribute("data-kt-element");
            clone.dataset.messageId   = msg.id;
            clone.dataset.senderId    = msg.sender_id;
            clone.dataset.messageType = msg.message_type;

            const textEl = clone.querySelector("[data-kt-element='message-text']");
            if (textEl) {
                if (isDeleted) {
                    textEl.style.background = "transparent";
                    textEl.style.padding    = "4px 0";
                    textEl.innerHTML        = `<span class="chat-deleted-msg"><i class="ki-duotone ki-information-2 fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>This message was removed</span>`;
                } else if (msg.message_type === "text") {
                    textEl.textContent = msg.content;
                } else if (msg.message_type === "image" && msg.files?.length) {
                    textEl.style.padding = "0";
                    textEl.style.background = "transparent";
                    textEl.innerHTML = buildImageHtml(msg.files, msg.id);
                } else if (msg.message_type === "file" && msg.files?.length) {
                    messageFilesCache[msg.id] = msg.files.map(f => ({
                        url:  `${BASE_URL}/${f.file_path}`,
                        name: f.original_name || "file",
                        type: f.file_type,
                        size: f.file_size,
                    }));
                    textEl.innerHTML = buildFileHtml(msg.files);
                }
            }

            const timeEl = clone.querySelector("[data-kt-element='message-time']");
            if (timeEl) timeEl.textContent = formatTime(msg.created_at);

            if (!isMine) {
                const senderName = `${msg.fname ?? ""} ${msg.lname ?? ""}`.trim();
                const imgEl  = clone.querySelector("img");
                const nameEl = clone.querySelector("[data-kt-element='message-sender-name']");
                if (imgEl)  { imgEl.src = msg.profile_photo ? `${BASE_URL}/uploads/profilePhoto/${msg.profile_photo}` : `${BASE_URL}/app/assets/media/avatars/blank.png`; imgEl.alt = senderName; }
                if (nameEl) nameEl.textContent = senderName;
            }

            // Place before the typing bubble (if it exists at the bottom)
            const typingBubble = container.querySelector("[data-kt-element='typing-bubble']");
            if (typingBubble) container.insertBefore(clone, typingBubble);
            else container.appendChild(clone);
        });

        if (!isDeleted && msg.reactions?.length) renderReactions(msg.id, msg.reactions);
    }

    // ------------------------------------------------------------------ Call message rendering

    function formatDuration(seconds) {
        const m = Math.floor(seconds / 60), s = seconds % 60;
        if (m === 0) return `${s} sec`;
        if (s === 0) return `${m} min`;
        return `${m} min ${s} sec`;
    }

    function renderCallMessage(msg) {
        let meta = {};
        try { meta = JSON.parse(msg.content || "{}"); } catch {}
        const callType = meta.call_type === "video" ? "video" : "voice";
        const isVideo  = callType === "video";
        const status   = meta.status || "ended";
        const isMissed = status === "missed";
        const isBad    = isMissed || status === "declined";
        const isMine   = String(msg.sender_id) === String(currentUserId);

        const iconCls    = isVideo ? "fa-video" : "fa-phone";
        const iconBg     = isBad   ? "#fff0f3" : "#eef6ff";
        const iconColor  = isBad   ? "#f1416c" : "#1a56db";
        const iconBorder = isBad   ? "#fcd3db" : "#bfdbfe";

        const callLabel = isMissed
            ? (isVideo ? "Missed video call" : "Missed audio call")
            : (isVideo ? "Video Call"        : "Audio call");

        let sublabel;
        if (status === "ended") {
            sublabel = formatDuration(meta.duration || 0);
        } else if (isMissed) {
            sublabel = formatTime(msg.created_at);
        } else if (status === "declined") {
            sublabel = "Declined";
        } else if (status === "cancelled") {
            sublabel = "Cancelled";
        } else {
            sublabel = formatTime(msg.created_at);
        }

        const showBtn  = status === "ended" || isMissed;
        const btnLabel = isVideo ? "Call again" : "Call Back";
        const btnHtml  = showBtn
            ? `<button type="button" class="btn btn-sm btn-light-primary py-1 px-3 fs-9 fw-semibold mt-2" data-navuli-call-back="${callType}">${escHtml(btnLabel)}</button>`
            : "";

        // Missed-call badge: small red ✕ overlaid on top-right of icon
        const badgeHtml = isMissed
            ? `<span style="position:absolute;top:-5px;right:-5px;width:17px;height:17px;background:#f1416c;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:700;color:#fff;line-height:1;">✕</span>`
            : "";

        const callCardHtml = `
            <div class="d-flex align-items-center gap-3 px-4 py-3 rounded-3" style="background:#f8faff;border:1px solid #dce8ff;max-width:280px;">
                <div style="position:relative;width:46px;height:46px;border-radius:10px;background:${iconBg};border:1.5px solid ${iconBorder};flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid ${iconCls} fs-4" style="color:${iconColor};"></i>
                    ${badgeHtml}
                </div>
                <div>
                    <div class="fw-semibold text-gray-800 fs-7">${escHtml(callLabel)}</div>
                    <div class="text-muted fs-9">${escHtml(sublabel)}</div>
                    ${btnHtml}
                </div>
            </div>`;

        const avatarUrl = isMine
            ? (window.NAVULI_MY_PHOTO || `${BASE_URL}/app/assets/media/avatars/blank.png`)
            : (msg.profile_photo ? `${BASE_URL}/uploads/profilePhoto/${msg.profile_photo}` : (currentContactPhotoUrl || `${BASE_URL}/app/assets/media/avatars/blank.png`));
        const senderName = `${msg.fname ?? ""} ${msg.lname ?? ""}`.trim();

        const actionBtnHtml = `<button class="chat-msg-action" type="button" title="More options"><span class="cdm-dots">⋯</span></button>`;
        const avatarHtml    = `<div class="symbol symbol-30px symbol-circle"><img alt="${escHtml(senderName)}" src="${avatarUrl}" style="width:30px;height:30px;object-fit:cover;border-radius:50%;"></div>`;
        const nameTimeHtml  = isMine
            ? `<span class="text-muted fs-8" data-kt-element="message-time">${escHtml(formatTime(msg.created_at))}</span>${avatarHtml}`
            : `${avatarHtml}<span class="fw-semibold fs-8 text-gray-700">${escHtml(senderName)}</span><span class="text-muted fs-8" data-kt-element="message-time">${escHtml(formatTime(msg.created_at))}</span>`;

        document.querySelectorAll("[data-kt-element='messages']").forEach(container => {
            const wrapper = document.createElement("div");
            wrapper.className           = `d-flex ${isMine ? "justify-content-end" : "justify-content-start"} mb-6 chat-msg-row`;
            wrapper.dataset.messageId   = msg.id;
            wrapper.dataset.senderId    = msg.sender_id;
            wrapper.dataset.messageType = "call";
            wrapper.innerHTML = `
                <div class="d-flex flex-column ${isMine ? "align-items-end" : "align-items-start"}">
                    <div class="d-flex align-items-center mb-1 gap-2">${nameTimeHtml}</div>
                    <div class="d-flex align-items-center gap-1">
                        ${isMine ? actionBtnHtml : ""}
                        <div data-kt-element="message-text">${callCardHtml}</div>
                        ${isMine ? "" : actionBtnHtml}
                    </div>
                </div>`;
            const typingBubble = container.querySelector("[data-kt-element='typing-bubble']");
            if (typingBubble) container.insertBefore(wrapper, typingBubble);
            else container.appendChild(wrapper);
        });
    }

    // ------------------------------------------------------------------ Photo grid

    function buildImageHtml(files, messageId) {
        messageFilesCache[messageId] = files.map(f => ({ url: `${BASE_URL}/${f.file_path}`, name: f.original_name || "Photo" }));
        const total = files.length, show = Math.min(total, 4), extra = total - show;
        const mid   = String(messageId).replace(/"/g, "");
        let html    = `<div class="chat-photo-grid grid-${show}">`;
        for (let i = 0; i < show; i++) {
            const url = `${BASE_URL}/${files[i].file_path}`, name = escHtml(files[i].original_name || "Photo");
            html += `<div class="chat-photo-cell" data-lb-msg="${mid}" data-lb-idx="${i}" role="button" tabindex="0" aria-label="View photo ${i+1}"><img src="${url}" alt="${name}" loading="lazy">${i === show-1 && extra > 0 ? `<div class="chat-photo-more">+${extra}</div>` : ""}</div>`;
        }
        return html + `</div>`;
    }

    function buildFileHtml(files) {
        return files.map(f => {
            const url = `${BASE_URL}/${f.file_path}`, icon = fileIcon(f.file_type), size = formatSize(f.file_size);
            return `<a href="${url}" target="_blank" download class="d-flex align-items-center gap-2 p-3 bg-light rounded text-dark text-decoration-none"><i class="ki-duotone ${icon} fs-2x"><span class="path1"></span><span class="path2"></span></i><div class="overflow-hidden"><div class="fw-semibold text-truncate mw-200px">${escHtml(f.original_name)}</div><div class="text-muted fs-7">${size}</div></div></a>`;
        }).join("");
    }

    function fileIcon(mime) {
        if (!mime) return "ki-file";
        if (mime.includes("pdf"))  return "ki-file-pdf";
        if (mime.includes("word")) return "ki-file-doc";
        if (mime.includes("excel") || mime.includes("spreadsheet")) return "ki-file-spreadsheet";
        if (mime.includes("zip"))  return "ki-archive";
        return "ki-file";
    }

    function formatSize(b) {
        if (!b) return "0 B";
        if (b < 1024) return b + " B";
        if (b < 1048576) return (b/1024).toFixed(1) + " KB";
        return (b/1048576).toFixed(1) + " MB";
    }

    function formatTime(dt) {
        if (!dt) return "Just now";
        const d = new Date(dt), now = new Date();
        return d.toDateString() === now.toDateString()
            ? d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
            : d.toLocaleDateString([], { month: "short", day: "numeric" }) + " " + d.toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" });
    }

    function escHtml(str) { const d = document.createElement("div"); d.textContent = str ?? ""; return d.innerHTML; }

    // ------------------------------------------------------------------ Reactions

    function renderReactions(messageId, reactions) {
        document.querySelectorAll(`[data-message-id="${messageId}"]`).forEach(msgEl => {
            const row = msgEl.querySelector("[data-kt-element='reaction-row']");
            if (!row) return;
            if (!reactions || !reactions.length) { row.innerHTML = ""; return; }
            row.innerHTML = reactions.map(r => `
                <span class="chat-reaction-pill${r.mine ? " mine" : ""}" data-emoji="${escHtml(r.emoji)}">
                    <span>${r.emoji}</span><span class="crp-count">${r.count}</span>
                </span>`).join("");
        });
    }

    function doReact(messageId, emoji) {
        const form = new FormData();
        form.append("emoji", emoji);
        api("POST", `/chat/message/${messageId}/react`, form, true).then(res => {
            if (!res.success) return;
            renderReactions(res.messageId, res.reactions);
            if (socket && socketConnected) {
                socket.emit("message_reacted", {
                    conversationId: res.conversationId,
                    messageId:      res.messageId,
                    reactions:      res.reactions,
                });
            }
        });
    }

    function positionPopupNear(target, popupEl) {
        const rect = (target instanceof Element) ? target.getBoundingClientRect() : target;
        const w = popupEl.offsetWidth  || 280;
        const h = popupEl.offsetHeight || 200;
        let top  = rect.bottom + 6;
        let left = rect.left;
        if (top  + h > window.innerHeight - 8) top  = rect.top - h - 6;
        if (left < 8)                           left = 8;
        if (left + w > window.innerWidth  - 8) left = window.innerWidth  - w - 8;
        popupEl.style.top  = top  + "px";
        popupEl.style.left = left + "px";
    }

    let reactionBarTargetId = null;

    function initReactions() {
        const bar = document.getElementById("chat_reaction_bar");
        if (!bar) return;

        document.addEventListener("click", e => {
            const trigger = e.target.closest(".chat-reaction-trigger");
            if (trigger) {
                e.stopPropagation();
                const msgEl = trigger.closest("[data-message-id]");
                if (!msgEl) return;
                reactionBarTargetId = msgEl.dataset.messageId;
                closeEmojiPopup();
                bar.classList.remove("d-none");
                positionPopupNear(trigger, bar);
                return;
            }

            const pill = e.target.closest(".chat-reaction-pill");
            if (pill) {
                e.stopPropagation();
                const msgEl = pill.closest("[data-message-id]");
                if (msgEl) doReact(msgEl.dataset.messageId, pill.dataset.emoji);
                return;
            }

            if (!bar.classList.contains("d-none") &&
                !bar.contains(e.target) &&
                !e.target.closest(".chat-reaction-trigger")) {
                bar.classList.add("d-none");
            }
        });

        bar.querySelectorAll(".crb-emoji[data-emoji]").forEach(btn => {
            btn.addEventListener("click", () => {
                if (reactionBarTargetId) doReact(reactionBarTargetId, btn.dataset.emoji);
                bar.classList.add("d-none");
            });
        });

        document.getElementById("chat_reaction_more")?.addEventListener("click", () => {
            const rect = bar.getBoundingClientRect();
            bar.classList.add("d-none");
            if (reactionBarTargetId) openEmojiPopup({ mode: "react", messageId: reactionBarTargetId, anchorEl: rect });
        });

        document.addEventListener("keydown", e => { if (e.key === "Escape") bar.classList.add("d-none"); });
    }

    // ------------------------------------------------------------------ Emoji palette popup

    const EMOJI_PALETTE = {
        "Smileys": ["😀","😁","😂","🤣","😃","😄","😅","😆","😉","😊","😋","😎","😍","🥰","😘","🙂","🙃","😇","😐","😑","😶","🙄","😏","😣","😥","😮","🤐","😯","😪","😫","🥱","😴","😌","😜","😝","🤤","😒","😓","😔","😕"],
        "Gestures": ["👍","👎","👌","✌️","🤞","🤟","🤘","👏","🙌","👐","🤲","🙏","💪","👋","🤝","✋","🖐️","👆","👇","👉","👈"],
        "Hearts":   ["❤️","🧡","💛","💚","💙","💜","🖤","🤍","🤎","💔","❣️","💕","💞","💓","💗","💖","💘","💝"],
        "Animals & Nature": ["🐶","🐱","🐭","🐹","🐰","🦊","🐻","🐼","🐨","🐯","🦁","🐮","🐷","🐸","🐵","🌸","🌹","🌻","🌞","🌈"],
        "Objects & Symbols": ["🎉","🎊","🎁","🏆","⭐","🔥","💯","✅","❌","⚠️","💡","📌","📎","🔔","🎵","☕","🍕","🍔","🎂","⚽"],
    };

    let emojiPopupMode      = null; // 'react' | 'compose'
    let emojiPopupMessageId = null;
    let emojiPopupInputEl   = null;

    function buildEmojiPopupHtml() {
        let html = "";
        for (const [cat, list] of Object.entries(EMOJI_PALETTE)) {
            html += `<div class="cep-cat-label">${escHtml(cat)}</div><div class="cep-grid">`;
            html += list.map(e => `<span class="cep-emoji" data-emoji="${e}">${e}</span>`).join("");
            html += `</div>`;
        }
        return html;
    }

    function initEmojiPopup() {
        const popup = document.getElementById("chat_emoji_popup");
        if (!popup) return;
        popup.innerHTML = buildEmojiPopupHtml();

        popup.addEventListener("click", e => {
            const item = e.target.closest(".cep-emoji");
            if (!item) return;
            const emoji = item.dataset.emoji;
            if (emojiPopupMode === "react" && emojiPopupMessageId) {
                doReact(emojiPopupMessageId, emoji);
            } else if (emojiPopupMode === "compose" && emojiPopupInputEl) {
                insertAtCursor(emojiPopupInputEl, emoji);
            }
            closeEmojiPopup();
        });

        document.addEventListener("click", e => {
            if (!popup.classList.contains("d-none") &&
                !popup.contains(e.target) &&
                !e.target.closest(".chat-reaction-trigger") &&
                !e.target.closest("#chat_reaction_more") &&
                !e.target.closest("[data-kt-element='emoji']")) {
                closeEmojiPopup();
            }
        });

        document.addEventListener("keydown", e => { if (e.key === "Escape") closeEmojiPopup(); });
    }

    function openEmojiPopup({ mode, messageId, inputEl, anchorEl }) {
        const popup = document.getElementById("chat_emoji_popup");
        if (!popup) return;
        emojiPopupMode      = mode;
        emojiPopupMessageId = messageId || null;
        emojiPopupInputEl   = inputEl || null;
        popup.classList.remove("d-none");
        positionPopupNear(anchorEl, popup);
    }

    function closeEmojiPopup() {
        document.getElementById("chat_emoji_popup")?.classList.add("d-none");
        emojiPopupMode = null; emojiPopupMessageId = null; emojiPopupInputEl = null;
    }

    function insertAtCursor(input, text) {
        const start = input.selectionStart ?? input.value.length;
        const end   = input.selectionEnd   ?? input.value.length;
        input.value = input.value.slice(0, start) + text + input.value.slice(end);
        const pos = start + text.length;
        input.focus();
        input.setSelectionRange(pos, pos);
        autoResizeTextarea(input);
    }

    // ------------------------------------------------------------------ Textarea auto-resize

    function autoResizeTextarea(el) {
        if (!el) return;
        el.style.height = "auto";
        el.style.height = Math.min(el.scrollHeight, 120) + "px";
    }

    // ------------------------------------------------------------------ Typing emit

    function emitTyping(typing) {
        if (!socket || !socketConnected || !currentConversationId || typing === isTypingActive) return;
        isTypingActive = typing;
        socket.emit("typing", { conversationId: currentConversationId, isTyping: typing });
    }

    function onInputChange(e) {
        autoResizeTextarea(e.target);
        emitTyping(true);
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => emitTyping(false), 2000);
    }

    // ------------------------------------------------------------------ Read / unread

    function doMarkRead(conversationId) {
        api("POST", `/chat/read/${conversationId}`).then(() => fetchUnreadCount());
        if (socket && socketConnected) socket.emit("messages_read", { conversationId });
        if (currentTargetUserId) {
            document.dispatchEvent(new CustomEvent("navuli:unreadBadge", {
                detail: { userId: String(currentTargetUserId), count: 0 }
            }));
        }
    }

    function fetchUnreadCount() {
        api("GET", "/chat/unread-count").then(res => { if (res.success) { totalUnreadCount = res.count; updateNavBadge(); } });
    }

    function updateNavBadge() {
        const badge = document.getElementById("navuli_chat_badge");
        if (badge) {
            if (totalUnreadCount > 0) { badge.textContent = totalUnreadCount >= 10 ? "9+" : totalUnreadCount; badge.classList.remove("d-none"); }
            else { badge.textContent = ""; badge.classList.add("d-none"); }
        }
        document.dispatchEvent(new CustomEvent("navuli:messagesCount", { detail: { count: totalUnreadCount } }));
    }

    // ------------------------------------------------------------------ Scroll

    function scrollToBottom() {
        requestAnimationFrame(() => requestAnimationFrame(() => {
            document.querySelectorAll("[data-kt-element='messages']").forEach(el => {
                if (typeof KTScroll !== "undefined") { const i = KTScroll.getInstance(el); if (i) i.update(); }
                el.scrollTop = el.scrollHeight;
            });
        }));
    }

    // ------------------------------------------------------------------ Init messenger

    function initMessenger(el) {
        if (!el) return;
        const input = el.querySelector("[data-kt-element='input']");
        if (input) {
            input.addEventListener("input", onInputChange);
            input.addEventListener("keydown", e => { if (e.key === "Enter" && !e.shiftKey) { e.preventDefault(); sendText(el); } });
            input.setAttribute("placeholder", "Type a message… (Enter to send)");
        }
        el.querySelector("[data-kt-element='send']")?.addEventListener("click", () => sendText(el));
        el.querySelector("[data-kt-element='attach']")?.addEventListener("click", () => el.querySelector("[data-kt-element='file-input']")?.click());
        el.querySelector("[data-kt-element='file-input']")?.addEventListener("change", e => { const f = e.target.files?.[0]; if (f) { sendFile(f, el); e.target.value = ""; } });
        el.querySelector("[data-kt-element='photo-attach']")?.addEventListener("click", () => el.querySelector("[data-kt-element='photo-input']")?.click());
        el.querySelector("[data-kt-element='photo-input']")?.addEventListener("change", e => { if (e.target.files?.length) { sendPhotos(e.target.files, el); e.target.value = ""; } });
        el.querySelector("[data-kt-element='emoji']")?.addEventListener("click", e => {
            openEmojiPopup({ mode: "compose", inputEl: input, anchorEl: e.currentTarget });
        });
    }

    // ------------------------------------------------------------------ Call audio (Web Audio API tones)

    const CallAudio = (() => {
        let actx = null;
        let ringTimer = null;

        function getCtx() {
            if (!actx || actx.state === "closed") {
                actx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (actx.state === "suspended") actx.resume();
            return actx;
        }

        function tone(freq, dur, vol, delay, type) {
            try {
                const ac  = getCtx();
                const osc = ac.createOscillator();
                const g   = ac.createGain();
                osc.connect(g); g.connect(ac.destination);
                osc.type = type || "sine";
                osc.frequency.value = freq;
                g.gain.setValueAtTime(0, ac.currentTime + delay);
                g.gain.linearRampToValueAtTime(vol, ac.currentTime + delay + 0.02);
                g.gain.exponentialRampToValueAtTime(0.0001, ac.currentTime + delay + dur);
                osc.start(ac.currentTime + delay);
                osc.stop(ac.currentTime + delay + dur + 0.05);
            } catch {}
        }

        function ringOnce() {
            // "ring ring" — two short dual-tone bursts (US telephone style)
            tone(440, 0.45, 0.18, 0.0);  tone(480, 0.45, 0.18, 0.0);
            tone(440, 0.45, 0.18, 0.6);  tone(480, 0.45, 0.18, 0.6);
        }

        function startRinging() {
            stopRinging();
            ringOnce();
            ringTimer = setInterval(ringOnce, 3500);
        }

        function stopRinging() {
            if (ringTimer) { clearInterval(ringTimer); ringTimer = null; }
        }

        // Pre-warm: create and resume AudioContext within a user-gesture call stack
        // so it is in "running" state before we need it for ringing.
        function warmUp() {
            try { getCtx(); } catch {}
        }

        function playConnected() {
            stopRinging();
            // Short ascending chime: call picked up
            tone(523, 0.15, 0.18, 0.00);
            tone(659, 0.15, 0.18, 0.13);
            tone(784, 0.25, 0.18, 0.26);
        }

        function playEnded() {
            stopRinging();
            // Soft descending notes: call over
            tone(523, 0.20, 0.18, 0.00);
            tone(440, 0.20, 0.18, 0.18);
            tone(349, 0.35, 0.18, 0.36);
        }

        function playDeclined() {
            stopRinging();
            // Two low descending beeps: rejected
            tone(330, 0.35, 0.22, 0.00);
            tone(262, 0.50, 0.22, 0.42);
        }

        function playBusy() {
            stopRinging();
            // Single soft tone: cancelled / no answer
            tone(440, 0.30, 0.18, 0.00);
        }

        // Pre-warm: create and resume AudioContext within a user-gesture call stack
        // so it is in "running" state before we need it for ringing.
        function warmUp() {
            try { getCtx(); } catch {}
        }

        return { startRinging, stopRinging, playConnected, playEnded, playDeclined, playBusy, warmUp };
    })();

    // ------------------------------------------------------------------ Call module

    function buildCallAvatar(photo, name, size) {
        const s = size + "px", fs = Math.round(size / 2.5) + "px";
        return photo
            ? `<img src="${escHtml(photo)}" style="width:${s};height:${s};object-fit:cover;" alt="">`
            : `<div class="d-flex align-items-center justify-content-center bg-light-primary fw-bold text-primary" style="width:${s};height:${s};font-size:${fs};">${escHtml((name || "?").charAt(0).toUpperCase())}</div>`;
    }

    function showCallSwal(icon, title, text) {
        if (typeof Swal === "undefined") return;
        Swal.mixin({ toast: true, position: "top", showConfirmButton: false, timer: 4000, timerProgressBar: true })
            .fire({ icon, title, text });
    }

    // Waits up to `ms` for the socket to be connected; triggers reconnect if needed.
    function waitForSocket(ms) {
        return new Promise(resolve => {
            if (socket?.connected) { resolve(true); return; }
            if (!socket) { resolve(false); return; }
            if (!socket.connected) socket.connect();
            const deadline = Date.now() + ms;
            const iv = setInterval(() => {
                if (socket?.connected) { clearInterval(iv); resolve(true); }
                else if (Date.now() >= deadline) { clearInterval(iv); resolve(false); }
            }, 150);
        });
    }

    // check_online with a hard timeout so it never hangs silently.
    function checkOnlineAck(userIds, ms) {
        return new Promise(resolve => {
            const timer = setTimeout(() => resolve({}), ms);
            socket.emit("check_online", userIds, result => {
                clearTimeout(timer);
                resolve(result || {});
            });
        });
    }

    async function initiateCall(callType) {
        if (callState.active) {
            Swal.fire({
                icon: "info",
                title: "Already in a call",
                html: `You are currently in a <strong>${callState.callType}</strong> call with <strong>${escHtml(callState.peerName || "someone")}</strong>.<br><br>Please end that call before starting a new one.`,
                confirmButtonText: "Got it",
                confirmButtonColor: "#1a56db",
                showClass: { popup: "animate__animated animate__fadeInDown animate__faster" },
                hideClass: { popup: "animate__animated animate__fadeOutUp animate__faster" },
            });
            return;
        }
        if (!currentTargetUserId || !currentConversationId) {
            showCallSwal("warning", "No conversation open", "Please open a chat conversation first.");
            return;
        }
        if (!navigator.mediaDevices?.getUserMedia || !window.RTCPeerConnection) {
            showCallSwal("error", "Not supported", "Your browser does not support calls. Make sure you are on http://localhost (not an IP address)."); return;
        }

        // 1. Set state and show card IMMEDIATELY
        callState.active         = true;
        setComposerDisabled(convBlocked);   // lock call buttons on all conversations
        callState.direction      = "outgoing";
        callState.callType       = callType;
        callState.peerId         = currentTargetUserId;
        callState.peerName       = currentContactName;
        callState.peerPhoto      = currentContactPhotoUrl;
        callState.conversationId = currentConversationId;
        showCallCardUI("Calling…", () => cancelCall("cancelled"));
        CallAudio.warmUp(); // pre-warm AudioContext while still in the user-gesture call stack

        // 2. Request mic / camera
        const isVideo = callType === "video";
        const constraints = isVideo
            ? { audio: true, video: { facingMode: "user", width: { ideal: 1280 }, height: { ideal: 720 } } }
            : { audio: true, video: false };
        let stream;
        try {
            stream = await navigator.mediaDevices.getUserMedia(constraints);
        } catch (e) {
            console.error("[NavuliChat] getUserMedia error:", e.name, e.message);
            if (isVideo) {
                // Fallback: camera denied — try audio-only
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: false });
                    callState.callType = "voice";
                } catch (e2) {
                    console.error("[NavuliChat] audio fallback error:", e2.name, e2.message);
                    showCallSwal("error", "Microphone access denied", "Please allow microphone access in your browser settings.");
                    resetCallState(); return;
                }
            } else {
                showCallSwal("error", "Microphone access denied", "Please allow microphone access in your browser settings.");
                resetCallState(); return;
            }
        }
        if (!callState.active) { stream.getTracks().forEach(t => t.stop()); return; }

        callState.localStream = stream;
        const la = document.getElementById("navuli_call_local_audio");
        if (la) la.srcObject = stream;
        if (callState.callType === "video") {
            const lv = document.getElementById("navuli_local_video");
            if (lv) lv.srcObject = stream;
        }

        // 3-4. Set up PeerConnection and send offer — all inside try so any failure is caught
        try {
            const pc = new RTCPeerConnection({ iceServers: ICE_SERVERS });
            callState.pc = pc;
            stream.getTracks().forEach(t => pc.addTrack(t, stream));

            pc.ontrack = e => {
                if (callState.callType === "video") {
                    const rv = document.getElementById("navuli_remote_video");
                    if (rv) rv.srcObject = e.streams[0];
                } else {
                    const ra = document.getElementById("navuli_call_remote_audio");
                    if (ra) ra.srcObject = e.streams[0];
                }
            };
            pc.onicecandidate = e => {
                if (e.candidate) socket?.emit("ice_candidate", { targetUserId: callState.peerId, candidate: e.candidate });
            };
            pc.onconnectionstatechange = () => {
                console.log("[NavuliChat] Caller PC state:", pc.connectionState);
                if (pc.connectionState === "connected") startCallTimer();
                else if (["disconnected", "failed", "closed"].includes(pc.connectionState)) endCall("ended", true);
            };

            const offer = await pc.createOffer();
            await pc.setLocalDescription(offer);
            if (!callState.active) return;

            setCallCardStatus(callState.callType === "video" ? "Video calling…" : "Ringing…");
            CallAudio.startRinging();

            console.log("[NavuliChat] Sending call_request to user", currentTargetUserId, "type:", callState.callType, "socket connected:", socket?.connected);
            socket?.emit("call_request", {
                targetUserId:   currentTargetUserId,
                conversationId: currentConversationId,
                callType:       callState.callType,
                offer,
                callerName:     window.NAVULI_MY_NAME  || "",
                callerPhoto:    window.NAVULI_MY_PHOTO || "",
            });

            callState.callTimeout = setTimeout(() => {
                if (callState.active && callState.direction === "outgoing" && !callState.startTime) {
                    cancelCall("missed");
                }
            }, 30000);
        } catch (e) {
            console.error("[NavuliChat] call setup error:", e);
            showCallSwal("error", "Call failed", "Could not initiate the call. Please try again.");
            resetCallState();
        }
    }

    // Generic connecting card — shown immediately on click before any async work.
    function showCallCardUI(statusText, onEnd) {
        const overlay = document.getElementById("navuli_call_overlay");
        if (!overlay) return;
        document.getElementById("navuli_call_avatar").innerHTML   = buildCallAvatar(callState.peerPhoto, callState.peerName, 88);
        document.getElementById("navuli_call_name").textContent   = callState.peerName;
        document.getElementById("navuli_call_status").textContent = statusText;
        document.getElementById("navuli_call_timer").classList.add("d-none");
        document.getElementById("navuli_call_controls").innerHTML =
            `<button type="button" class="btn btn-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:56px;height:56px;" id="navuli_end_early_btn" title="End">
                <i class="fa-solid fa-phone-slash fs-4"></i>
            </button>`;
        document.getElementById("navuli_end_early_btn").addEventListener("click", onEnd);
        overlay.style.display = "flex";
    }

    function setCallCardStatus(text) {
        const el = document.getElementById("navuli_call_status");
        if (el) el.textContent = text;
    }

    function showActiveCallUI() {
        CallAudio.playConnected();
        if (callState.callType === "video") showVideoCallUI(); else showVoiceCallUI();
        startCallTimer();
    }

    function showVoiceCallUI() {
        const overlay = document.getElementById("navuli_call_overlay");
        if (!overlay) return;
        document.getElementById("navuli_call_avatar").innerHTML   = buildCallAvatar(callState.peerPhoto, callState.peerName, 88);
        document.getElementById("navuli_call_name").textContent   = callState.peerName;
        document.getElementById("navuli_call_status").textContent = "Connected";
        document.getElementById("navuli_call_timer").classList.remove("d-none");
        document.getElementById("navuli_call_controls").innerHTML =
            `<button type="button" class="btn btn-light-warning rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:56px;height:56px;" id="navuli_mute_btn" title="Mute">
                <i class="fa-solid fa-microphone fs-4"></i>
            </button>
            <button type="button" class="btn btn-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:56px;height:56px;" id="navuli_end_call_btn" title="End call">
                <i class="fa-solid fa-phone-slash fs-4"></i>
            </button>`;
        document.getElementById("navuli_mute_btn").addEventListener("click", toggleMute);
        document.getElementById("navuli_end_call_btn").addEventListener("click", () => endCall("ended", true));
        overlay.style.display = "flex";
    }

    function showVideoCallUI() {
        const overlay   = document.getElementById("navuli_call_overlay");
        const videoView = document.getElementById("navuli_video_view");
        const card      = document.getElementById("navuli_call_card");
        if (!overlay || !videoView) return;
        document.getElementById("navuli_video_name").textContent = callState.peerName;
        document.getElementById("navuli_video_status").textContent = "Connected";
        document.getElementById("navuli_video_timer").classList.remove("d-none");
        document.getElementById("navuli_video_controls").innerHTML =
            `<button type="button" class="btn btn-dark rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:56px;height:56px;opacity:.9;" id="navuli_mute_btn" title="Mute/Unmute">
                <i class="fa-solid fa-microphone text-white fs-4"></i>
            </button>
            <button type="button" class="btn btn-dark rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:56px;height:56px;opacity:.9;" id="navuli_camera_btn" title="Camera on/off">
                <i class="fa-solid fa-video text-white fs-4"></i>
            </button>
            <button type="button" class="btn btn-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:68px;height:68px;" id="navuli_end_call_btn" title="End call">
                <i class="fa-solid fa-phone-slash text-white fs-3"></i>
            </button>`;
        document.getElementById("navuli_mute_btn").addEventListener("click", toggleMute);
        document.getElementById("navuli_camera_btn").addEventListener("click", toggleCamera);
        document.getElementById("navuli_end_call_btn").addEventListener("click", () => endCall("ended", true));
        if (card) card.style.display = "none";
        videoView.style.display = "block";
        overlay.style.display = "flex";
    }

    function toggleCamera() {
        const tracks = callState.localStream?.getVideoTracks() || [];
        if (!tracks.length) return;
        const wasEnabled = tracks[0].enabled;
        tracks.forEach(t => { t.enabled = !wasEnabled; });
        const btn = document.getElementById("navuli_camera_btn");
        const lv  = document.getElementById("navuli_local_video");
        if (!btn) return;
        if (wasEnabled) {
            btn.style.cssText = "width:56px;height:56px;background:#f1416c;border:none;";
            btn.querySelector("i").className = "fa-solid fa-video-slash text-white fs-4";
            if (lv) lv.style.opacity = "0.15";
        } else {
            btn.style.cssText = "width:56px;height:56px;opacity:.9;";
            btn.className = "btn btn-dark rounded-circle p-0 d-flex align-items-center justify-content-center";
            btn.querySelector("i").className = "fa-solid fa-video text-white fs-4";
            if (lv) lv.style.opacity = "1";
        }
    }

    function cancelCall(status) {
        if (!callState.active || callState.direction !== "outgoing") return;
        clearTimeout(callState.callTimeout);
        const { peerId, conversationId, callType } = callState;
        socket?.volatile.emit("call_cancel", { targetUserId: peerId });
        CallAudio.playBusy();
        saveCallEvent(conversationId, callType, status, 0, peerId);
        resetCallState();
    }

    function handleIncomingCall({ callerId, callerName, callerPhoto, callType, offer, conversationId }) {
        console.log("[NavuliChat] handleIncomingCall received — from:", callerId, callerName, "type:", callType, "convId:", conversationId);
        if (callState.active) { socket?.volatile.emit("call_decline", { callerId, reason: "busy" }); return; }
        callState.direction      = "incoming";
        callState.callType       = callType || "voice";
        callState.peerId         = callerId;
        callState.peerName       = callerName || "Unknown";
        callState.peerPhoto      = callerPhoto || null;
        callState.incomingOffer  = offer;
        callState.conversationId = conversationId || null;

        const incomingEl = document.getElementById("navuli_incoming_call");
        if (!incomingEl) return;
        const avatarEl = document.getElementById("navuli_incoming_avatar");
        if (avatarEl) avatarEl.innerHTML = buildCallAvatar(callerPhoto, callerName, 44);
        const nameEl = document.getElementById("navuli_incoming_name");
        if (nameEl) nameEl.textContent = callerName || "Unknown";
        const typeEl = document.getElementById("navuli_incoming_type");
        if (typeEl) typeEl.textContent = callType === "video" ? "Incoming video call…" : "Incoming voice call…";
        incomingEl.classList.remove("d-none");
        CallAudio.startRinging();

        callState.incomingTimeout = setTimeout(() => {
            if (!callState.active) { incomingEl.classList.add("d-none"); resetCallState(); }
        }, 30000);
    }

    async function acceptCall() {
        const { incomingOffer, peerId } = callState;
        document.getElementById("navuli_incoming_call")?.classList.add("d-none");
        clearTimeout(callState.incomingTimeout);
        CallAudio.stopRinging();

        // ── 1. Mark active and show card IMMEDIATELY ──────────────────────
        callState.active = true;
        setComposerDisabled(convBlocked);   // lock call buttons on all conversations
        showCallCardUI("Connecting…", () => {
            socket?.volatile.emit("call_decline", { callerId: peerId });
            resetCallState();
        });

        // ── 2. Background: request mic / camera ───────────────────────────
        const isVideo = callState.callType === "video";
        const constraints = isVideo
            ? { audio: true, video: { facingMode: "user", width: { ideal: 1280 }, height: { ideal: 720 } } }
            : { audio: true, video: false };
        let stream;
        try {
            stream = await navigator.mediaDevices.getUserMedia(constraints);
        } catch (e) {
            console.error("[NavuliChat] acceptCall getUserMedia error:", e.name, e.message);
            if (isVideo) {
                // Camera denied or unavailable — fall back to audio-only
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ audio: true, video: false });
                    callState.callType = "voice";
                } catch (e2) {
                    console.error("[NavuliChat] acceptCall audio fallback error:", e2.name, e2.message);
                    showCallSwal("error", "Microphone access denied", "Please allow microphone access in your browser settings.");
                    socket?.volatile.emit("call_decline", { callerId: peerId });
                    resetCallState(); return;
                }
            } else {
                showCallSwal("error", "Microphone access denied", "Please allow microphone access in your browser settings.");
                socket?.volatile.emit("call_decline", { callerId: peerId });
                resetCallState(); return;
            }
        }
        if (!callState.active) { stream.getTracks().forEach(t => t.stop()); return; }
        if (callState.callType === "video") {
            const lv = document.getElementById("navuli_local_video");
            if (lv) lv.srcObject = stream;
        }

        callState.localStream = stream;
        const la2 = document.getElementById("navuli_call_local_audio");
        if (la2) la2.srcObject = stream;

        // 3. WebRTC setup
        const pc = new RTCPeerConnection({ iceServers: ICE_SERVERS });
        callState.pc = pc;
        stream.getTracks().forEach(t => pc.addTrack(t, stream));
        pc.ontrack = e => {
            if (callState.callType === "video") {
                const rv = document.getElementById("navuli_remote_video");
                if (rv) rv.srcObject = e.streams[0];
            } else {
                const ra = document.getElementById("navuli_call_remote_audio");
                if (ra) ra.srcObject = e.streams[0];
            }
        };
        pc.onicecandidate = e => {
            if (e.candidate) socket?.emit("ice_candidate", { targetUserId: callState.peerId, candidate: e.candidate });
        };
        pc.onconnectionstatechange = () => {
            console.log("[NavuliChat] Callee PC state:", pc.connectionState);
            if (pc.connectionState === "connected") startCallTimer();
            else if (["disconnected", "failed", "closed"].includes(pc.connectionState)) endCall("ended", false);
        };

        // 4. SDP exchange
        try {
            await pc.setRemoteDescription(new RTCSessionDescription(incomingOffer));
            for (const c of callState.pendingCandidates) await pc.addIceCandidate(new RTCIceCandidate(c));
            callState.pendingCandidates = [];
            const answer = await pc.createAnswer();
            await pc.setLocalDescription(answer);
            if (!callState.active) return;
            console.log("[NavuliChat] Sending call_answer to user", peerId);
            socket?.emit("call_answer", { callerId: peerId, answer });
        } catch (e) {
            console.error("[NavuliChat] acceptCall SDP error:", e);
            showCallSwal("error", "Connection failed", "Could not establish the call. Please try again.");
            resetCallState(); return;
        }
        if (!callState.active) return;

        // ── 5. Fetch conversation id then show active call UI ─────────────
        const res = await api("GET", `/chat/conversation/${peerId}`);
        if (res.success) callState.conversationId = res.conversation_id;
        if (callState.active) showActiveCallUI();
    }

    function declineCall() {
        const peerId = callState.peerId;
        document.getElementById("navuli_incoming_call")?.classList.add("d-none");
        clearTimeout(callState.incomingTimeout);
        socket?.volatile.emit("call_decline", { callerId: peerId });
        CallAudio.stopRinging();
        resetCallState();
    }

    function endCall(status, emitEnd) {
        if (!callState.active) return;
        const duration   = callState.startTime ? Math.floor((Date.now() - callState.startTime) / 1000) : 0;
        const isOutgoing = callState.direction === "outgoing";
        const { peerId, conversationId, callType } = callState;
        if (emitEnd) socket?.volatile.emit("call_end", { targetUserId: peerId });
        if (callState.startTime) CallAudio.playEnded(); else CallAudio.playBusy();
        if (isOutgoing) saveCallEvent(conversationId, callType, status, duration, peerId);
        resetCallState();
    }

    function startCallTimer() {
        if (callState.startTime) return;
        callState.startTime = Date.now();
        callState.durationTimer = setInterval(() => {
            const elapsed = Math.floor((Date.now() - callState.startTime) / 1000);
            const m = Math.floor(elapsed / 60), s = elapsed % 60;
            const timeStr = `${m}:${s.toString().padStart(2, "0")}`;
            const t1 = document.getElementById("navuli_call_time");
            const t2 = document.getElementById("navuli_video_time");
            if (t1) t1.textContent = timeStr;
            if (t2) t2.textContent = timeStr;
        }, 1000);
    }

    function toggleMute() {
        callState.muted = !callState.muted;
        callState.localStream?.getAudioTracks().forEach(t => { t.enabled = !callState.muted; });
        const btn = document.getElementById("navuli_mute_btn");
        if (!btn) return;
        btn.className = `btn ${callState.muted ? "btn-warning" : "btn-light-warning"} rounded-circle p-0 d-flex align-items-center justify-content-center`;
        btn.style.cssText = "width:56px;height:56px;";
        btn.querySelector("i").className = `fa-solid ${callState.muted ? "fa-microphone-slash" : "fa-microphone"} fs-4`;
    }

    function saveCallEvent(conversationId, callType, status, duration, receiverUserId) {
        if (!conversationId) return;
        const form = new FormData();
        form.append("conversation_id", conversationId);
        form.append("call_type", callType || "voice");
        form.append("status", status);
        form.append("duration", duration || 0);
        api("POST", "/chat/call-event", form, true).then(res => {
            if (!res.success || !res.message) return;
            const key = String(res.message.id);
            if (seenMessageIds.has(key)) return;
            seenMessageIds.add(key);
            const id = parseInt(res.message.id);
            if (!isNaN(id) && id > lastKnownMessageId) lastKnownMessageId = id;
            if (String(res.message.conversation_id) === String(currentConversationId)) {
                renderMessage(res.message);
                scrollToBottom();
            }
            if (socket && socketConnected) {
                socket.emit("new_message", { conversationId, message: res.message, receiverUserId });
            }
        });
    }

    function resetCallState() {
        CallAudio.stopRinging();
        clearTimeout(callState.callTimeout);
        clearTimeout(callState.incomingTimeout);
        clearInterval(callState.durationTimer);
        callState.localStream?.getTracks().forEach(t => t.stop());
        callState.pc?.close();
        const la = document.getElementById("navuli_call_local_audio");
        const ra = document.getElementById("navuli_call_remote_audio");
        const rv = document.getElementById("navuli_remote_video");
        const lv = document.getElementById("navuli_local_video");
        if (la) la.srcObject = null;
        if (ra) ra.srcObject = null;
        if (rv) rv.srcObject = null;
        if (lv) lv.srcObject = null;
        // Reset video view, restore voice card
        const videoView = document.getElementById("navuli_video_view");
        const card      = document.getElementById("navuli_call_card");
        if (videoView) videoView.style.display = "none";
        if (card) card.style.display = "";
        const overlay = document.getElementById("navuli_call_overlay");
        if (overlay) overlay.style.display = "none";
        document.getElementById("navuli_incoming_call")?.classList.add("d-none");
        Object.assign(callState, {
            active: false, direction: null, callType: "voice",
            peerId: null, peerName: "", peerPhoto: null, conversationId: null,
            incomingOffer: null, startTime: null, durationTimer: null,
            callTimeout: null, incomingTimeout: null,
            localStream: null, pc: null, pendingCandidates: [], muted: false,
        });
        setComposerDisabled(convBlocked);   // unlock call buttons now that call is over
    }

    function initCallButtons() {
        document.getElementById("kt_drawer_chat_voice_call")?.addEventListener("click", () => initiateCall("voice"));
        document.getElementById("kt_drawer_chat_video_call")?.addEventListener("click", () => initiateCall("video"));
        document.getElementById("navuli_decline_btn")?.addEventListener("click", declineCall);
        document.getElementById("navuli_accept_btn")?.addEventListener("click", acceptCall);

        // Pre-warm AudioContext on first click anywhere on the page so that
        // startRinging() works on the callee side even when the call arrives
        // via a socket event (outside a user-gesture context).
        document.addEventListener("click", () => CallAudio.warmUp(), { once: true, passive: true });

        // "Call again" / "Call Back" buttons inside call event bubbles
        document.addEventListener("click", e => {
            const btn = e.target.closest("[data-navuli-call-back]");
            if (btn) initiateCall(btn.dataset.navuliCallBack || "voice");
        });
    }

    // ------------------------------------------------------------------ Public

    // ------------------------------------------------------------------ openForUser (called from other pages)

    function openForUser(userId, name, photoUrl) {
        const avatarEl = document.getElementById("kt_drawer_chat_avatar");
        const nameEl   = document.getElementById("kt_drawer_chat_name");
        const dotEl    = document.getElementById("kt_drawer_chat_status_dot");
        const textEl   = document.getElementById("kt_drawer_chat_status_text");

        if (avatarEl) avatarEl.innerHTML = photoUrl
            ? `<img src="${photoUrl}" class="rounded-circle" style="width:45px;height:45px;object-fit:cover;" alt="">`
            : `<div class="symbol-label bg-light-primary rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary fs-5" style="width:45px;height:45px;">${(name.charAt(0)||"?").toUpperCase()}</div>`;
        if (nameEl)  nameEl.textContent = name;
        if (dotEl)   dotEl.className    = "badge badge-circle w-10px h-10px me-1 bg-secondary";
        if (textEl)  textEl.textContent = "Offline";
        currentContactPhotoUrl = photoUrl || null;

        // Show the chat drawer
        const drawerEl = document.getElementById("kt_drawer_chat");
        const drawer   = drawerEl && KTDrawer.getInstance(drawerEl);
        if (drawer) {
            drawer.show();
        } else if (drawerEl) {
            drawerEl.classList.add("drawer-on");
            document.body.classList.add("drawer-on");
        }

        // Build synthetic trigger and load conversation
        const trigger = document.createElement("div");
        trigger.dataset.userName  = name;
        trigger.dataset.userPhoto = photoUrl || "";
        openConversation(parseInt(userId), trigger);
    }

    return {
        init() {
            initLightbox();
            initDeleteMenu();
            initShareModal();
            initMinimize();
            initCallButtons();
            initReactions();
            initEmojiPopup();
            initHeaderDropdown();
            initClickableHeader();
            initMessenger(document.querySelector("#kt_chat_messenger"));
            initMessenger(document.querySelector("#kt_drawer_chat_messenger"));
            connectSocket().then(() => fetchUnreadCount());
        },
        openConversation,
        openForUser,
        isSocketConnected: () => socketConnected,
    };

})();

KTUtil.onDOMContentLoaded(() => NavuliChat.init());
