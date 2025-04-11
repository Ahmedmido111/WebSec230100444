<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h2>Hello {{ $name }}!</h2>
    <p>Thank you for registering. Please click the link below to verify your email address:</p>
    <p><a href="{{ $link }}">Verify Email Address</a></p>
    <p>If you did not create an account, no further action is required.</p>
    <p>Regards,<br>Your Application Team</p>
</body>
</html> 