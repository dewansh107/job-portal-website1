<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fogot Password  Email</title>
</head>
<body>
    <h1>Hello {{$mailData['user']->name}}</h1>
    <p>Click the Link to change your password.</p>
    <a href="{{ route("account.resetPassword",$mailData['token']) }}">Click Here</a>

    <p>Thank You</p>
    <p>Have a great journey</p>
    
</body>
</html>