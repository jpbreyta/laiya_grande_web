<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Laiya Grande Verification Code</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }

        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            background: linear-gradient(135deg, #0d9488, #06b6d4);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .title {
            color: #1f2937;
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }

        .subtitle {
            color: #6b7280;
            font-size: 16px;
            margin: 10px 0 0 0;
        }

        .otp-container {
            background: linear-gradient(135deg, #f0fdfa, #ecfdf5);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }

        .otp-label {
            color: #065f46;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #047857;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }

        .otp-note {
            color: #059669;
            font-size: 14px;
            margin-top: 10px;
        }

        .content {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.7;
            margin: 20px 0;
        }

        .warning {
            background: #fef3cd;
            border: 1px solid #fbbf24;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .warning-title {
            color: #92400e;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .warning-text {
            color: #a16207;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text {
            color: #9ca3af;
            font-size: 14px;
            margin: 5px 0;
        }

        .brand {
            color: #0d9488;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏖️</div>
            <h1 class="title">Verification Code</h1>
            <p class="subtitle">Your booking verification is almost complete</p>
        </div>

        <div class="content">
            <p>Hello,</p>
            <p>You've requested to access your booking information at <span class="brand">Laiya Grande Beach
                    Resort</span>. To complete the verification process, please use the code below:</p>
        </div>

        <div class="otp-container">
            <div class="otp-label">Your Verification Code</div>
            <div class="otp-code">{{ $otp->otp_code }}</div>
            <div class="otp-note">This code expires in 10 minutes</div>
        </div>

        <div class="content">
            <p>Enter this code on the verification page to access your booking details and continue with your
                reservation.</p>
        </div>

        <div class="warning">
            <div class="warning-title">🔒 Security Notice</div>
            <div class="warning-text">
                • Never share this code with anyone<br>
                • Our staff will never ask for this code<br>
                • If you didn't request this code, please ignore this email
            </div>
        </div>

        <div class="footer">
            <p class="footer-text">This is an automated message from <span class="brand">Laiya Grande Beach
                    Resort</span></p>
            <p class="footer-text">If you need assistance, please contact our support team</p>
        </div>
    </div>
</body>

</html>
