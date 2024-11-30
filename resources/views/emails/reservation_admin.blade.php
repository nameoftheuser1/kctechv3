<h1>New Reservation</h1>
<p>A new reservation has been made.</p>

<p><strong>Name:</strong> {{ $reservation->name }}</p>
<p><strong>Email:</strong> {{ $reservation->email }}</p>
<p><strong>Contact:</strong> {{ $reservation->contact }}</p>
<p><strong>Check-In:</strong> {{ $reservation->check_in }}</p>
<p><strong>Check-Out:</strong> {{ $reservation->check_out }}</p>
<p><strong>Total Amount:</strong> {{ number_format($reservation->total_amount, 2) }}</p>
<p><strong>Down Payment:</strong> {{ number_format($reservation->down_payment, 2) }}</p>

<h2>Selected Rooms</h2>
<ul>
    @php
        $totalRoomPrice = 0; // Initialize total room price variable
    @endphp
    @foreach ($rooms as $room)
        <li>Room Number: {{ $room->room_number }}, Type: {{ $room->room_type }}, Price:
            {{ number_format($room->price, 2) }}</li>
        @php
            $totalRoomPrice += $room->price; // Add room price to the total
        @endphp
    @endforeach
</ul>

<p><strong>Total Room Price:</strong> {{ number_format($totalRoomPrice, 2) }}</p>
