<h1>Reservation Confirmation</h1>
<p>Dear {{ $reservation->name }},</p>

<p>Thank you for making a reservation with us. Here are the details of your reservation:</p>

<p><strong>Check-In:</strong> {{ $reservation->check_in }}</p>
<p><strong>Check-Out:</strong> {{ $reservation->check_out }}</p>
<p><strong>Number of Guests:</strong> {{ $reservation->pax }}</p>
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

<p>To complete your reservation, please proceed with the down payment. You can send your payment via GCash to this
    number: 09057556578.</p>

<p><a href="{{ route('user-form.payment', ['id' => $reservation->id, 'down_payment' => $reservation->down_payment]) }}"
        style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Proceed
        to Payment</a></p>

<p>We look forward to welcoming you!</p>
