<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <p>Hello {{ $transaction->user?->name ?? 'Customer' }},</p>

    <p>
        Your payment for <strong>{{ $transaction->plan_name }}</strong> has been completed successfully.
        Your listing is now waiting for review.
    </p>

    <p>
        <strong>Invoice:</strong> {{ $invoiceNumber }}<br>
        <strong>Transaction ID:</strong> {{ $transaction->txnid }}<br>
        <strong>Amount Paid:</strong> {{ number_format((float) $transaction->amount, 2) }} BDT
    </p>

    <p>Your PDF invoice is attached with this email.</p>

    <p>
        Thank you,<br>
        bdtalika.com
    </p>
</body>
</html>
