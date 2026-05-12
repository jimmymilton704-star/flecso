<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flecso — Verification Code</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #F2EDE5;
            font-family: 'Geist', Arial, sans-serif;
            color: #171615;
            padding: 30px 15px;
        }

        table {
            border-collapse: collapse;
        }

        img {
            border: 0;
            display: block;
            max-width: 100%;
        }

        .card {
            width: 640px;
            max-width: 100%;
            margin: auto;
            background: #FFFFFF;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #E8E3DC;
            box-shadow:
                0 1px 0 rgba(23, 22, 21, 0.04),
                0 24px 48px -24px rgba(60, 40, 15, 0.18);
        }

        /* HEADER */
        .header {
            padding: 22px 36px;
            border-bottom: 1px solid #E8E3DC;
            background: #FFFFFF;
        }

        .header-table {
            width: 100%;
        }

        .logo {
            width: 120px;
            height: auto;
        }

        .header-meta {
            font-size: 11px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #76706A;
            font-weight: 600;
            text-align: right;
        }

        .header-meta span {
            color: #EF6A1A;
            font-size: 14px;
            margin-right: 5px;
        }

        /* BODY */
        .body {
            padding: 44px 36px 36px;
            background: linear-gradient(180deg, #FAF6EF 0%, #FFFFFF 100%);
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: 0.22em;
            color: #EF6A1A;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .title {
            font-size: 34px;
            line-height: 1.08;
            letter-spacing: -0.02em;
            color: #171615;
            font-weight: 700;
            margin-bottom: 14px;
        }

        .lede {
            font-size: 15px;
            line-height: 1.6;
            color: #3D3A36;
            margin-bottom: 30px;
        }

        /* OTP FRAME */
        .code-frame {
            background: #FAF6EF;
            border: 1px solid #E8E3DC;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
        }

        .code-top {
            margin-bottom: 20px;
            text-align: left;
        }

        .code-label {
            font-size: 10px;
            letter-spacing: 0.22em;
            color: #171615;
            font-weight: 700;
        }

        .code-label-dot {
            width: 7px;
            height: 7px;
            background: #EF6A1A;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            box-shadow: 0 0 0 4px rgba(239, 106, 26, 0.12);
        }

        .otp-box {
            display: inline-block;
            min-width: 240px;
            padding: 18px 28px;
            background: #FFFFFF;
            border: 1px solid #E8E3DC;
            border-radius: 14px;
            font-family: 'Geist Mono', monospace;
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 10px;
            color: #171615;
            margin-bottom: 18px;
            box-shadow:
                0 1px 0 rgba(23,22,21,0.04),
                inset 0 -2px 0 rgba(23,22,21,0.04);
        }

        .meta {
            margin-top: 16px;
            font-size: 12px;
            color: #76706A;
        }

        /* FOOTER */
        .footer {
            background: #111110;
            padding: 32px 36px;
            color: #9D958A;
        }

        .footer-table {
            width: 100%;
        }

        .footer-left {
            width: 55%;
            vertical-align: top;
        }

        .footer-right {
            vertical-align: top;
            text-align: right;
        }

        .footer-logo {
            color: #FAF6EF;
            font-size: 13px;
            letter-spacing: 0.3em;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .footer-logo span {
            color: #EF6A1A;
            margin-right: 4px;
        }

        .footer-tagline {
            font-size: 13px;
            line-height: 1.6;
            color: #C9C1B5;
        }

        .footer-label {
            font-size: 10px;
            letter-spacing: 0.22em;
            color: #7E766C;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .footer-link {
            display: block;
            color: #FAF6EF !important;
            text-decoration: none;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .footer-hours {
            font-size: 11px;
            color: #7E766C;
        }

        .divider {
            height: 1px;
            background: #2A2724;
            margin: 24px 0 20px;
        }

        .footer-bottom {
            width: 100%;
        }

        .footer-links {
            font-size: 12px;
            color: #C9C1B5;
        }

        .footer-links a {
            color: #C9C1B5 !important;
            text-decoration: none;
            margin: 0 5px;
        }

        .social {
            text-align: right;
        }

        .social a {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 8px;
            border: 1px solid #2A2724;
            background: #1A1816;
            color: #FAF6EF !important;
            text-decoration: none;
            text-align: center;
            font-size: 12px;
            margin-left: 6px;
        }

        .legal {
            margin-top: 18px;
            font-size: 11px;
            line-height: 1.6;
            color: #7E766C;
        }

        /* RESPONSIVE */
        @media only screen and (max-width: 640px) {

            .header,
            .body,
            .footer {
                padding: 24px 20px !important;
            }

            .title {
                font-size: 28px !important;
            }

            .otp-box {
                font-size: 30px !important;
                letter-spacing: 6px !important;
                min-width: auto !important;
                width: 100%;
            }

            .footer-left,
            .footer-right {
                display: block;
                width: 100% !important;
                text-align: left !important;
                padding-bottom: 20px;
            }

            .social {
                text-align: left !important;
                margin-top: 10px;
            }
        }
    </style>
</head>

<body>

    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center">

                <table class="card" role="presentation" cellpadding="0" cellspacing="0">

                    <!-- HEADER -->
                    <tr>
                        <td class="header">

                            <table class="header-table">
                                <tr>

                                    <td align="left">

                                        {{-- IMPORTANT --}}
                                        {{-- Use url() instead of asset() for emails --}}
                                        <img src="{{ url('images/logo.png') }}"
                                            alt="Flecso Logo"
                                            class="logo">

                                    </td>

                                    <td class="header-meta">
                                        <span>⌬</span>
                                        Secure sign-in
                                    </td>

                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td class="body">

                            <div class="eyebrow">
                                ONE-TIME PASSCODE
                            </div>

                            <div class="title">
                                Confirm it's you<br>
                                before we hit the road.
                            </div>

                            <div class="lede">
                                Enter this six-digit verification code in Flecso
                                to complete your secure sign in.
                            </div>

                            <!-- OTP FRAME -->
                            <div class="code-frame">

                                <div class="code-top">
                                    <span class="code-label-dot"></span>
                                    <span class="code-label">
                                        VERIFICATION CODE
                                    </span>
                                </div>

                                <div class="otp-box">
                                    {{ $otp }}
                                </div>

                                <div class="meta">
                                    Single use · Expires in 10 minutes
                                </div>

                            </div>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td class="footer">

                            <table class="footer-table">
                                <tr>

                                    <td class="footer-left">

                                        <div class="footer-logo">
                                            <span>▶</span>FLECSO
                                        </div>

                                        <div class="footer-tagline">
                                            Trucking management, built for fleets<br>
                                            that don't sit still.
                                        </div>

                                    </td>

                                    <td class="footer-right">

                                        <div class="footer-label">
                                            SUPPORT
                                        </div>

                                        <a href="mailto:support@flecso.com" class="footer-link">
                                            support@flecso.com
                                        </a>

                                        <a href="tel:+18883540119" class="footer-link">
                                            +1 (888) 354-0119
                                        </a>

                                        <div class="footer-hours">
                                            Mon–Fri, 6am–8pm CT
                                        </div>

                                    </td>

                                </tr>
                            </table>

                            <div class="divider"></div>

                            <table class="footer-bottom">
                                <tr>

                                    <td class="footer-links">
                                        <a href="#">Help center</a> ·
                                        <a href="#">Status</a> ·
                                        <a href="#">Privacy</a> ·
                                        <a href="#">Terms</a>
                                    </td>

                                    <td class="social">
                                        <a href="#">in</a>
                                        <a href="#">𝕏</a>
                                        <a href="#">▶</a>
                                    </td>

                                </tr>
                            </table>

                            <div class="legal">
                                © {{ date('Y') }} Flecso, Inc. All rights reserved.
                                <br>
                                This is an automated security message — please don't reply.
                            </div>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>