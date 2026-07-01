<!--begin::Messages page-->
<div class="card" style="height: calc(100vh - 220px); min-height: 520px;">
	<div class="d-flex h-100">

		<!--begin::Conversation list (left pane)-->
		<div class="d-flex flex-column border-end" style="width: 320px; min-width: 320px;">
			<div class="px-5 py-4 border-bottom border-gray-200">
				<h4 class="fw-bold text-gray-900 mb-0">Messages</h4>
			</div>
			<div class="px-4 py-3 border-bottom border-gray-200">
				<div class="position-relative">
					<i class="ki-duotone ki-magnifier fs-4 text-gray-400 position-absolute top-50 translate-middle-y ms-3">
						<span class="path1"></span><span class="path2"></span>
					</i>
					<input type="text" id="mp_search" class="form-control form-control-sm ps-10" placeholder="Search users..." autocomplete="off" />
				</div>
			</div>
			<div id="mp_list" class="flex-grow-1 overflow-auto px-2 py-2">
				<div id="mp_loading" class="text-center py-5">
					<span class="spinner-border spinner-border-sm text-primary"></span>
					<div class="text-muted fs-8 mt-2">Loading users...</div>
				</div>
				<div id="mp_sentinel" style="height:1px;"></div>
			</div>
		</div>
		<!--end::Conversation list-->

		<!--begin::Messenger (right pane)-->
		<div class="card w-100 rounded-0 border-0 d-flex flex-column h-100" id="kt_chat_messenger">
			<!--begin::Card header-->
			<div class="card-header pe-5" id="mp_header">
				<div class="card-title flex-grow-1">
					<div class="d-flex align-items-center">
						<div class="symbol symbol-45px symbol-circle me-3 position-relative chat-header-clickable" id="mp_avatar">
							<div class="symbol-label bg-light-primary fs-5 fw-bold text-primary">?</div>
						</div>
						<div class="d-flex flex-column flex-grow-1">
							<span class="fs-5 fw-bold text-gray-900 lh-1 chat-header-clickable" id="mp_name">Select a conversation</span>
							<div class="mt-1 d-flex align-items-center justify-content-between">
								<div class="d-flex align-items-center chat-header-clickable">
									<span id="mp_status_dot" class="badge badge-circle w-10px h-10px me-1 bg-secondary"></span>
									<span class="fs-7 fw-semibold text-muted" id="mp_status_text"></span>
								</div>
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-icon btn-active-light-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="More options">
										<i class="ki-duotone ki-dots-vertical fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
									</button>
									<ul class="dropdown-menu dropdown-menu-end">
										<li><a class="dropdown-item" href="#" data-kt-element="block-toggle">Block</a></li>
										<li><a class="dropdown-item" href="#" data-kt-element="chat-transcript">Chat Transcript</a></li>
										<li><hr class="dropdown-divider"></li>
										<li><a class="dropdown-item text-danger" href="#" data-kt-element="clear-conversation">Clear Conversation</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--end::Card header-->

			<!--begin::Card body-->
			<div class="card-body" id="mp_body">
				<div class="scroll-y me-n5 pe-5"
				     data-kt-element="messages"
				     data-kt-scroll="true"
				     data-kt-scroll-activate="true"
				     data-kt-scroll-height="auto"
				     data-kt-scroll-dependencies="#mp_header, #mp_footer"
				     data-kt-scroll-wrappers="#mp_body"
				     data-kt-scroll-offset="0px">

					<!--begin::Message out template (my sent messages)-->
					<div class="d-none" data-kt-element="template-out">
						<div class="d-flex justify-content-end mb-6 chat-msg-row">
							<div class="d-flex flex-column align-items-end">
								<div class="d-flex align-items-center mb-1 gap-2">
									<span class="text-muted fs-8" data-kt-element="message-time">Just now</span>
									<div class="symbol symbol-30px symbol-circle">
										<?php if (!empty(session('photo'))): ?>
										<img alt="Me" src="<?= base_url('uploads/profilePhoto/' . session('photo')) ?>" style="object-fit:cover;">
										<?php else: ?>
										<div class="symbol-label bg-light-primary fs-8 fw-bold text-primary"><?= esc(session('initial') ?? '?') ?></div>
										<?php endif; ?>
									</div>
								</div>
								<div class="d-flex align-items-center gap-1">
									<button class="chat-msg-action" type="button" title="More options">
										<span class="cdm-dots">⋯</span>
									</button>
									<button class="chat-reaction-trigger" type="button" title="React">😊</button>
									<div class="px-4 py-3 rounded bg-light-primary text-dark fw-semibold mw-lg-380px text-end" data-kt-element="message-text"></div>
								</div>
								<div class="chat-reaction-row justify-content-end" data-kt-element="reaction-row"></div>
							</div>
						</div>
					</div>
					<!--end::Message out template-->

					<!--begin::Message in template (their incoming messages)-->
					<div class="d-none" data-kt-element="template-in">
						<div class="d-flex justify-content-start mb-6 chat-msg-row">
							<div class="d-flex flex-column align-items-start">
								<div class="d-flex align-items-center mb-1 gap-2">
									<div class="symbol symbol-30px symbol-circle">
										<img alt="" src="<?= base_url('app/assets/media/avatars/blank.png') ?>" style="width:30px;height:30px;object-fit:cover;border-radius:50%;">
									</div>
									<span class="fw-semibold fs-8 text-gray-700" data-kt-element="message-sender-name">–</span>
									<span class="text-muted fs-8" data-kt-element="message-time">Just now</span>
								</div>
								<div class="d-flex align-items-center gap-1">
									<div class="px-4 py-3 rounded bg-light-info text-dark fw-semibold mw-lg-380px text-start" data-kt-element="message-text"></div>
									<button class="chat-reaction-trigger" type="button" title="React">😊</button>
									<button class="chat-msg-action" type="button" title="More options">
										<span class="cdm-dots">⋯</span>
									</button>
								</div>
								<div class="chat-reaction-row justify-content-start" data-kt-element="reaction-row"></div>
							</div>
						</div>
					</div>
					<!--end::Message in template-->

					<!--begin::Typing indicator bubble (hidden, injected by JS)-->
					<div class="d-none chat-typing-indicator" data-kt-element="typing-bubble">
						<div class="d-flex align-items-end gap-2">
							<div class="symbol symbol-30px symbol-circle flex-shrink-0">
								<div class="symbol-label bg-light-info fw-bold text-info fs-9" data-kt-element="typing-initial">?</div>
							</div>
							<div class="chat-typing-bubble">
								<span class="typing-dot"></span>
								<span class="typing-dot"></span>
								<span class="typing-dot"></span>
							</div>
						</div>
					</div>
					<!--end::Typing indicator bubble-->

					<!--begin::Empty state-->
					<div class="text-center py-10" id="mp_empty">
						<i class="ki-duotone ki-message-text-2 fs-3x text-muted mb-3 d-block">
							<span class="path1"></span><span class="path2"></span><span class="path3"></span>
						</i>
						<span class="text-muted fs-6">Select a user on the left to start chatting.</span>
					</div>
					<!--end::Empty state-->

				</div>
			</div>
			<!--end::Card body-->

			<!--begin::Card footer-->
			<div class="card-footer pt-4" id="mp_footer">
				<div class="min-h-20px mb-1">
					<span data-kt-element="upload-progress" class="d-none text-muted fs-8">
						<span class="spinner-border spinner-border-sm me-1 align-middle"></span>Uploading…
					</span>
				</div>
				<textarea class="form-control form-control-flush mb-3" rows="1"
				          data-kt-element="input"
				          placeholder="Type a message"></textarea>
				<div class="d-flex flex-stack">
					<div class="d-flex align-items-center me-2">
						<button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button"
						        data-kt-element="attach"
						        title="Attach file (PDF, Word, Excel, PowerPoint, ZIP, TXT — max 10 MB)">
							<i class="ki-duotone ki-paper-clip fs-3"><span class="path1"></span><span class="path2"></span></i>
						</button>
						<input type="file" data-kt-element="file-input" class="d-none"
						       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip">
						<button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button"
						        data-kt-element="photo-attach"
						        title="Send photos (select up to 10 images)">
							<i class="ki-duotone ki-picture fs-3"><span class="path1"></span><span class="path2"></span></i>
						</button>
						<input type="file" data-kt-element="photo-input" class="d-none"
						       accept=".jpg,.jpeg,.png,.gif,.webp" multiple>
						<button class="btn btn-sm btn-icon btn-active-light-primary me-1" type="button"
						        data-kt-element="emoji"
						        title="Emoji">😊</button>
					</div>
					<button class="btn btn-primary" type="button" data-kt-element="send">Send</button>
				</div>
			</div>
			<!--end::Card footer-->
		</div>
		<!--end::Messenger-->

	</div>
</div>
<!--end::Messages page-->

<script>
(function () {
	'use strict';

	const PHOTO_BASE = '<?= base_url('uploads/profilePhoto/') ?>';
	const LIST_URL   = '<?= base_url('user/chatUserList') ?>';

	const initialUserId    = <?= json_encode($initial_target_user_id ?? null) ?>;
	const initialUserName  = <?= json_encode($initial_target_user_name ?? '') ?>;
	const initialUserPhoto = <?= json_encode($initial_target_user_photo ?? '') ?>;

	let page        = 1;
	let loading     = false;
	let hasMore     = true;
	let searchTimer = null;
	let activeUserId = initialUserId ? String(initialUserId) : null;

	const listEl     = document.getElementById('mp_list');
	const loadingEl  = document.getElementById('mp_loading');
	const sentinelEl = document.getElementById('mp_sentinel');
	const searchEl   = document.getElementById('mp_search');

	function esc(str) {
		return String(str ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
	}

	function dotColor(status) {
		return status === 'Online' ? '#50cd89' : '#a1a5b7';
	}

	// Per-user unread counts: userId (string) -> count
	const unreadCounts = {};

	function setUnreadBadge(userId, count) {
		userId = String(userId);
		unreadCounts[userId] = count;
		const row = listEl.querySelector(`.mp-row[data-user-id="${userId}"]`);
		if (!row) return;
		const badge = row.querySelector('.mp-unread-badge');
		if (!badge) return;
		if (count > 0) {
			badge.textContent          = count > 99 ? '99+' : String(count);
			badge.style.display        = 'flex';
			badge.style.visibility     = 'visible';
			badge.style.opacity        = '1';
		} else {
			badge.textContent          = '';
			badge.style.display        = 'none';
		}
	}

	function loadUnreadCounts() {
		fetch('<?= base_url('chat/unread-per-user') ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
			.then(r => r.json())
			.then(data => {
				if (!data.success) return;
				Object.entries(data.counts).forEach(([uid, cnt]) => setUnreadBadge(uid, cnt));
			})
			.catch(() => {/* silent */});
	}

	function buildRow(u) {
		const initials = ((u.fname || '').charAt(0) + (u.lname || '').charAt(0)).toUpperCase();
		const avatar   = u.profile_photo
			? `<img src="${PHOTO_BASE}${u.profile_photo}" class="rounded-circle" style="width:42px;height:42px;object-fit:cover;" alt="">`
			: `<div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary bg-light-primary fs-7" style="width:42px;height:42px;flex-shrink:0;">${initials}</div>`;
		const dot = `<span style="position:absolute;bottom:1px;right:1px;width:11px;height:11px;border-radius:50%;background:${dotColor(u.online_status)};border:2px solid #fff;"></span>`;

		const row = document.createElement('div');
		row.className          = 'mp-row d-flex align-items-center gap-3 px-3 py-2 rounded cursor-pointer';
		row.style.cssText      = 'transition:background .15s;';
		row.dataset.userId     = u.user_id;
		row.dataset.userName   = `${u.fname} ${u.lname}`;
		row.dataset.userPhoto  = u.profile_photo ? `${PHOTO_BASE}${u.profile_photo}` : '';
		row.dataset.userStatus = u.online_status;

		const storedCount = unreadCounts[String(u.user_id)] || 0;
		const badgeDisplay = storedCount > 0 ? 'flex' : 'none';
		const badgeText    = storedCount > 99 ? '99+' : (storedCount > 0 ? String(storedCount) : '');

		row.innerHTML = `
			<div style="position:relative;flex-shrink:0;">${avatar}${dot}</div>
			<div style="min-width:0;flex:1;">
				<div class="fw-semibold text-gray-800 fs-7 text-truncate">${esc(u.fname)} ${esc(u.lname)}</div>
				<div class="fs-9 ${u.online_status === 'Online' ? 'text-success' : 'text-muted'}">${esc(u.online_status)}</div>
			</div>
			<span class="mp-unread-badge" style="display:${badgeDisplay};align-items:center;justify-content:center;flex-shrink:0;min-width:20px;height:20px;border-radius:10px;background:#f1416c;color:#fff;font-size:10px;font-weight:700;line-height:1;padding:0 5px;">${badgeText}</span>`;
		row.addEventListener('mouseenter', () => row.style.background = 'var(--bs-gray-100)');
		row.addEventListener('mouseleave', () => row.style.background = '');
		row.addEventListener('click', () => openChat(u.user_id, row.dataset.userName, row.dataset.userPhoto, u.online_status, row));
		if (activeUserId && String(u.user_id) === activeUserId) row.style.background = 'var(--bs-gray-100)';
		return row;
	}

	function openChat(userId, name, photoUrl, status, row) {
		const initials = (name || '?').trim().split(/\s+/).map(p => p.charAt(0)).join('').toUpperCase().slice(0, 2) || '?';
		const isOnline  = status === 'Online';

		document.getElementById('mp_avatar').innerHTML = photoUrl
			? `<img src="${esc(photoUrl)}" class="rounded-circle" style="width:45px;height:45px;object-fit:cover;" alt="">`
			: `<div class="symbol-label bg-light-primary rounded-circle d-flex align-items-center justify-content-center fw-bold text-primary fs-5" style="width:45px;height:45px;">${initials}</div>`;
		document.getElementById('mp_name').textContent        = name || '';
		document.getElementById('mp_status_dot').className    = `badge badge-circle w-10px h-10px me-1 ${isOnline ? 'bg-success' : 'bg-secondary'}`;
		document.getElementById('mp_status_text').textContent = isOnline ? 'Online' : 'Offline';

		activeUserId = String(userId);
		setUnreadBadge(userId, 0);
		listEl.querySelectorAll('.mp-row').forEach(el => {
			el.style.background = el.dataset.userId === activeUserId ? 'var(--bs-gray-100)' : '';
		});

		const trigger = row || (() => {
			const el = document.createElement('div');
			el.dataset.userName  = name || '';
			el.dataset.userPhoto = photoUrl || '';
			return el;
		})();

		if (window.NavuliChat) window.NavuliChat.openConversation(parseInt(userId), trigger);
	}

	function fetchPage() {
		if (loading || !hasMore) return;
		loading = true;
		loadingEl.style.display = 'block';

		const url = `${LIST_URL}?page=${page}&search=${encodeURIComponent(searchEl.value.trim())}`;
		fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
			.then(r => r.json())
			.then(data => {
				if (!data.success) return;
				data.users.forEach(u => listEl.insertBefore(buildRow(u), loadingEl));
				hasMore = data.hasMore;
				page    = data.nextPage;
				// Fetch/re-apply unread counts after rows are in the DOM
				loadUnreadCounts();
			})
			.catch(() => {/* silent */})
			.finally(() => {
				loadingEl.style.display = 'none';
				loading = false;
			});
	}

	const observer = new IntersectionObserver(entries => {
		if (entries[0].isIntersecting) fetchPage();
	}, { root: null, rootMargin: '0px', threshold: 0 });
	observer.observe(sentinelEl);

	searchEl.addEventListener('input', () => {
		clearTimeout(searchTimer);
		searchTimer = setTimeout(() => {
			page    = 1;
			hasMore = true;
			listEl.querySelectorAll('.mp-row').forEach(el => el.remove());
			fetchPage();
		}, 350);
	});

	fetchPage();

	// If we navigated straight here with a known target user (e.g. via "Open in Message"),
	// open the conversation immediately without waiting for their row to appear in the list.
	if (initialUserId) {
		openChat(initialUserId, initialUserName, initialUserPhoto, '', null);
	}

	// Live update: a user we're allowed to see just connected and isn't rendered yet
	// (e.g. they're beyond the currently-loaded page) — add them at the top without reload.
	document.addEventListener('navuli:userOnline', e => {
		const u = e.detail;
		if (!u || !u.user_id) return;
		if (listEl.querySelector(`.mp-row[data-user-id="${u.user_id}"]`)) return;
		const term = searchEl.value.trim().toLowerCase();
		if (term && !`${u.fname} ${u.lname}`.toLowerCase().includes(term)) return;
		listEl.insertBefore(buildRow(u), listEl.firstChild);
	});

	// Real-time unread badge updates from the chat module
	document.addEventListener('navuli:unreadBadge', e => {
		const { userId, count, action } = e.detail || {};
		if (!userId) return;
		if (action === 'increment') {
			// Don't increment badge for the conversation currently open
			if (String(userId) === activeUserId) return;
			const current = unreadCounts[String(userId)] || 0;
			setUnreadBadge(userId, current + 1);
		} else {
			setUnreadBadge(userId, count || 0);
		}
	});

}());
</script>
