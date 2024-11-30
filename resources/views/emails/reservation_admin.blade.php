<h1>New Reservation</h1>
<p>A new reservation has been made.</p>

<p><strong>Name:</strong> {{ $reservation->name }}</p>
<p><strong>Email:</strong> {{ $reservation->email }}</p>
<p><strong>Contact:</strong> {{ $reservation->contact }}</p>
<p><strong>Check-In:</strong> {{ $reservation->check_in }}</p>
<p><strong>Check-Out:</strong> {{ $reservation->check_out }}</p>
<p><strong>Total Amount:</strong> {{ $reservation->total_amount }}</p>
<p><strong>Down Payment:</strong> {{ $reservation->down_payment }}</p>

<h2>Selected Rooms</h2>
<ul>
    @foreach ($rooms as $room)
        <li>Room Number: {{ $room->room_number }}, Type: {{ $room->room_type }}</li>
    @endforeach
</ul>
