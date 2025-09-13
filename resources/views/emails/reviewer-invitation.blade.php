<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviewer Invitation - {{ $interviewTitle }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .title {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .cta-button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .cta-button:hover {
            background-color: #1d4ed8;
        }
        .message-box {
            background-color: #f8f9fa;
            border-left: 4px solid #2563eb;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6b7280;
            font-size: 14px;
        }
        .features {
            background-color: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .features h3 {
            color: #1e40af;
            margin-top: 0;
        }
        .features ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .features li {
            margin: 5px 0;
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🎥 {{ $companyName }}</div>
            <h1 class="title">Reviewer Invitation</h1>
        </div>

        <div class="content">
            <p>Hello,</p>
            
            <p>You have been invited to review interview submissions for:</p>
            
            <h2 style="color: #2563eb; margin: 20px 0;">{{ $interviewTitle }}</h2>
            
            @if($customMessage)
                <div class="message-box">
                    <strong>Message from the interviewer:</strong><br>
                    {{ $customMessage }}
                </div>
            @endif

            <div class="features">
                <h3>📊 What you can do as a reviewer:</h3>
                <ul>
                    <li>Watch candidate video responses</li>
                    <li>Score candidates on a 1-10 scale</li>
                    <li>Provide detailed feedback and comments</li>
                    <li>Compare multiple candidates</li>
                    <li>Export review data</li>
                </ul>
            </div>

            <p>Click the button below to access the submissions and start reviewing:</p>
            
            <div style="text-align: center;">
                <a href="{{ $submissionsLink }}" style="display: inline-block; background-color: #2563eb; color: #ffffff !important; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; text-align: center; margin: 20px 0; font-size: 16px;">
                    🎯 Start Reviewing Submissions
                </a>
            </div>

            <p><strong>Important:</strong> You can review submissions at your own pace. Each submission includes video responses that you can watch and evaluate.</p>
        </div>

        <div class="footer">
            <p>This invitation was sent by {{ $companyName }}.</p>
            <p>If you have any questions, please contact the interviewer directly.</p>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                © {{ date('Y') }} {{ $companyName }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
