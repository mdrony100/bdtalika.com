<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoiceNumber }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #222; line-height: 1.6;">
    <p>Hello {{ $transaction->user?->name ?? 'Customer' }},</p>

    <p>
        Thank you for choosing banner advertisement on <strong>bdtalika.com</strong>.
        Please find your invoice below. The PDF copy is attached with this email.
    </p>

    <p>
        <strong>Invoice Number:</strong> {{ $invoiceNumber }}<br>
        <strong>Advertisement:</strong> {{ $transaction->plan_name }}<br>
        <strong>Duration:</strong> {{ $transaction->plan_duration }}<br>
        <strong>Amount Due:</strong> {{ number_format((float) $transaction->amount, 2) }} BDT (Payable Offline)
    </p>

    <p>
        Please complete the payment as discussed with our team to activate your advertisement.
    </p>

    <p>
        Thank you,<br>
        bdtalika.com
    </p>
</body>
</html>
