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
    </style>

    <section class="page">
        <div class="inbox">
            <!-- LEFT: conversation list -->
            <aside class="inbox-list">
                <header class="inbox-list__head">
                    <h2>Inbox</h2>
                    <button class="icon-btn" id="newConv" aria-label="New conversation" title="New conversation">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14M5 12h14"></path>
                        </svg>
                    </button>
                </header>

                <div class="inbox-search">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-3-3"></path>
                    </svg>
                    <input id="convSearch" type="text" placeholder="Search conversations…">
                </div>

                <div class="inbox-filters" id="convFilters">
                    <button class="active">All</button>
                    <button>Unread</button>
                    <button>Pinned</button>
                </div>

                <div class="conv-list" id="convList">
                </div>
            </aside>

            <!-- CENTRE: chat -->
            <main class="inbox-chat">
                <header class="chat-head" id="chatHead">

                </header>
                <div class="chat-body" id="chatBody">

                </div>
                <div class="chat-quick">
                    <button data-quick="On my way 👍">On my way 👍</button>
                    <button data-quick="ETA in 30 minutes.">ETA in 30 minutes</button>
                    <button data-quick="Confirmed, thanks.">Confirmed, thanks</button>
                    <button data-quick="Need a swap, can you help?">Need a swap</button>
                    <button data-quick="At the depot, all clear.">At the depot</button>
                </div>
                <footer class="chat-composer">
                    <button class="chat-composer__attach" id="chatAttach" aria-label="Attach">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48">
                            </path>
                        </svg>
                    </button>
                    <input id="chatInput" class="chat-composer__input" type="text" placeholder="Type a message…"
                        autocomplete="off">

                    <button class="chat-send" id="chatSend" disabled="">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m22 2-7 20-4-9-9-4Z"></path>
                            <path d="M22 2 11 13"></path>
                        </svg>
                        <span>Send</span>
                    </button>
                </footer>
            </main>

            <!-- RIGHT: details -->
            <aside class="inbox-details" id="chatDetails">

            </aside>
        </div>
    </section>


    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

    <script>
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
            key: '{{ env("PUSHER_APP_KEY") }}',
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: true,
            authEndpoint: '/broadcasting/auth',
            withCredentials: true,
        });

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
                    console.log(res.data);
                    res.data.forEach(chat => {

                        let image =
                            chat.driver?.driver_photo ?
                                `/${chat.driver.driver_photo}` :
                                'https://ui-avatars.com/api/?name=' + encodeURIComponent(chat.driver?.full_name ?? 'Driver');

                        let unreadBadge = '';


                        if (chat.unread_count > 0) {
                            unreadBadge = `
                                        <span style="
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

                        html += `
                                    <div class="conv-item"
                                         id="chat-item-${chat.id}"
                                         onclick="openChat(${chat.id})">

                                        <div class="conv-avatar">
                                            <img src="${image}" alt="">
                                        </div>

                                        <div class="conv-info">

                                            <div class="conv-name">
                                                 ${chat.driver?.full_name ?? 'Driver'}
                                            </div>

                                            <div class="conv-preview"
                                                 id="chat-last-message-${chat.id}">
                                                 ${chat.last_message ?? ''}
                                            </div>

                                        </div>

                                        <div class="conv-meta">

                                            <span class="conv-time">
                                                ${formatTime(chat.last_message_at ?? '')}
                                            </span>


                                        </div>

                                    </div>
                                `;
                    });

                    document.getElementById('convList').innerHTML = html;

                    /*
                    |--------------------------------------------------------------------------
                    | AUTO OPEN FIRST CHAT
                    |--------------------------------------------------------------------------
                    */
                    // if (res.data.length > 0 && !activeChatId) {
                    //     openChat(res.data[0].id);
                    // }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | OPEN CHAT
        |--------------------------------------------------------------------------
        */
        function openChat(chatId) {

            activeChatId = chatId;

            /*
            |--------------------------------------------------------------------------
            | ACTIVE UI
            |--------------------------------------------------------------------------
            */
            document.querySelectorAll('.conv-item').forEach(item => {
                item.classList.remove('active');
            });

            let activeItem = document.getElementById('chat-item-' + chatId);

            if (activeItem) {
                activeItem.classList.add('active');
            }

            /*
            |--------------------------------------------------------------------------
            | GET CHAT OBJECT
            |--------------------------------------------------------------------------
            */
            fetch(`{{ route('chat.messages') }}?chat_id=${chatId}`)
                .then(res => res.json())
                .then(res => {

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE HEADER + RIGHT SIDEBAR
                    |--------------------------------------------------------------------------
                    */
                    if (res.chat) {
                        updateChatDetails(res.chat);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | LOAD MESSAGES
                    |--------------------------------------------------------------------------
                    */
                    let html = '';

                    res.data.forEach(msg => {
                        html += messageHtml(msg);
                    });

                    document.getElementById('chatBody').innerHTML = html;

                    scrollChatBottom();

                    /*
                    |--------------------------------------------------------------------------
                    | MARK AS SEEN
                    |--------------------------------------------------------------------------
                    */
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

                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE UNREAD BADGE
                    |--------------------------------------------------------------------------
                    */
                    let unread = activeItem?.querySelector('.unread-badge');

                    if (unread) {
                        unread.remove();
                    }
                });

            /*
            |--------------------------------------------------------------------------
            | REMOVE OLD CHANNEL
            |--------------------------------------------------------------------------
            */
            if (activeChannel) {
                window.Echo.leave(activeChannel);
            }

            /*
            |--------------------------------------------------------------------------
            | SUBSCRIBE CHANNEL
            |--------------------------------------------------------------------------
            */
            activeChannel = 'chat.' + chatId;

            window.Echo.private(activeChannel)
                .listen('.message.sent', (e) => {

                    if (e.sender_type === 'admin') {
                        return;
                    }

                    appendMessage(e);

                    let preview = document.getElementById(
                        'chat-last-message-' + chatId
                    );

                    if (preview) {
                        preview.innerText = e.message ?? 'File';
                    }
                });
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE CHAT HEADER + SIDEBAR
        |--------------------------------------------------------------------------
        */
        function updateChatDetails(chat) {

            let driver = chat.driver || {};

            let truck = driver.truck || {};
            let container = driver.container || {};

            let image =
                driver?.driver_photo
                    ? `/${driver.driver_photo}`
                    : 'https://ui-avatars.com/api/?name=' +
                    encodeURIComponent(driver?.full_name ?? 'Driver');

            /*
            |--------------------------------------------------------------------------
            | HEADER
            |--------------------------------------------------------------------------
            */
            document.getElementById('chatHead').innerHTML = `

            <div class="chat-head__avatar">
                <img src="${image}" alt="">
            </div>

            <div>
                <div class="chat-head__name">
                    ${driver?.full_name ?? 'Driver'}
                </div>

                <div class="chat-head__status">
                    Driver : ${driver?.license_number ?? 'N/A'}
                </div>
            </div>

            <div class="chat-head__actions">

                <a title="Call" href="tel:${driver?.phone ?? 'N/A'}">
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

            /*
            |--------------------------------------------------------------------------
            | RIGHT SIDEBAR
            |--------------------------------------------------------------------------
            */
            document.getElementById('chatDetails').innerHTML = `

            <div class="chat-profile">

                <img class="chat-profile__avatar"
                    src="${image}"
                    alt="">

                <h3>${driver?.full_name ?? 'Driver'}</h3>

                <p>Driver: ${driver?.license_number ?? 'N/A'}</p>

            </div>

            <!-- DRIVER INFO -->
            <div class="details-section">

                <h4>Driver Info</h4>

                <div class="details-row">
                    <span class="k">Date of Birth</span>
                    <span class="v">${driver?.date_of_birth ?? 'N/A'}</span>
                </div>

                <div class="details-row">
                    <span class="k">Phone</span>
                    <span class="v">${driver?.phone ?? 'N/A'}</span>
                </div>

                <div class="details-row">
                    <span class="k">Email</span>
                    <span class="v">${driver?.email ?? 'N/A'}</span>
                </div>

                <div class="details-row">
                    <span class="k">Status</span>
                    <span class="v">${driver?.status ?? 'Active'}</span>
                </div>

            </div>

            <!-- TRUCK INFO -->
            <div class="details-section">

                <h4>Truck Details</h4>

                <div class="details-row">
                    <span class="k">Truck No</span>
                    <span class="v">${truck?.truck_number ?? 'N/A'}</span>
                </div>

                <div class="details-row">
                    <span class="k">Plate</span>
                    <span class="v">${truck?.license_plate_number ?? 'N/A'}</span>
                </div>

                <div class="details-row">
                    <span class="k">Type</span>
                    <span class="v">${truck?.truck_type_category ?? 'N/A'}</span>
                </div>

                 <div class="details-row">
                    <span class="k">Capacity(Tons)</span>
                    <span class="v">${truck?.capacity_tons ?? 'N/A'}</span>
                </div>

            </div>

            <!-- CHAT INFO -->
            <div class="details-section">

                <h4>Conversation</h4>

                <div class="details-row">
                    <span class="k">Chat ID</span>
                    <span class="v">#${chat.id}</span>
                </div>

                <div class="details-row">
                    <span class="k">Last Message</span>
                    <span class="v">${chat.last_message ?? 'N/A'}</span>
                </div>

                <div class="details-row">
                    <span class="k">Updated</span>
                    <span class="v">${chat.last_message_at ?? 'N/A'}</span>
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

            let fileHtml = '';

            /*
            |--------------------------------------------------------------------------
            | FILES
            |--------------------------------------------------------------------------
            */
            if (msg.file) {

                if (msg.file_type === 'image') {

                    fileHtml = `
                                <div style="margin-top:8px">
                                    <img src="${msg.file}"
                                         style="max-width:220px;border-radius:14px">
                                </div>
                            `;
                } else {

                    fileHtml = `
                                <div style="margin-top:8px">
                                    <a href="${msg.file}"
                                       target="_blank"
                                       style="
                                           color:white;
                                           text-decoration:underline;
                                           font-size:13px;
                                       ">
                                       📎 Download File
                                    </a>
                                </div>
                            `;
                }
            }

            return `
                        <div class="chat-msg ${isMe ? 'from-me' : 'from-driver'}">

                            ${msg.message ?? ''}

                            ${fileHtml}

                            <div class="chat-msg__time">
                                ${formatTime(msg.created_at)}
                            </div>

                        </div>
                    `;
        }

        /*
        |--------------------------------------------------------------------------
        | APPEND MESSAGE
        |--------------------------------------------------------------------------
        */
        function appendMessage(msg) {

            document.getElementById('chatBody').innerHTML += messageHtml(msg);

            scrollChatBottom();
        }

        /*
        |--------------------------------------------------------------------------
        | SEND MESSAGE
        |--------------------------------------------------------------------------
        */
        function sendMessage() {

            let input = document.getElementById('chatInput');

            let message = input.value.trim();

            if (!message || !activeChatId) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | TEMP MESSAGE OBJECT
            |--------------------------------------------------------------------------
            */
            let tempMessage = {
                sender_type: 'admin',
                message: message,
                created_at: new Date().toISOString()
            };

            /*
            |--------------------------------------------------------------------------
            | SHOW MESSAGE INSTANTLY
            |--------------------------------------------------------------------------
            */
            appendMessage(tempMessage);

            /*
            |--------------------------------------------------------------------------
            | UPDATE CHAT PREVIEW
            |--------------------------------------------------------------------------
            */
            let preview = document.getElementById(
                'chat-last-message-' + activeChatId
            );

            if (preview) {
                preview.innerText = message;
            }

            /*
            |--------------------------------------------------------------------------
            | CLEAR INPUT INSTANTLY
            |--------------------------------------------------------------------------
            */
            input.value = '';

            document.getElementById('chatSend').disabled = true;

            /*
            |--------------------------------------------------------------------------
            | SEND IN BACKGROUND
            |--------------------------------------------------------------------------
            */
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

                    /*
                    |--------------------------------------------------------------------------
                    | OPTIONAL ERROR HANDLE
                    |--------------------------------------------------------------------------
                    */
                    if (!res.status) {
                        alert('Message failed');
                    }

                })
                .catch(err => {
                    console.log(err);
                });
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT TIME
        |--------------------------------------------------------------------------
        */
        function formatTime(date) {

            let d = new Date(date);

            return d.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        /*
        |--------------------------------------------------------------------------
        | SCROLL CHAT
        |--------------------------------------------------------------------------
        */
        function scrollChatBottom() {

            let body = document.getElementById('chatBody');

            body.scrollTop = body.scrollHeight;
        }

        /*
        |--------------------------------------------------------------------------
        | SEND BUTTON ENABLE
        |--------------------------------------------------------------------------
        */
        document.getElementById('chatInput')
            .addEventListener('input', function () {

                document.getElementById('chatSend').disabled =
                    this.value.trim() === '';
            });

        /*
        |--------------------------------------------------------------------------
        | ENTER SEND
        |--------------------------------------------------------------------------
        */
        document.getElementById('chatInput')
            .addEventListener('keypress', function (e) {

                if (e.key === 'Enter') {
                    e.preventDefault();
                    sendMessage();
                }
            });

        /*
        |--------------------------------------------------------------------------
        | SEND CLICK
        |--------------------------------------------------------------------------
        */
        document.getElementById('chatSend')
            .addEventListener('click', sendMessage);

        /*
        |--------------------------------------------------------------------------
        | QUICK REPLIES
        |--------------------------------------------------------------------------
        */
        document.querySelectorAll('.chat-quick button')
            .forEach(btn => {

                btn.addEventListener('click', function () {

                    document.getElementById('chatInput').value =
                        this.dataset.quick;

                    document.getElementById('chatSend').disabled = false;
                });
            });

        /*
        |--------------------------------------------------------------------------
        | SEARCH CHAT
        |--------------------------------------------------------------------------
        */
        document.getElementById('convSearch')
            .addEventListener('keyup', function () {

                let value = this.value.toLowerCase();

                document.querySelectorAll('.conv-item')
                    .forEach(item => {

                        let text = item.innerText.toLowerCase();

                        item.style.display =
                            text.includes(value) ? 'flex' : 'none';
                    });
            });

        /*
        |--------------------------------------------------------------------------
        | INIT
        |--------------------------------------------------------------------------
        */
        loadChats();
    </script>
@endsection