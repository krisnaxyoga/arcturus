<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
</head>
<body>
    <h1>Hello {{$user->name}}</h1>

    <p>your new password : {{$token}}</p>

    <p>You have requested to reset your password. To reset your password, click on the following link:</p>

    <a href="{{route('login')}}">login</a>

    <p>If you did not request to reset your password, please ignore this email.</p>

    <p>Thank you,</p>

    <p>{{config('app.name')}}</p>
</body>
</html>
