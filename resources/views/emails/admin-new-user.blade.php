<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Customer Registered</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h3>New customer registered</h3>
    <p>A new customer account has been created on bdtalika.com.</p>

    <p>
        <strong>Name:</strong> {{ $user->name }}<br>
        <strong>Username:</strong> {{ $user->username ?: 'N/A' }}<br>
        <strong>Email:</strong> {{ $user->email }}<br>
        <strong>Phone:</strong> {{ $user->phone ?: 'N/A' }}<br>
        <strong>Registered:</strong> {{ optional($user->created_at)->format('d M Y, h:i A') }}
    </p>

    <p>
        <a href="{{ route('admin-user-show', $user->id) }}">View customer details</a>
    </p>
</body>
</html>
