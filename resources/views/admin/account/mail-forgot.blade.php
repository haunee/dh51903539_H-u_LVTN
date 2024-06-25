<!DOCTYPE html>
<html>
<head>
    <title>Verify Password Admin</title>
</head>
<body>
    <h1>Welcome, {{ $admin->email }}</h1>
    <p>Your verification code is: <strong>{{ $token}}</strong></p>
    <p>Vui lòng nhấn vào link xác nhận tài khoản:</p> <a href="{{ url('/admin-forgotpass') }}">Verify Password</a>
</body>
</html>