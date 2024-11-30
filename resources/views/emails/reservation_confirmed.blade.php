<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Reservation has been Confirmed</title>
</head>

<body>
    <h1>Dear {{ $reservation->name }},</h1>
    <p>We are pleased to inform you that your reservation has been confirmed. Below are the details of your reservation:
    </p>

    <ul>
        <li><strong>Reservation ID:</strong> {{ $reservation->id }}</li>
        <li><strong>Name:</strong> {{ $reservation->name }}</li>
        <li><strong>Contact:</strong> {{ $reservation->contact }}</li>
        <li><strong>Check-in Date:</strong> {{ $reservation->check_in->format('F j, Y') }}</li>
        <li><strong>Check-out Date:</strong> {{ $reservation->check_out->format('F j, Y') }}</li>
        <li><strong>Total Amount:</strong> ₱{{ number_format($reservation->total_amount, 2) }}</li>
        <li><strong>Status:</strong> Reserved</li>
    </ul>

    <p>Thank you for choosing us. We look forward to your stay!</p>
</body>

</html>
