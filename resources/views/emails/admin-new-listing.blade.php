<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Listing Created</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h3>New listing created</h3>
    <p>A customer has created a new business listing on bdtalika.com.</p>

    <p>
        <strong>Listing:</strong> {{ $listing->name }}<br>
        <strong>Category:</strong> {{ $listing->category?->title ?: 'N/A' }}<br>
        <strong>Status:</strong> Draft<br>
        <strong>Customer:</strong> {{ $listing->user?->name ?: 'N/A' }}<br>
        <strong>Email:</strong> {{ $listing->user?->email ?: $listing->email ?: 'N/A' }}<br>
        <strong>Phone:</strong> {{ $listing->phone_number ?: $listing->user?->phone ?: 'N/A' }}<br>
        <strong>Created:</strong> {{ optional($listing->created_at)->format('d M Y, h:i A') }}
    </p>

    <p>
        <a href="{{ route('admin.listing.view', $listing->id) }}">View listing details</a>
    </p>
</body>
</html>
