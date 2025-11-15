<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login Verification Code</title>
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
            background: linear-gradient(135deg, #1d3557, #457b9d);
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
            border: 2px dashed #457b9d; 
            border-radius: 8px; 
            background: #f0f7ff; 
            color: #1d3557;
            margin: 24px 0;
        }
        .muted { 
            color: #667085; 
            font-size: 14px; 
            line-height: 1.6;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
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
            <h1>🔐 Admin Login Verification</h1>
        </div>
        <p style="font-size: 16px; color: #1d3557; margin-bottom: 16px;">Hello Administrator,</p>
        <p>You have requested to log in to the OJT Management System. Use the verification code below to complete your login:</p>
        <div class="code">{{ $otpCode }}</div>
        <div class="warning">
            <strong>⚠️ Security Notice:</strong> This code expires in 10 minutes. Do not share this code with anyone.
        </div>
        <p class="muted">If you didn't request this login, please ignore this email or contact the system administrator immediately.</p>
        <div class="footer">
            <p>This is an automated message from the OJT Management System.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

