<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Reservation Update</title>
</head>
<body>
    <h1>Your Reservation has been updated</h1>
    <p>Dear {{ $reservation->name }},</p>
    <p>Your reservation has been successfully updated. Here are the details:</p>
    <ul>
        <li>Check-in: {{ $reservation->check_in }}</li>
        <li>Check-out: {{ $reservation->check_out }}</li>
        <li>Total Amount: {{ $reservation->total_amount }}</li>
        <li>Rooms:
            <ul>
                @foreach ($reservation->rooms as $room)
                    <li>Room Number: {{ $room->room_number }}, Type: {{ $room->room_type }}</li>
                @endforeach
            </ul>
        </li>
    </ul>
    <p>If you have any questions, feel free to contact us.</p>
    <p>Thank you for choosing us!</p>
</body>
</html>
