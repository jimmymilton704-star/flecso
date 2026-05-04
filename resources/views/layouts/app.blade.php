<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>

<body class="@yield('body-class')">
    <div class="app" id="app">
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Sidebar -->
        @include('partials.sidebar')

        <!-- Main -->
        <main class="main">

            <!-- Header -->
            @include('partials.header')

            <!-- Page Content -->
            @yield('content')

        </main>
    </div>

    <div class="modal" id="qrModal" aria-hidden="true">
        <div class="modal__backdrop" data-close></div>
        <div class="modal__dialog">
            <header class="modal__header">
                <div>
                    <h3 id="qrTitle">QR Code</h3>
                    <p id="qrSubtitle" class="muted">Scan to access asset details</p>
                </div>
                <button class="icon-btn" data-close aria-label="Close"><svg viewBox="0 0 24 24" width="18" height="18"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg></button>
            </header>
            <div class="modal__body modal__body--center">
                <div class="qr-frame">
                    <div id="qrCanvas" style="width:240px;height:240px"></div>
                </div>
                <div id="qrData" class="qr-data"></div>
            </div>
            <footer class="modal__footer">
                <button class="btn btn--ghost" id="qrPrint"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path
                            d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z" />
                    </svg> Print</button>
                <button class="btn btn--primary" id="qrDownload"><svg viewBox="0 0 24 24" width="16" height="16"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
                    </svg> Download PNG</button>
            </footer>
        </div>
    </div>

    <!-- Drawer -->
    <div class="drawer" id="drawer" aria-hidden="true">
        <div class="drawer__backdrop" data-close></div>
        <aside class="drawer__panel" id="drawerPanel">
            <header class="drawer__header">
                <div>
                    <h3 id="drawerTitle">Add New</h3>
                    <p id="drawerSubtitle" class="muted">Fill in the details below</p>
                </div>
                <button class="icon-btn" data-close aria-label="Close"><svg viewBox="0 0 24 24" width="18" height="18"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg></button>
            </header>
            <div class="drawer__body" id="drawerBody"></div>
            <footer class="drawer__footer">
                <button class="btn btn--ghost" data-close>Cancel</button>
                <button class="btn btn--primary" id="drawerSubmit">Save Record</button>
            </footer>
        </aside>
    </div>

    <div class="toast" id="toast" aria-live="polite"></div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    @include('partials.footer')

</body>

</html>