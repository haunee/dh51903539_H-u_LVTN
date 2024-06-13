<!DOCTYPE html>
<html>
<head>
    <title>Verify Password</title>
</head>
<body>
    <h1>Welcome, {{ $customer->username }}</h1>
    <p>Your verification code is: <strong>{{ $token }}</strong></p>
    <p>Vui lòng nhấn vào link xác nhận tài khoản:</p> <a href="{{ url('/forgot-password') }}">Verify Password</a>
</body>
</html>