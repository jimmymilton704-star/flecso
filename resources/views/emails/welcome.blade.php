<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Flecso — Welcome email</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700&family=Geist+Mono:wght@400;500;600&display=swap"
        rel="stylesheet" />

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #F2EDE5;
            font-family: 'Geist', Arial, sans-serif;
            color: #171615;
            -webkit-font-smoothing: antialiased;
        }

        img {
            border: 0;
            display: block;
            max-width: 100%;
        }

        a {
            text-decoration: none;
        }

        .email-viewport {
            width: 100%;
            background: #F2EDE5;
        }

        .preheader {
            display: none !important;
            visibility: hidden;
            opacity: 0;
            color: transparent;
            height: 0;
            width: 0;
            overflow: hidden;
        }

        .outer-table {
            width: 100%;
            border-collapse: collapse;
            background: #F2EDE5;
        }

        .outer-cell {
            padding: 32px 16px;
        }

        .card {
            width: 640px;
            max-width: 100%;
            margin: 0 auto;
            background: #FFFFFF;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid #E8E3DC;
            box-shadow:
                0 1px 0 rgba(23, 22, 21, 0.04),
                0 24px 48px -24px rgba(60, 40, 15, 0.18);
        }

        /* HEADER */

        .header-cell {
            padding: 22px 36px;
            border-bottom: 1px solid #E8E3DC;
            background: #FFFFFF;
        }

        .header-row {
            width: 100%;
        }

        .logo {
            height: 26px;
            width: auto;
        }

        .header-meta {
            font-size: 11px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #76706A;
            font-weight: 600;
            white-space: nowrap;
        }

        .header-meta-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #EF6A1A;
            display: inline-block;
            margin-right: 8px;
            box-shadow: 0 0 0 4px rgba(239, 106, 26, 0.13);
        }

        /* HERO */

        .hero-cell {
            padding: 44px 36px 36px;
            background: linear-gradient(180deg, #FAF6EF 0%, #FFFFFF 100%);
            border-bottom: 1px solid #E8E3DC;
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: 0.22em;
            color: #EF6A1A;
            font-weight: 700;
            margin: 0 0 16px;
        }

        .hero-title {
            font-size: 40px;
            line-height: 1.05;
            letter-spacing: -0.02em;
            color: #171615;
            font-weight: 700;
            margin: 0 0 16px;
        }

        .hero-lede {
            font-size: 16px;
            line-height: 1.55;
            color: #3D3A36;
            margin: 0;
            max-width: 500px;
        }

        /* BODY */

        .body-cell {
            padding: 36px 40px 16px;
        }

        .paragraph {
            font-size: 15px;
            line-height: 1.65;
            color: #3D3A36;
            margin: 0 0 18px;
        }

        .paragraph-strong {
            font-size: 15px;
            line-height: 1.6;
            color: #171615;
            font-weight: 600;
            margin: 8px 0 24px;
            letter-spacing: -0.005em;
        }

        /* CTA */

        .login-block {
            background: #FAF6EF;
            border: 1px solid #E8E3DC;
            border-radius: 14px;
            padding: 22px 24px;
            margin: 8px 0 28px;
        }

        .cta-primary {
            display: inline-block;
            background: #EF6A1A;
            color: #FFFFFF !important;
            font-weight: 600;
            font-size: 15px;
            padding: 13px 22px;
            border-radius: 10px;
            letter-spacing: -0.005em;
            box-shadow:
                0 1px 0 #C45011,
                0 8px 18px -8px rgba(239, 106, 26, 0.5);
        }

        /* SIGNOFF */

        .signoff {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #E8E3DC;
        }

        .signoff-line {
            font-size: 14.5px;
            color: #3D3A36;
            margin-bottom: 4px;
        }

        .signoff-team {
            font-size: 15px;
            font-weight: 600;
            color: #171615;
            letter-spacing: -0.005em;
        }

        /* FOOTER */

        .footer-cell {
            padding: 32px 36px;
            background: #111110;
            color: #9D958A;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-brand-cell {
            vertical-align: top;
            padding-right: 24px;
            width: 55%;
        }

        .footer-contact-cell {
            vertical-align: top;
            text-align: right;
        }

        .footer-logo-row {
            color: #FAF6EF;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .footer-mark {
            color: #EF6A1A;
            font-size: 14px;
        }

        .footer-wordmark {
            letter-spacing: 0.3em;
            font-size: 13px;
        }

        .footer-tagline {
            font-size: 13px;
            color: #C9C1B5;
            line-height: 1.5;
        }

        .footer-col-label {
            font-size: 10px;
            letter-spacing: 0.22em;
            color: #7E766C;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .footer-contact-link {
            display: block;
            font-size: 13px;
            color: #FAF6EF !important;
            margin-bottom: 2px;
        }

        .footer-hours {
            font-size: 11.5px;
            color: #7E766C;
            margin-top: 4px;
        }

        .footer-divider {
            height: 1px;
            background: #2A2724;
            margin: 22px 0 18px;
        }

        .footer-links {
            font-size: 12px;
        }

        .footer-link {
            color: #C9C1B5 !important;
            font-weight: 500;
        }

        .footer-dot {
            color: #5C544A;
            padding: 0 8px;
        }

        .social-cell {
            vertical-align: middle;
            text-align: right;
            white-space: nowrap;
        }

        .social-btn {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #2A2724;
            background: #1A1816;
            color: #FAF6EF !important;
            font-weight: 700;
            font-size: 12px;
            margin-left: 6px;
        }

        .footer-legal {
            font-size: 11px;
            color: #7E766C;
            line-height: 1.6;
            margin-top: 18px;
        }

        /* MOBILE */

        @media (max-width:640px) {

            .outer-cell {
                padding: 20px 12px;
            }

            .card {
                width: 100% !important;
                border-radius: 14px;
            }

            .header-cell {
                padding: 18px 22px;
            }

            .hero-cell {
                padding: 32px 22px 24px;
            }

            .hero-title {
                font-size: 30px;
            }

            .body-cell {
                padding: 26px 22px 12px;
            }

            .footer-cell {
                padding: 26px 22px;
            }

            .footer-brand-cell,
            .footer-contact-cell {
                display: block;
                width: 100% !important;
                text-align: left !important;
                padding: 0 0 16px;
            }

            .social-cell {
                text-align: left;
                padding-top: 10px;
            }

            .social-btn {
                margin-left: 0;
                margin-right: 6px;
            }
        }
    </style>
</head>

<body>

    <div class="email-viewport">

        <div class="preheader">
            Welcome to Flecso — your account is ready.
        </div>

        <table class="outer-table" role="presentation">
            <tr>
                <td class="outer-cell">

                    <table class="card" role="presentation">

                        <!-- HEADER -->
                        <tr>
                            <td class="header-cell">

                                <table class="header-row">
                                    <tr>

                                        <td align="left">
                                            <img src="https://www.flecso.app/images/logo.png" alt="Flecso Logo" class="logo">
                                        </td>

                                        <td align="right" class="header-meta">
                                            <span class="header-meta-dot"></span>
                                            Account ready
                                        </td>

                                    </tr>
                                </table>

                            </td>
                        </tr>

                        <!-- HERO -->
                        <tr>
                            <td class="hero-cell">

                                <div class="eyebrow">
                                    WELCOME ABOARD
                                </div>

                                <h1 class="hero-title">
                                    Welcome to Flecso!
                                </h1>

                                <p class="hero-lede">
                                    We are excited to have you onboard and become part of our
                                    growing digital logistics ecosystem.
                                </p>

                            </td>
                        </tr>

                        <!-- BODY -->
                        <tr>
                            <td class="body-cell">

                                <p class="paragraph">
                                    Flecso is designed to simplify and modernize container
                                    tracking, trucking operations, fleet management, and driver
                                    coordination — all from one centralized platform. Whether you
                                    are managing fleets, monitoring deliveries, or handling driver
                                    operations, Flecso provides the tools you need to streamline
                                    your workflow efficiently.
                                </p>

                                <p class="paragraph">
                                    Our mission is to help logistics companies reduce operational
                                    complexity, improve visibility, and increase delivery
                                    efficiency through smart automation and real-time management
                                    tools.
                                </p>

                                <p class="paragraph-strong">
                                    Your account has been successfully created and is now ready
                                    to use.
                                </p>

                                <div class="login-block">

                                    <a href="{{ route('login') }}" class="cta-primary">
                                        Go to login →
                                    </a>

                                </div>

                                <p class="paragraph">
                                    If you have any questions or require assistance, our support
                                    team is always here to help.
                                </p>

                                <p class="paragraph">
                                    Thank you for choosing Flecso.
                                </p>

                                <div class="signoff">
                                    <div class="signoff-line">Best Regards,</div>
                                    <div class="signoff-team">The Flecso Team</div>
                                </div>

                            </td>
                        </tr>

                        <!-- FOOTER -->
                        <tr>
                            <td class="footer-cell">

                                <table class="footer-table">
                                    <tr>

                                        <td class="footer-brand-cell">

                                            <div class="footer-logo-row">
                                                <span class="footer-wordmark">FLECSO</span>
                                            </div>

                                            <div class="footer-tagline">
                                                Trucking management, built for fleets<br>
                                                that don't sit still.
                                            </div>

                                        </td>

                                        <td class="footer-contact-cell">

                                            <div class="footer-col-label">
                                                SUPPORT
                                            </div>

                                            <a href="mailto:support@flecso.com" class="footer-contact-link">
                                                support@flecso.com
                                            </a>

                                           

                                        </td>

                                    </tr>
                                </table>

                                <div class="footer-divider"></div>

                                <table class="footer-table">
                                    <tr>

                                        <td style="vertical-align:middle;">

                                            <div class="footer-links">
                                                <a href="#" class="footer-link">Help center</a>
                                                <span class="footer-dot">·</span>

                                                <a href="#" class="footer-link">Status</a>
                                                <span class="footer-dot">·</span>

                                                <a href="#" class="footer-link">Privacy</a>
                                                <span class="footer-dot">·</span>

                                                <a href="#" class="footer-link">Terms</a>
                                            </div>

                                        </td>

                                        

                                    </tr>
                                </table>

                                <div class="footer-legal">
                                    © 2026 Flecso, Inc. All rights reserved.
                                   
                                </div>

                            </td>
                        </tr>

                    </table>

                </td>
            </tr>
        </table>

    </div>

</body>

</html>