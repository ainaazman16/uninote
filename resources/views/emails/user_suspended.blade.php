<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Suspended</title>
</head>
<body>

<p>Dear {{ $user->name }},</p>

<p>
    Your UniNote account has been <strong>suspended</strong> by the administrator
    due to a violation of system policies.
</p>

<p>
    If you believe this action was taken in error, please contact the system administrator.
</p>

<p>
    Regards,<br>
    <strong>UniNote Administration</strong>
</p>

</body>
</html>
