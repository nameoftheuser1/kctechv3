<x-admin-layout>
    <div class="mb-4">
        <a href="{{ route('reservations.index') }}"
            class="text-sm bg-slate-600 text-white p-2 rounded-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 mr-1">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Back to Reservations
        </a>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="p-5 text-lg font-semibold text-left text-gray-900 bg-gray-100 border-b border-gray-300">
            Reservation Receipt
            <p class="mt-1 text-sm font-normal text-gray-500">Details of your reservation are listed below.</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md w-1/2 mx-auto mt-4">
            <div class="text-gray-900 border-b border-gray-300 pb-4 mb-4">
                <h3 class="text-lg font-semibold">Reservation Details</h3>
                <p><strong>Name:</strong> {{ $reservation->name }}</p>
                <p><strong>Address:</strong> {{ $reservation->address }}</p>
                <p><strong>Pax:</strong> {{ $reservation->pax }}</p>
                <p><strong>Contact:</strong> {{ $reservation->contact }}</p>
                <p><strong>Car Plate Number:</strong> {{ $reservation->car_unit_plate_number ?? 'N/A' }}</p>
                <p><strong>Check-in Date:</strong>
                    {{ \Carbon\Carbon::parse($reservation->check_in)->format('F j, Y g:i A') }}</p>
                <p><strong>Check-out Date:</strong>
                    {{ \Carbon\Carbon::parse($reservation->check_out)->format('F j, Y g:i A') }}</p>

                <!-- Calculate total sales report amount -->
                @php
                    $salesReportsTotal = $reservation->salesReports->sum('amount');
                    $totalAmountWithSales = $reservation->total_amount + $salesReportsTotal;
                @endphp

                @if ($salesReportsTotal > 0)
                    <p><strong>Total Additional:</strong> ₱{{ number_format($salesReportsTotal, 2) }}</p>
                @endif

                <p class="text-lg font-bold"><strong>Total Amount:</strong>
                    ₱{{ number_format($totalAmountWithSales, 2) }}</p>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900">Rooms</h3>
                <ul class="list-disc list-inside text-gray-700">
                    @foreach ($reservation->rooms as $room)
                        <li>{{ $room->room_number }} - {{ $room->room_type }} -
                            {{ $room->stay_type }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="p-4 mt-4">
            <a href="{{ route('reservations.index') }}"
                class="inline-block p-2 bg-blue-500 text-white rounded text-sm hover:bg-blue-700">
                Back to Reservations
            </a>
        </div>
    </div>
</x-admin-layout>
