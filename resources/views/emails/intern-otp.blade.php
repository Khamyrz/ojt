<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Intern Login Verification Code</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f6f8fb; 
            padding: 24px; 
            margin: 0;
        }
        .card { 
            max-width: 560px; 
            margin: 0 auto; 
            background: #ffffff; 
            border: 1px solid #eee; 
            border-radius: 8px; 
            padding: 32px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #38c172, #2fa360);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -32px -32px 24px -32px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .code { 
            font-size: 36px; 
            letter-spacing: 8px; 
            font-weight: 700; 
            text-align: center; 
            padding: 20px; 
            border: 2px dashed #38c172; 
            border-radius: 8px; 
            background: #f0fff4; 
            color: #2fa360;
            margin: 24px 0;
        }
        .muted { 
            color: #667085; 
            font-size: 14px; 
            line-height: 1.6;
        }
        .info {
            background: #e6f7ff;
            border-left: 4px solid #38c172;
            padding: 12px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>🎓 Intern Login Verification</h1>
        </div>
        <p style="font-size: 16px; color: #2fa360; margin-bottom: 16px;">Hello Intern,</p>
        <p>You have requested to log in to the OJT Management System. Use the verification code below to complete your login:</p>
        <div class="code">{{ $otpCode }}</div>
        <div class="info">
            <strong>ℹ️ Important:</strong> This code expires in 10 minutes. Keep it secure and do not share it with anyone.
        </div>
        <p class="muted">If you didn't request this login, please ignore this email or contact your supervisor immediately.</p>
        <div class="footer">
            <p>This is an automated message from the OJT Management System.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

