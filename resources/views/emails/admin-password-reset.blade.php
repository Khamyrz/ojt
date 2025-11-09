<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password Reset</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #ff6b6b;
        }
        .content {
            padding: 30px;
        }
        .alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn:hover {
            background: linear-gradient(135deg, #ee5a52, #ff6b6b);
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .security-note {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Admin Password Reset</h1>
            <p>OJT Management System</p>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>We received a request to reset your admin password for the OJT Management System.</p>
            
            <div class="alert">
                <strong>⚠️ Security Alert:</strong> If you did not request this password reset, please ignore this email and contact the system administrator immediately.
            </div>
            
            <p>To reset your password, click the button below:</p>
            
            <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
            
            <div class="security-note">
                <strong>🔒 Security Information:</strong>
                <ul>
                    <li>This link will expire in 1 hour</li>
                    <li>This link can only be used once</li>
                    <li>If the link doesn't work, copy and paste this URL into your browser:</li>
                </ul>
                <p style="word-break: break-all; background: #f0f0f0; padding: 10px; border-radius: 3px; font-family: monospace;">
                    {{ $resetUrl }}
                </p>
            </div>
            
            <p>If you have any questions or concerns, please contact the system administrator.</p>
            
            <p>Best regards,<br>
            <strong>OJT Management System</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} OJT Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>



