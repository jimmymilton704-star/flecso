@php
    $user = user();
    $alerts = adminAlerts();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
</head>

<body class="@yield('body-class')">
    <style>
        .goog-te-banner-frame {
            display: none !important;
        }

        body {
            top: 0 !important;
        }

        .goog-logo-link {
            display: none !important;
        }

        .goog-te-gadget {
            font-size: 0 !important;
        }

        .VIpgJd-ZVi9od-ORHb-OEVmcd {
            display: none !important;
        }

        .VIpgJd-yAWNEb-hvhgNd {
            display: none !important;
        }

        .lang-switch.active {
            background: var(--orange-50);
            border-left: 3px solid var(--orange-500);
            font-weight: 600;
        }
    </style>
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
                <button class="icon-btn" data-close aria-label="Close"><svg viewBox="0 0 24 24" width="18"
                        height="18" fill="none" stroke="currentColor" stroke-width="2">
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
                <button class="btn btn--ghost" id="qrPrint"><svg viewBox="0 0 24 24" width="16" height="16"
                        fill="none" stroke="currentColor" stroke-width="2">
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
                <button class="icon-btn" data-close aria-label="Close"><svg viewBox="0 0 24 24" width="18"
                        height="18" fill="none" stroke="currentColor" stroke-width="2">
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
    <div id="google_translate_element" style="display:none;"></div>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script>
        document.addEventListener("click", function(e) {

            // toggle language popup
            if (e.target.closest("#langBtn")) {
                document.getElementById("langPop")?.classList.toggle("open");
            }

            // close on outside click
            if (!e.target.closest(".notify-wrap")) {
                document.getElementById("langPop")?.classList.remove("open");
            }

            // language switch
            const btn = e.target.closest(".lang-switch");
            if (!btn) return;
            document.querySelectorAll(".lang-switch").forEach(el => {
                el.classList.remove("active");
            });

            // ✅ add new active
            btn.classList.add("active")

            const lang = btn.dataset.lang;

            localStorage.setItem("lang", lang);
            setGoogleLanguage(lang);
        });

        function setGoogleLanguage(lang) {
            const combo = document.querySelector(".goog-te-combo");

            if (!combo) {
                console.warn("Google Translate not loaded yet");
                return;
            }

            combo.value = lang;
            combo.dispatchEvent(new Event("change"));
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const lang = localStorage.getItem("lang") || "en";

            document.querySelectorAll(".lang-switch").forEach(el => {
                el.classList.remove("active");
            });

            const activeBtn = document.querySelector(`.lang-switch[data-lang="${lang}"]`);

            if (activeBtn) {
                activeBtn.classList.add("active");
            }

            setTimeout(() => {
                setGoogleLanguage(lang);
            }, 800);
        });
    </script>


    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    @include('partials.footer')

</body>

</html>
