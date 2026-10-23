<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoiceNumber }}</title>
    <style>
        body {
            color: #1f2933;
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
        }

        .wrap {
            padding: 34px;
        }

        .header {
            border-bottom: 2px solid #ef233c;
            margin-bottom: 24px;
            padding-bottom: 16px;
        }

        .brand {
            color: #ef233c;
            font-size: 26px;
            font-weight: 700;
        }

        .muted {
            color: #6b7280;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            margin: 20px 0 8px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            font-weight: 700;
        }

        .summary {
            margin-top: 22px;
        }

        .total {
            color: #111827;
            font-size: 18px;
            font-weight: 700;
        }

        .footer {
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
            margin-top: 34px;
            padding-top: 14px;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="header">
            <div class="brand">bdtalika</div>
            <div class="muted">Payment invoice for business listing package</div>
        </div>

        <table>
            <tr>
                <th>Invoice Number</th>
                <td>{{ $invoiceNumber }}</td>
                <th>Invoice Date</th>
                <td>{{ optional($transaction->created_at)->format('d M Y, h:i A') }}</td>
            </tr>
            <tr>
                <th>Customer</th>
                <td>{{ $transaction->user?->name ?? 'Customer' }}</td>
                <th>Email</th>
                <td>{{ $transaction->email }}</td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $transaction->phone ?: 'N/A' }}</td>
                <th>Transaction ID</th>
                <td>{{ $transaction->txnid ?: 'N/A' }}</td>
            </tr>
        </table>

        <div class="title">Package Details</div>
        <table>
            <thead>
                <tr>
                    <th>Listing</th>
                    <th>Plan</th>
                    <th>Duration</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $transaction->listing?->name ?? 'N/A' }}</td>
                    <td>{{ $transaction->plan_name }}</td>
                    <td>{{ $transaction->plan_duration }}</td>
                    <td>{{ number_format((float) $transaction->amount, 2) }} BDT</td>
                </tr>
            </tbody>
        </table>

        <table class="summary">
            <tr>
                <th>Total Paid</th>
                <td class="total">{{ number_format((float) $transaction->amount, 2) }} BDT</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td>Paid</td>
            </tr>
        </table>

        <div class="footer">
            This is a system generated invoice. Thank you for choosing bdtalika.com.
        </div>
    </div>
</body>
</html>
