<!DOCTYPE html>
<html>
<head>
    <title>Подтверждение email</title>
</head>
<body>
    <h1>Здравствуйте, {{ $user->name }}!</h1>
    <p>Пожалуйста, подтвердите вашу электронную почту, нажимая на ссылку ниже:</p>
    <a href="{{ route('verification.verify', ['id' => $user->id, 'hash' => sha1($user->email)]) }}">
        Подтвердить электронную почту
    </a>
</body>
</html>
