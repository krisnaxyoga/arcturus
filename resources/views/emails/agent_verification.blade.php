<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>verification account</title>
</head>
<body>
    <h1>Hello {{$data->name}}</h1>

    <p>thank you for registering at arcturus.my.id to continue you can do</p>

    <p>please click link below to verify your account :</p>

    <a href="{{route('verifaccount', ['id' => $data->id])}}">Verification acccount</a>

    <p>Thank you,</p>

    <p>{{config('app.name')}}</p>
</body>
</html>
