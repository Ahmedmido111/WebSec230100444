<!DOCTYPE html>
<html>
<head>
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Password Reset Request</h2>
        <p>Hello {{ $name }},</p>
        <p>We received a request to reset your password. Click the button below to create a new password:</p>
        
        <a href="{{ $resetLink }}" class="button">Reset Password</a>
        
        <p>This password reset link will expire in 60 minutes.</p>
        
        <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
        
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>For security reasons, this link will expire after 60 minutes.</p>
        </div>
    </div>
</body>
</html> 