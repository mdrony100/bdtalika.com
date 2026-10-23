<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bKash Payment Completed</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <h3>bKash payment completed</h3>
    <p>A customer has completed a bKash payment for a business listing on bdtalika.com.</p>

    <p>
        <strong>Listing:</strong> {{ $listing?->name ?: 'N/A' }}<br>
        <strong>Category:</strong> {{ $listing?->category?->title ?: 'N/A' }}<br>
        <strong>Customer:</strong> {{ $transaction->user?->name ?: 'N/A' }}<br>
        <strong>Email:</strong> {{ $transaction->user?->email ?: $transaction->email ?: 'N/A' }}<br>
        <strong>Phone:</strong> {{ $transaction->phone ?: $listing?->phone_number ?: $transaction->user?->phone ?: 'N/A' }}<br>
        <strong>Plan:</strong> {{ $transaction->plan_name ?: 'N/A' }}<br>
        <strong>Duration:</strong> {{ $transaction->plan_duration ?: 'N/A' }}<br>
        <strong>Amount:</strong> {{ number_format((float) $transaction->amount, 2) }} BDT<br>
        <strong>Transaction ID:</strong> {{ $transaction->txnid ?: 'N/A' }}<br>
        <strong>Paid At:</strong> {{ optional($transaction->created_at)->format('d M Y, h:i A') }}
    </p>

    @if ($listing)
        <p>
            <a href="{{ route('admin.listing.edit', $listing->id) }}">Review listing in admin panel</a>
        </p>
    @endif
</body>
</html>
