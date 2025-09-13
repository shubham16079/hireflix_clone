<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Invitation</title>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; margin: 0; padding: 0; background-color: #f3f4f6; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 28px; font-weight: bold; }
        .content { padding: 40px 20px; }
        .greeting { font-size: 18px; color: #374151; margin-bottom: 20px; }
        .interview-details { background-color: #f9fafb; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3b82f6; }
        .interview-title { font-size: 20px; font-weight: bold; color: #1f2937; margin-bottom: 10px; }
        .cta-button { display: inline-block; background-color: #3b82f6; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 20px 0; }
        .cta-button:hover { background-color: #2563eb; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; color: #6b7280; font-size: 14px; }
        .instructions { background-color: #fef3c7; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f59e0b; }
        .instructions h3 { color: #92400e; margin-top: 0; }
        .instructions ul { color: #92400e; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎥 Interview Invitation</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello <strong>{{ $candidateName }}</strong>,
            </div>
            
            <p style="color: #374151; line-height: 1.6;">
                We're excited to invite you for a video interview! This is a great opportunity for us to get to know you better and for you to showcase your skills.
            </p>
            
            <div class="interview-details">
                <div class="interview-title">📋 {{ $interviewTitle }}</div>
                <p style="color: #6b7280; margin: 0;">Please click the button below to start your interview when you're ready.</p>
            </div>
            
            <div class="instructions">
                <h3>📝 Interview Instructions:</h3>
                <ul>
                    <li>Make sure you have a stable internet connection</li>
                    <li>Use a quiet environment with good lighting</li>
                    <li>Have your camera and microphone ready</li>
                    <li>Take your time to answer each question thoughtfully</li>
                    <li>You can complete the interview at your own pace</li>
                </ul>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $interviewLink }}" style="display: inline-block; background-color: #3b82f6; color: #ffffff !important; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; margin: 20px 0; font-size: 16px;">🚀 Start Your Interview</a>
            </div>
            
            <p style="color: #6b7280; font-size: 14px; text-align: center; margin-top: 30px;">
                If the button doesn't work, you can copy and paste this link into your browser:<br>
                <a href="{{ $interviewLink }}" style="color: #3b82f6; word-break: break-all;">{{ $interviewLink }}</a>
            </p>
        </div>
        
        <div class="footer">
            <p>This interview invitation was sent by <strong>{{ $companyName }}</strong></p>
            <p>If you have any questions, please don't hesitate to contact us.</p>
            <p style="margin-top: 20px; font-size: 12px; color: #9ca3af;">
                © {{ date('Y') }} {{ $companyName }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
