<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #2C5F5F, #4A90E2);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px 0;
        }

        .original-message {
            background-color: #f8f9fa;
            border-left: 4px solid #2C5F5F;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
        }

        .reply-content {
            background-color: #e8f4fd;
            border-left: 4px solid #4A90E2;
            padding: 15px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .contact-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Laiya Grande Beach Resort</h1>
            <p>Thank you for contacting us</p>
        </div>

        <div class="content">
            <p>Dear {{ $originalMessage->name }},</p>

            <div class="reply-content" style="white-space: pre-line;">
                {{ $replyContent }}
            </div>

            <div class="original-message">
                <strong>Your original message:</strong><br>
                <em>{{ $originalMessage->message }}</em>
            </div>

            <p>If you have any additional questions or need further assistance, please don't hesitate to contact us
                again.</p>

            <div class="contact-info">
                <strong>Contact Information:</strong><br>
                📍 Laiya, San Juan, Philippines, 4226<br>
                📞 0963 033 7629<br>
                📧 laiyagrandebr22@gmail.com<br>
                🌐 https://web.facebook.com/laiyagrande
            </div>

            <p>We look forward to welcoming you to Laiya Grande Beach Resort!</p>

            <p>Best regards,<br>
                <strong>The Laiya Grande Beach Resort Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>This email was sent to {{ $originalMessage->email }} in response to your inquiry.</p>
            <p>&copy; {{ date('Y') }} Laiya Grande Beach Resort. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
