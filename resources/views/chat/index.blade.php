@extends('layouts.app')

@section('title', 'Inbox')
@section('body-class', 'page-inbox')

<link rel="stylesheet" href="{{ asset('css/inbox.css') }}" />

@section('content')

    <style>
        body.page-inbox .page {
            padding: 0;
            height: calc(100vh - var(--topbar-h));
            display: block;
            overflow: hidden;
            animation: none;
        }

        .new-chat-modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .new-chat-box {
            width: 420px;
            max-height: 80vh;
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .new-chat-header {
            padding: 18px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .new-chat-header h3 {
            margin: 0;
            font-size: 18px;
        }

        .new-chat-header button {
            border: none;
            background: none;
            font-size: 18px;
            cursor: pointer;
        }

        .new-chat-search {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .new-chat-search input {
            width: 100%;
            height: 42px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 0 14px;
            outline: none;
        }

        .new-chat-list {
            overflow: auto;
            flex: 1;
        }

        .driver-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f3f3;
        }

        .driver-item:hover {
            background: #f8f8f8;
        }

        .driver-item img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .driver-item-name {
            font-weight: 600;
            font-size: 14px;
        }

        .driver-item-license {
            font-size: 12px;
            color: #777;
        }

        /*
        |--------------------------------------------------------------------------
        | WHATSAPP STYLE MESSAGE DESIGN
        |--------------------------------------------------------------------------
        */
        .chat-msg {
            max-width: 320px;
            width: fit-content;
            padding: 8px;
            border-radius: 16px;
            margin-bottom: 10px;
            position: relative;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
            word-break: break-word;
        }

        .chat-msg.from-me {
            margin-left: auto;
            background: #ff6b00;
            color: #ffffff;
            border-bottom-right-radius: 5px;
        }

        .chat-msg.from-driver {
            margin-right: auto;
            background: #ffffff;
            color: #111827;
            border-bottom-left-radius: 5px;
            border: 1px solid #eeeeee;
        }

        .chat-msg__text {
            font-size: 14px;
            line-height: 1.45;
            padding: 4px 6px 2px;
        }

        .chat-msg__time {
            font-size: 10px;
            opacity: .85;
            text-align: right;
            margin-top: 5px;
            padding: 0 4px;
        }

        /*
        |--------------------------------------------------------------------------
        | IMAGE MESSAGE
        |--------------------------------------------------------------------------
        */
        .wa-image-wrap {
            width: 245px;
            max-width: 100%;
            border-radius: 14px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.16);
            cursor: pointer;
        }

        .wa-image-wrap img {
            width: 100%;
            max-height: 310px;
            display: block;
            object-fit: cover;
            border-radius: 14px;
            transition: transform .25s ease;
        }

        .wa-image-wrap:hover img {
            transform: scale(1.02);
        }

        /*
        |--------------------------------------------------------------------------
        | FILE MESSAGE
        |--------------------------------------------------------------------------
        */
        .wa-file-card {
            min-width: 245px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border-radius: 14px;
            text-decoration: none;
            color: inherit;
            background: rgba(255, 255, 255, 0.16);
        }

        .from-driver .wa-file-card {
            background: #f4f6f8;
            color: #111827;
        }

        .wa-file-icon {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: rgba(255, 255, 255, 0.24);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .from-driver .wa-file-icon {
            background: #ffffff;
        }

        .wa-file-name {
            font-size: 13px;
            font-weight: 600;
            max-width: 165px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .wa-file-sub {
            font-size: 11px;
            opacity: .75;
            margin-top: 2px;
        }

        /*
        |--------------------------------------------------------------------------
        | WHATSAPP STYLE VOICE
        |--------------------------------------------------------------------------
        */
        .wa-voice {
            width: 275px;
            max-width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 6px;
            border-radius: 16px;
        }

        .wa-voice-play {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        .from-driver .wa-voice-play {
            background: #ff6b00;
            color: #ffffff;
        }

        .wa-voice-wave {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 3px;
            height: 36px;
            cursor: pointer;
        }

        .wa-voice-wave span {
            width: 3px;
            border-radius: 99px;
            background: rgba(255, 255, 255, 0.78);
            display: block;
        }

        .from-driver .wa-voice-wave span {
            background: #c7c7c7;
        }

        .wa-voice-wave span.active {
            background: #ffffff;
        }

        .from-driver .wa-voice-wave span.active {
            background: #ff6b00;
        }

        .wa-voice-meta {
            min-width: 42px;
            font-size: 11px;
            opacity: .9;
            text-align: right;
        }

        .wa-voice-text {
            font-size: 12px;
            line-height: 1.35;
            opacity: .95;
            margin-top: 5px;
            padding: 0 6px;
        }

        .hidden-audio {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | IMAGE LIGHTBOX
        |--------------------------------------------------------------------------
        */
        .image-lightbox {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.86);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            padding: 25px;
        }

        .image-lightbox.active {
            display: flex;
        }

        .image-lightbox img {
            max-width: 92vw;
            max-height: 88vh;
            border-radius: 16px;
            object-fit: contain;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .45);
        }

        .image-lightbox__close {
            position: fixed;
            top: 22px;
            right: 26px;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, .16);
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .chat-msg {
                max-width: 82%;
            }

            .wa-voice {
                width: 235px;
            }

            .wa-image-wrap {
                width: 230px;
            }
        }
    </style>

    <section class="page">
        <div class="inbox">

            <aside class="inbox-list">
                <header class="inbox-list__head">
                    <h2>Inbox</h2>

                    <div style="display:flex;gap:8px">
                        <button class="icon-btn" id="broadcastBtn" title="Broadcast Message">
                            📢
                        </button>

                        <button class="icon-btn" id="newConv" aria-label="New conversation" title="New conversation">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14M5 12h14"></path>
                            </svg>
                        </button>
                    </div>
                </header>

                <div class="inbox-search">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-3-3"></path>
                    </svg>

                    <input id="convSearch" type="text" placeholder="Search conversations…">
                </div>

                <div class="conv-list" id="convList"></div>
            </aside>

            <main class="inbox-chat">
                <header class="chat-head" id="chatHead"></header>

                <div class="chat-body" id="chatBody"></div>

                <div class="chat-quick">
                    <button data-quick="On my way 👍">On my way 👍</button>
                    <button data-quick="ETA in 30 minutes.">ETA in 30 minutes</button>
                    <button data-quick="Confirmed, thanks.">Confirmed, thanks</button>
                    <button data-quick="Need a swap, can you help?">Need a swap</button>
                    <button data-quick="At the depot, all clear.">At the depot</button>
                </div>

                <footer class="chat-composer">
                    <button class="chat-composer__attach" id="chatAttach" aria-label="Attach">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48">
                            </path>
                        </svg>
                    </button>

                    <input type="file" id="chatFileInput" hidden>

                    <input id="chatInput" class="chat-composer__input" type="text" placeholder="Type a message…"
                        autocomplete="off">

                    <button class="chat-mic" id="voiceRecordBtn">
                        🎤
                    </button>

                    <button class="chat-send" id="chatSend" disabled>
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m22 2-7 20-4-9-9-4Z"></path>
                            <path d="M22 2 11 13"></path>
                        </svg>
                        <span>Send</span>
                    </button>
                </footer>
            </main>

            <aside class="inbox-details" id="chatDetails"></aside>
        </div>

        <div id="newChatModal" class="new-chat-modal">
            <div class="new-chat-box">
                <div class="new-chat-header">
                    <h3>Select Driver</h3>
                    <button onclick="closeNewChatModal()">✕</button>
                </div>

                <div class="new-chat-search">
                    <input type="text" id="driverSearch" placeholder="Search driver...">
                </div>

                <div class="new-chat-list" id="driverList">
                    Loading...
                </div>
            </div>
        </div>

        <div id="broadcastModal" class="new-chat-modal">
            <div class="new-chat-box">
                <div class="new-chat-header">
                    <h3>Broadcast Message</h3>
                    <button onclick="closeBroadcastModal()">✕</button>
                </div>

                <div class="new-chat-search">
                    <input type="text" id="broadcastDriverSearch" placeholder="Search driver...">
                </div>

                <div class="new-chat-list" id="broadcastDriverList">
                    Loading...
                </div>

                <div style="padding:15px;border-top:1px solid #eee">
                    <textarea id="broadcastMessage" placeholder="Type broadcast message..."
                        style="
                    width:100%;
                    height:120px;
                    border:1px solid #ddd;
                    border-radius:12px;
                    padding:12px;
                    resize:none;
                    outline:none;
                "></textarea>

                    <button id="sendBroadcastBtn"
                        style="
                    width:100%;
                    height:46px;
                    margin-top:12px;
                    border:none;
                    border-radius:12px;
                    background:#ff6b1a;
                    color:#fff;
                    font-weight:700;
                    cursor:pointer;
                ">
                        Send Broadcast
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div id="toast"
        style="
    position:fixed;
    bottom:20px;
    right:20px;
    background:#1f2937;
    color:#fff;
    padding:12px 16px;
    border-radius:12px;
    font-size:14px;
    display:none;
    z-index:99999;
    box-shadow:0 10px 25px rgba(0,0,0,.2);
">
    </div>

    <div id="imageLightbox" class="image-lightbox" onclick="closeImageLightbox()">
        <button class="image-lightbox__close" onclick="closeImageLightbox()">×</button>
        <img id="imageLightboxImg" src="" alt="">
    </div>

    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

    <script>
        function showToast(message, type = 'success') {
            let toast = document.getElementById('toast');

            toast.innerText = message;
            toast.style.background = type === 'success' ? '#16a34a' : '#dc2626';
            toast.style.display = 'block';

            setTimeout(() => {
                toast.style.display = 'none';
            }, 2500);
        }

        /*
        |--------------------------------------------------------------------------
        | GLOBALS
        |--------------------------------------------------------------------------
        */
        Pusher.logToConsole = true;

        let activeChatId = null;
        let activeChannel = null;

        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true,
            authEndpoint: '/broadcasting/auth',
            withCredentials: true,
        });

        /*
        |--------------------------------------------------------------------------
        | HELPERS
        |--------------------------------------------------------------------------
        */
        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatTime(date) {
            if (!date) {
                return '';
            }

            let d = new Date(date);

            if (isNaN(d.getTime())) {
                return '';
            }

            return d.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function scrollChatBottom() {
            let body = document.getElementById('chatBody');
            body.scrollTop = body.scrollHeight;
        }

        function openImageLightbox(src) {
            document.getElementById('imageLightboxImg').src = src;
            document.getElementById('imageLightbox').classList.add('active');
        }

        function closeImageLightbox() {
            document.getElementById('imageLightbox').classList.remove('active');
            document.getElementById('imageLightboxImg').src = '';
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD CHAT LIST
        |--------------------------------------------------------------------------
        */
        function loadChats() {
            fetch("{{ route('chat.list') }}")
                .then(res => res.json())
                .then(res => {
                    let html = '';

                    res.data.forEach(chat => {
                        let image = chat.driver?.driver_photo ?
                            `/${chat.driver.driver_photo}` :
                            'https://ui-avatars.com/api/?name=' + encodeURIComponent(chat.driver?.full_name ??
                                'Driver');

                        let unreadBadge = '';

                        if (chat.unread_count > 0) {
                            unreadBadge = `
                            <span class="unread-badge" style="
                                background:#ff6b1a;
                                color:#fff;
                                min-width:20px;
                                height:20px;
                                border-radius:50%;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                font-size:11px;
                                font-weight:700;
                                margin-top:6px;
                            ">
                                ${chat.unread_count}
                            </span>
                        `;
                        }

                        let preview = chat.last_message ?? '';

                        html += `
                        <div class="conv-item" id="chat-item-${chat.id}" onclick="openChat(${chat.id})">
                            <div class="conv-avatar">
                                <img src="${image}" alt="">
                            </div>

                            <div class="conv-info">
                                <div class="conv-name">
                                    ${escapeHtml(chat.driver?.full_name ?? 'Driver')}
                                </div>

                                <div class="conv-preview" id="chat-last-message-${chat.id}">
                                    ${escapeHtml(preview)}
                                </div>
                            </div>

                            <div class="conv-meta">
                                <span class="conv-time">
                                    ${formatTime(chat.last_message_at ?? '')}
                                </span>

                                ${unreadBadge}
                            </div>
                        </div>
                    `;
                    });

                    document.getElementById('convList').innerHTML = html;
                });
        }

        /*
        |--------------------------------------------------------------------------
        | OPEN CHAT
        |--------------------------------------------------------------------------
        */
        function openChat(chatId) {
            activeChatId = chatId;

            document.querySelectorAll('.conv-item').forEach(item => {
                item.classList.remove('active');
            });

            let activeItem = document.getElementById('chat-item-' + chatId);

            if (activeItem) {
                activeItem.classList.add('active');
            }

            fetch(`{{ route('chat.messages') }}?chat_id=${chatId}`)
                .then(res => res.json())
                .then(res => {
                    if (res.chat) {
                        updateChatDetails(res.chat);
                    }

                    let html = '';

                    res.data.forEach(msg => {
                        html += messageHtml(msg);
                    });

                    document.getElementById('chatBody').innerHTML = html;

                    initVoicePlayers();
                    scrollChatBottom();

                    fetch(`{{ route('chat.seen') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            chat_id: chatId
                        })
                    });

                    let unread = activeItem?.querySelector('.unread-badge');

                    if (unread) {
                        unread.remove();
                    }
                });

            if (activeChannel) {
                window.Echo.leave(activeChannel);
            }

            activeChannel = 'chat.' + chatId;

            window.Echo.private(activeChannel)
                .listen('.message.sent', (e) => {
                    if (e.sender_type === 'admin') {
                        return;
                    }

                    appendMessage(e);

                    let preview = document.getElementById('chat-last-message-' + chatId);

                    if (preview) {
                        preview.innerText = e.message ?? 'File';
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE CHAT DETAILS
        |--------------------------------------------------------------------------
        */
        function updateChatDetails(chat) {
            let driver = chat.driver || {};
            let truck = driver.truck || {};

            let image = driver?.driver_photo ?
                `/${driver.driver_photo}` :
                'https://ui-avatars.com/api/?name=' + encodeURIComponent(driver?.full_name ?? 'Driver');

            document.getElementById('chatHead').innerHTML = `
            <div class="chat-head__avatar">
                <img src="${image}" alt="">
            </div>

            <div>
                <div class="chat-head__name">
                    ${escapeHtml(driver?.full_name ?? 'Driver')}
                </div>

                <div class="chat-head__status">
                    Driver : ${escapeHtml(driver?.license_number ?? 'N/A')}
                </div>
            </div>

            <div class="chat-head__actions">
                <a title="Call" href="tel:${escapeHtml(driver?.phone ?? '')}">
                    <svg viewBox="0 0 24 24"
                        width="18"
                        height="18"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                        19.8 19.8 0 0 1-8.63-3.07
                        19.5 19.5 0 0 1-6-6
                        19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3
                        a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81
                        a2 2 0 0 1-.45 2.11L8.09 9.91
                        a16 16 0 0 0 6 6l1.27-1.27
                        a2 2 0 0 1 2.11-.45c.91.35
                        1.85.59 2.81.72A2 2 0 0 1 22 16.92Z"></path>
                    </svg>
                </a>
            </div>
        `;

            document.getElementById('chatDetails').innerHTML = `
            <div class="chat-profile">
                <img class="chat-profile__avatar" src="${image}" alt="">
                <h3>${escapeHtml(driver?.full_name ?? 'Driver')}</h3>
                <p>Driver: ${escapeHtml(driver?.license_number ?? 'N/A')}</p>
            </div>

            <div class="details-section">
                <h4>Driver Info</h4>

                <div class="details-row">
                    <span class="k">Date of Birth</span>
                    <span class="v">${escapeHtml(driver?.date_of_birth ?? 'N/A')}</span>
                </div>

                <div class="details-row">
                    <span class="k">Phone</span>
                    <span class="v">${escapeHtml(driver?.phone ?? 'N/A')}</span>
                </div>

                <div class="details-row">
                    <span class="k">Email</span>
                    <span class="v">${escapeHtml(driver?.email ?? 'N/A')}</span>
                </div>

                <div class="details-row">
                    <span class="k">Status</span>
                    <span class="v">${escapeHtml(driver?.status ?? 'Active')}</span>
                </div>
            </div>

            <div class="details-section">
                <h4>Truck Details</h4>

                <div class="details-row">
                    <span class="k">Truck No</span>
                    <span class="v">${escapeHtml(truck?.truck_number ?? 'N/A')}</span>
                </div>

                <div class="details-row">
                    <span class="k">Plate</span>
                    <span class="v">${escapeHtml(truck?.license_plate_number ?? 'N/A')}</span>
                </div>

                <div class="details-row">
                    <span class="k">Type</span>
                    <span class="v">${escapeHtml(truck?.truck_type_category ?? 'N/A')}</span>
                </div>

                <div class="details-row">
                    <span class="k">Capacity(Tons)</span>
                    <span class="v">${escapeHtml(truck?.capacity_tons ?? 'N/A')}</span>
                </div>
            </div>

            <div class="details-section">
                <h4>Conversation</h4>

                <div class="details-row">
                    <span class="k">Chat ID</span>
                    <span class="v">#${chat.id}</span>
                </div>

                <div class="details-row">
                    <span class="k">Last Message</span>
                    <span class="v">${escapeHtml(chat.last_message ?? 'N/A')}</span>
                </div>

                <div class="details-row">
                    <span class="k">Updated</span>
                    <span class="v">${escapeHtml(chat.last_message_at ?? 'N/A')}</span>
                </div>
            </div>
        `;
        }

        /*
        |--------------------------------------------------------------------------
        | MESSAGE HTML
        |--------------------------------------------------------------------------
        */
        function messageHtml(msg) {
            let isMe = msg.sender_type === 'admin';
            let type = msg.file_type ?? null;
            let message = msg.message ?? '';
            let file = msg.file ?? '';
            let fileName = msg.file_name ?? 'Download File';
            let contentHtml = '';

            if (type === 'image' && file) {
                contentHtml = `
                <div class="wa-image-wrap" onclick="openImageLightbox('${file}')">
                    <img src="${file}" alt="Image">
                </div>
            `;
            } else if (type === 'voice' && file) {
                let voiceId = 'voice-' + Math.random().toString(36).substring(2, 12);

                contentHtml = `
                <div class="wa-voice" data-voice-id="${voiceId}">
                    <button type="button" class="wa-voice-play" data-audio="${voiceId}">
                        ▶
                    </button>

                    <div class="wa-voice-wave" data-audio="${voiceId}">
                        <span style="height:8px"></span>
                        <span style="height:18px"></span>
                        <span style="height:12px"></span>
                        <span style="height:24px"></span>
                        <span style="height:15px"></span>
                        <span style="height:28px"></span>
                        <span style="height:13px"></span>
                        <span style="height:21px"></span>
                        <span style="height:11px"></span>
                        <span style="height:26px"></span>
                        <span style="height:14px"></span>
                        <span style="height:20px"></span>
                        <span style="height:9px"></span>
                        <span style="height:25px"></span>
                        <span style="height:12px"></span>
                        <span style="height:18px"></span>
                        <span style="height:10px"></span>
                        <span style="height:22px"></span>
                    </div>

                    <div class="wa-voice-meta" id="${voiceId}-time">
                        0:00
                    </div>

                    <audio id="${voiceId}" class="hidden-audio" preload="metadata">
                        <source src="${file}" type="audio/webm">
                        <source src="${file}" type="audio/mpeg">
                    </audio>
                </div>

                ${message ? `<div class="wa-voice-text">${escapeHtml(message)}</div>` : ''}
            `;
            } else if (file) {
                contentHtml = `
                <a href="${file}" target="_blank" class="wa-file-card">
                    <div class="wa-file-icon">📎</div>
                    <div>
                        <div class="wa-file-name">${escapeHtml(fileName)}</div>
                        <div class="wa-file-sub">Download File</div>
                    </div>
                </a>
            `;
            } else {
                contentHtml = `
                <div class="chat-msg__text">
                    ${escapeHtml(message)}
                </div>
            `;
            }

            return `
            <div class="chat-msg ${isMe ? 'from-me' : 'from-driver'}">
                ${contentHtml}

                <div class="chat-msg__time">
                    ${formatTime(msg.created_at)}
                </div>
            </div>
        `;
        }

        function appendMessage(msg) {
            document.getElementById('chatBody').innerHTML += messageHtml(msg);
            initVoicePlayers();
            scrollChatBottom();
        }

        /*
        |--------------------------------------------------------------------------
        | VOICE PLAYER
        |--------------------------------------------------------------------------
        */
        function initVoicePlayers() {
            document.querySelectorAll('.wa-voice-play:not([data-ready="1"])').forEach(button => {
                button.dataset.ready = '1';

                button.addEventListener('click', function() {
                    let audioId = this.dataset.audio;
                    let audio = document.getElementById(audioId);

                    if (!audio) {
                        return;
                    }

                    document.querySelectorAll('audio').forEach(item => {
                        if (item !== audio) {
                            item.pause();
                        }
                    });

                    document.querySelectorAll('.wa-voice-play').forEach(btn => {
                        if (btn !== this) {
                            btn.innerHTML = '▶';
                        }
                    });

                    if (audio.paused) {
                        audio.play();
                        this.innerHTML = '⏸';
                    } else {
                        audio.pause();
                        this.innerHTML = '▶';
                    }
                });
            });

            document.querySelectorAll('.wa-voice-wave:not([data-ready="1"])').forEach(wave => {
                wave.dataset.ready = '1';

                wave.addEventListener('click', function() {
                    let audioId = this.dataset.audio;
                    let audio = document.getElementById(audioId);
                    let btn = document.querySelector(`.wa-voice-play[data-audio="${audioId}"]`);

                    if (btn) {
                        btn.click();
                    }
                });
            });

            document.querySelectorAll('.hidden-audio:not([data-ready="1"])').forEach(audio => {
                audio.dataset.ready = '1';

                audio.addEventListener('loadedmetadata', function() {
                    let timeBox = document.getElementById(this.id + '-time');

                    if (timeBox && isFinite(this.duration)) {
                        timeBox.innerText = secondsToTime(this.duration);
                    }
                });

                audio.addEventListener('timeupdate', function() {
                    let timeBox = document.getElementById(this.id + '-time');

                    if (timeBox) {
                        timeBox.innerText = secondsToTime(this.currentTime);
                    }

                    let wrapper = document.querySelector(`.wa-voice[data-voice-id="${this.id}"]`);
                    let bars = wrapper ? wrapper.querySelectorAll('.wa-voice-wave span') : [];

                    if (bars.length && this.duration) {
                        let activeCount = Math.floor((this.currentTime / this.duration) * bars.length);

                        bars.forEach((bar, index) => {
                            if (index <= activeCount) {
                                bar.classList.add('active');
                            } else {
                                bar.classList.remove('active');
                            }
                        });
                    }
                });

                audio.addEventListener('ended', function() {
                    let btn = document.querySelector(`.wa-voice-play[data-audio="${this.id}"]`);

                    if (btn) {
                        btn.innerHTML = '▶';
                    }

                    let wrapper = document.querySelector(`.wa-voice[data-voice-id="${this.id}"]`);

                    if (wrapper) {
                        wrapper.querySelectorAll('.wa-voice-wave span').forEach(bar => {
                            bar.classList.remove('active');
                        });
                    }

                    let timeBox = document.getElementById(this.id + '-time');

                    if (timeBox && isFinite(this.duration)) {
                        timeBox.innerText = secondsToTime(this.duration);
                    }
                });
            });
        }

        function secondsToTime(seconds) {
            seconds = Math.floor(seconds || 0);

            let min = Math.floor(seconds / 60);
            let sec = seconds % 60;

            return min + ':' + String(sec).padStart(2, '0');
        }

        /*
        |--------------------------------------------------------------------------
        | SEND TEXT MESSAGE
        |--------------------------------------------------------------------------
        */
        function sendMessage() {
            let input = document.getElementById('chatInput');
            let message = input.value.trim();

            if (!message || !activeChatId) {
                return;
            }

            let tempMessage = {
                sender_type: 'admin',
                message: message,
                created_at: new Date().toISOString()
            };

            appendMessage(tempMessage);

            let preview = document.getElementById('chat-last-message-' + activeChatId);

            if (preview) {
                preview.innerText = message;
            }

            input.value = '';
            document.getElementById('chatSend').disabled = true;

            fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        chat_id: activeChatId,
                        message: message
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (!res.status) {
                        showToast('Message failed', 'error');
                    }
                })
                .catch(err => {
                    console.log(err);
                    showToast('Message failed', 'error');
                });
        }

        document.getElementById('chatInput').addEventListener('input', function() {
            document.getElementById('chatSend').disabled = this.value.trim() === '';
        });

        document.getElementById('chatInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });

        document.getElementById('chatSend').addEventListener('click', sendMessage);

        document.querySelectorAll('.chat-quick button').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('chatInput').value = this.dataset.quick;
                document.getElementById('chatSend').disabled = false;
            });
        });

        document.getElementById('convSearch').addEventListener('keyup', function() {
            let value = this.value.toLowerCase();

            document.querySelectorAll('.conv-item').forEach(item => {
                let text = item.innerText.toLowerCase();
                item.style.display = text.includes(value) ? 'flex' : 'none';
            });
        });

        /*
        |--------------------------------------------------------------------------
        | NEW CHAT
        |--------------------------------------------------------------------------
        */
        document.getElementById('newConv').addEventListener('click', openNewChatModal);

        function openNewChatModal() {
            document.getElementById('newChatModal').style.display = 'flex';
            loadDrivers();
        }

        function closeNewChatModal() {
            document.getElementById('newChatModal').style.display = 'none';
        }

        function loadDrivers() {
            fetch("{{ route('chat.drivers') }}")
                .then(res => res.json())
                .then(res => {
                    let html = '';

                    res.data.forEach(driver => {
                        let image = driver.driver_photo ?
                            `/${driver.driver_photo}` :
                            'https://ui-avatars.com/api/?name=' + encodeURIComponent(driver.full_name);

                        html += `
                        <div class="driver-item" onclick="selectDriver(${driver.id})">
                            <img src="${image}" alt="">

                            <div>
                                <div class="driver-item-name">
                                    ${escapeHtml(driver.full_name)}
                                </div>

                                <div class="driver-item-license">
                                    ${escapeHtml(driver.license_number ?? 'N/A')}
                                </div>
                            </div>
                        </div>
                    `;
                    });

                    document.getElementById('driverList').innerHTML = html;
                });
        }

        document.getElementById('driverSearch').addEventListener('keyup', function() {
            let value = this.value.toLowerCase();

            document.querySelectorAll('.driver-item').forEach(item => {
                item.style.display = item.innerText.toLowerCase().includes(value) ? 'flex' : 'none';
            });
        });

        function selectDriver(driverId) {
            fetch("{{ route('chat.create_or_get') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        driver_id: driverId
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (!res.status) {
                        return;
                    }

                    closeNewChatModal();
                    loadChats();

                    setTimeout(() => {
                        openChat(res.chat_id);
                    }, 300);
                });
        }

        /*
        |--------------------------------------------------------------------------
        | SEND FILE MESSAGE
        |--------------------------------------------------------------------------
        */
        document.getElementById('chatAttach').addEventListener('click', function() {
            document.getElementById('chatFileInput').click();
        });

        document.getElementById('chatFileInput').addEventListener('change', function() {
            let file = this.files[0];

            if (!file || !activeChatId) {
                return;
            }

            sendFileMessage(file);
            this.value = '';
        });

        function sendFileMessage(file) {
            let isImage = file.type.startsWith('image/');

            /*
            |--------------------------------------------------------------------------
            | TEMP MESSAGE ID
            |--------------------------------------------------------------------------
            */
            let tempId = 'temp-file-' + Date.now();

            let tempMessage = {
                sender_type: 'admin',
                message: '',
                created_at: new Date().toISOString(),
                file: isImage ? URL.createObjectURL(file) : '#',
                file_type: isImage ? 'image' : 'file',
                file_name: file.name,
                temp_id: tempId
            };

            /*
            |--------------------------------------------------------------------------
            | SHOW TEMP PREVIEW
            |--------------------------------------------------------------------------
            */
            appendMessage(tempMessage);

            /*
            |--------------------------------------------------------------------------
            | ADD TEMP ID TO LAST MESSAGE DIV
            |--------------------------------------------------------------------------
            */
            let allMessages = document.querySelectorAll('#chatBody .chat-msg');
            let lastMessage = allMessages[allMessages.length - 1];

            if (lastMessage) {
                lastMessage.setAttribute('id', tempId);
                lastMessage.style.opacity = '0.65';
            }

            let preview = document.getElementById('chat-last-message-' + activeChatId);

            if (preview) {
                preview.innerText = isImage ? '📷 Sending image...' : '📎 Sending file...';
            }

            let formData = new FormData();

            formData.append('chat_id', activeChatId);
            formData.append('file', file);

            fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async response => {

                    let text = await response.text();

                    let data;

                    try {
                        data = JSON.parse(text);
                    } catch (error) {
                        console.log('Non JSON response:', text);

                        throw new Error('Server returned HTML error. Check Laravel log or Network tab.');
                    }

                    if (!response.ok) {
                        throw new Error(data.message || 'Upload failed');
                    }

                    return data;
                })
                .then(res => {

                    console.log('File upload response:', res);

                    if (!res.status) {
                        throw new Error(res.message || 'File upload failed');
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE TEMP BLOB PREVIEW
                    |--------------------------------------------------------------------------
                    */
                    let tempEl = document.getElementById(tempId);

                    if (tempEl) {
                        tempEl.remove();
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | APPEND REAL SERVER MESSAGE
                    |--------------------------------------------------------------------------
                    */
                    appendMessage(res.data);

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE CHAT PREVIEW
                    |--------------------------------------------------------------------------
                    */
                    if (preview) {
                        preview.innerText = res.data.file_type === 'image' ?
                            '📷 Image' :
                            '📎 ' + (res.data.file_name ?? 'File');
                    }

                    loadChats();
                })
                .catch(err => {

                    console.log('File upload error:', err);

                    let tempEl = document.getElementById(tempId);

                    if (tempEl) {
                        tempEl.style.opacity = '1';
                        tempEl.style.border = '1px solid #dc2626';

                        let errorBox = document.createElement('div');
                        errorBox.style.fontSize = '11px';
                        errorBox.style.marginTop = '6px';
                        errorBox.style.color = '#fff';
                        errorBox.innerText = 'Upload failed';

                        tempEl.appendChild(errorBox);
                    }

                    if (preview) {
                        preview.innerText = 'Upload failed';
                    }

                    showToast(err.message || 'File upload failed', 'error');
                });
        }
        /*
        |--------------------------------------------------------------------------
        | BROADCAST
        |--------------------------------------------------------------------------
        */
        document.getElementById('broadcastBtn').addEventListener('click', openBroadcastModal);

        function openBroadcastModal() {
            document.getElementById('broadcastModal').style.display = 'flex';
            loadBroadcastDrivers();
        }

        function closeBroadcastModal() {
            document.getElementById('broadcastModal').style.display = 'none';
            document.getElementById('broadcastMessage').value = '';
        }

        function loadBroadcastDrivers() {
            fetch("{{ route('chat.drivers') }}")
                .then(res => res.json())
                .then(res => {
                    let html = '';

                    res.data.forEach(driver => {
                        let image = driver.driver_photo ?
                            `/${driver.driver_photo}` :
                            'https://ui-avatars.com/api/?name=' + encodeURIComponent(driver.full_name);

                        html += `
                        <label class="driver-item">
                            <input type="checkbox" value="${driver.id}" class="broadcast-driver-checkbox">

                            <img src="${image}" alt="">

                            <div>
                                <div class="driver-item-name">
                                    ${escapeHtml(driver.full_name)}
                                </div>

                                <div class="driver-item-license">
                                    ${escapeHtml(driver.license_number ?? 'N/A')}
                                </div>
                            </div>
                        </label>
                    `;
                    });

                    document.getElementById('broadcastDriverList').innerHTML = html;
                });
        }

        document.getElementById('broadcastDriverSearch').addEventListener('keyup', function() {
            let value = this.value.toLowerCase();

            document.querySelectorAll('#broadcastDriverList .driver-item').forEach(item => {
                item.style.display = item.innerText.toLowerCase().includes(value) ? 'flex' : 'none';
            });
        });

        document.getElementById('sendBroadcastBtn').addEventListener('click', function() {
            let message = document.getElementById('broadcastMessage').value.trim();

            if (!message) {
                showToast('Please enter message', 'error');
                return;
            }

            let driverIds = [];

            document.querySelectorAll('.broadcast-driver-checkbox:checked').forEach(cb => {
                driverIds.push(cb.value);
            });

            if (driverIds.length === 0) {
                showToast('Please select drivers', 'error');
                return;
            }

            fetch("{{ route('chat.broadcast') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        driver_ids: driverIds,
                        message: message
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (!res.status) {
                        showToast('Failed to send broadcast', 'error');
                        return;
                    }

                    closeBroadcastModal();
                    loadChats();
                    showToast('Broadcast sent successfully');

                    if (activeChatId && res.messages) {
                        res.messages.forEach(msg => {
                            appendMessage(msg);
                        });
                    }
                });
        });

        /*
        |--------------------------------------------------------------------------
        | VOICE RECORDING
        |--------------------------------------------------------------------------
        */
        let mediaRecorder;
        let audioChunks = [];

        document.getElementById('voiceRecordBtn').addEventListener('click', async function() {
            if (!activeChatId) {
                showToast('Please open a chat first', 'error');
                return;
            }

            if (!mediaRecorder || mediaRecorder.state === 'inactive') {
                let stream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });

                let options = {};

                if (MediaRecorder.isTypeSupported('audio/webm;codecs=opus')) {
                    options.mimeType = 'audio/webm;codecs=opus';
                } else if (MediaRecorder.isTypeSupported('audio/webm')) {
                    options.mimeType = 'audio/webm';
                } else if (MediaRecorder.isTypeSupported('audio/mp4')) {
                    options.mimeType = 'audio/mp4';
                }

                mediaRecorder = new MediaRecorder(stream, options);
                audioChunks = [];

                mediaRecorder.ondataavailable = e => {
                    if (e.data.size > 0) {
                        audioChunks.push(e.data);
                    }
                };

                mediaRecorder.onstop = async () => {
                    let mimeType = mediaRecorder.mimeType || 'audio/webm';
                    let extension = mimeType.includes('mp4') ? 'mp4' : 'webm';

                    let audioBlob = new Blob(audioChunks, {
                        type: mimeType
                    });

                    sendVoiceMessage(audioBlob, extension);

                    stream.getTracks().forEach(track => track.stop());
                };

                mediaRecorder.start();
                this.innerHTML = '⏹️';

                return;
            }

            mediaRecorder.stop();
            this.innerHTML = '🎤';
        });

        async function sendVoiceMessage(audioBlob, extension = 'webm') {
            let formData = new FormData();

            formData.append('chat_id', activeChatId);

            formData.append(
                'voice',
                audioBlob,
                'voice-message.' + extension
            );

            formData.append('translate_to', 'it');

            try {
                let res = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                let data = await res.json();

                if (data.status) {
                    appendMessage(data.data);

                    let preview = document.getElementById('chat-last-message-' + activeChatId);

                    if (preview) {
                        preview.innerText = '🎤 Voice message';
                    }
                } else {
                    showToast('Voice message failed', 'error');
                }

            } catch (error) {
                console.log(error);
                showToast('Voice message failed', 'error');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | INIT
        |--------------------------------------------------------------------------
        */
        loadChats();
    </script>

@endsection
