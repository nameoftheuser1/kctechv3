<!DOCTYPE html>
<html>

<head>
    <title>Payment Received</title>
</head>

<body>
    <h2>Payment Received for Reservation #{{ $reservation->id }}</h2>
    <p><strong>Reference Number:</strong> {{ $payment->reference_number }}</p>
    <p><strong>Amount:</strong> ₱{{ number_format($payment->amount, 2) }}</p>
    <p><strong>Customer:</strong> {{ $reservation->name }}</p>
    <p><strong>Status:</strong> {{ $reservation->status }}</p>
</body>

</html>
