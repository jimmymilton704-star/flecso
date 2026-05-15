<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Your Flecso Account</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f6f7fb;
            color: #1f2937;
        }

        .delete-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .delete-card {
            width: 100%;
            max-width: 620px;
            background: #ffffff;
            border-radius: 18px;
            padding: 38px;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.08);
            border: 1px solid #eeeeee;
        }

        .delete-logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .delete-logo img {
            max-width: 150px;
            height: auto;
        }

        .delete-title {
            margin: 0;
            text-align: center;
            font-size: 30px;
            line-height: 1.25;
            font-weight: 800;
            color: #111827;
        }

        .delete-subtitle {
            margin: 14px auto 24px;
            max-width: 500px;
            text-align: center;
            font-size: 15px;
            line-height: 1.6;
            color: #6b7280;
        }

        .warning-box {
            background: #fff4ee;
            border: 1px solid #ffd6c2;
            color: #b64010;
            border-radius: 14px;
            padding: 14px 16px;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 28px;
        }

        .section-title {
            margin: 0 0 14px;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .steps {
            margin: 0 0 28px;
            padding-left: 22px;
            color: #374151;
        }

        .steps li {
            margin-bottom: 10px;
            font-size: 15px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }

        .form-input {
            width: 100%;
            height: 48px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 0 14px;
            font-size: 15px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            border-color: #f15e2e;
            box-shadow: 0 0 0 4px rgba(241, 94, 46, 0.14);
        }

        .delete-btn {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 12px;
            background: #f15e2e;
            color: #ffffff;
            font-size: 15px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .delete-btn:hover {
            background: #d94f23;
            transform: translateY(-1px);
        }

        .after-box {
            margin-top: 28px;
            padding: 18px;
            border-radius: 14px;
            background: #f9fafb;
            border: 1px solid #eeeeee;
        }

        .after-box strong {
            display: block;
            margin-bottom: 10px;
            color: #111827;
            font-size: 15px;
        }

        .after-list {
            margin: 0;
            padding-left: 20px;
            color: #4b5563;
        }

        .after-list li {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.5;
        }

        .delete-footer {
            margin-top: 28px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.7;
        }

        .delete-footer a {
            color: #f15e2e;
            text-decoration: none;
            font-weight: 700;
        }

        .delete-footer a:hover {
            text-decoration: underline;
        }

        .success-message {
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #166534;
            border-radius: 12px;
            padding: 13px 15px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 12px;
            padding: 13px 15px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        @media (max-width: 576px) {
            .delete-card {
                padding: 28px 22px;
            }

            .delete-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

<div class="delete-page">
    <div class="delete-card">

        <div class="delete-logo">
            {{-- Change logo path if needed --}}
            <img src="{{ asset('images/logo.png') }}" alt="Flecso Logo">
        </div>

        <h1 class="delete-title">
            Delete Your Flecso Account
        </h1>

        <p class="delete-subtitle">
            We're sad to see you go. Permanently delete your Flecso account and all associated data below.
        </p>

        <div class="warning-box">
            <strong>Warning:</strong> This action cannot be undone. All your data will be erased permanently.
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <h2 class="section-title">
            How to Request Deletion
        </h2>

        <ol class="steps">
            <li>Enter your email or Flecso User ID below.</li>
            <li>We’ll send a secure confirmation link to your email.</li>
            <li>Click the link within 48 hours to confirm deletion.</li>
        </ol>

        <form action="{{ route('delete.account.request') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">
                    Email <span style="color:#ef4444">*</span>
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    placeholder="Enter your email address"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <button type="submit" class="delete-btn">
                Send Deletion Request
            </button>
        </form>

        <div class="after-box">
            <strong>After requesting:</strong>

            <ul class="after-list">
                <li>Confirmation email arrives in less than 5 minutes.</li>
                <li>Link expires in 48 hours.</li>
                <li>Deletion starts immediately upon confirmation.</li>
                <li>Final confirmation email will be sent within 24 hours.</li>
            </ul>
        </div>

        <div class="delete-footer">
            © {{ date('Y') }} Flecso. All rights reserved.
            <br>
            Need help? Email
            <a href="mailto:support@flecso.com">support@flecso.com</a>
        </div>

    </div>
</div>

</body>
</html>