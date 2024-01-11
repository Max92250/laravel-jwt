<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <p>Hello {{ $email }},</p>
    <p>You have requested a password reset. Please click the link below to reset your password:</p>
    <a href="{{ url("api/password/reset/{$token}") }}">Reset Password</a>

    <p>If you did not request a password reset, please ignore this email.</p>

    <p>Thank you,</p>
    <p>Your Application Team</p>
</body>
</html>