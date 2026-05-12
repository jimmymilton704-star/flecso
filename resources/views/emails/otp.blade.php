<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your OTP Code</title>
</head>

<body style="margin:0;padding:0;background:#f5f7fb;font-family:Arial, sans-serif;">

    <div style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.08);">

        <!-- HEADER -->
        <div style="background:#FF6B1A;padding:20px;text-align:center;color:#fff;">
            <h2 style="margin:0;">Flecso Verification</h2>
        </div>

        <!-- BODY -->
        <div style="padding:30px;text-align:center;">

            <h3 style="margin-bottom:10px;color:#111;">Email Verification OTP</h3>

            <p style="color:#666;font-size:14px;line-height:22px;">
                Use the OTP below to verify your email address. This code is valid for <strong>10 minutes</strong>.
            </p>

            <!-- OTP BOX -->
            <div style="margin:25px 0;">
                <div style="display:inline-block;padding:15px 25px;font-size:28px;letter-spacing:6px;font-weight:bold;color:#FF6B1A;background:#fff3ec;border-radius:10px;border:1px dashed #FF6B1A;">
                    {{ $otp }}
                </div>
            </div>

            <p style="font-size:13px;color:#999;">
                If you did not request this, you can safely ignore this email.
            </p>

        </div>

        <!-- FOOTER -->
        <div style="background:#f8f8f8;padding:15px;text-align:center;font-size:12px;color:#777;">
            © {{ date('Y') }} Flecso. All rights reserved.
        </div>

    </div>

</body>
</html>