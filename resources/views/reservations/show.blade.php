<x-admin-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-3/4 border rounded-lg bg-white mt-0 sm:mt-10">
        <a href="{{ route('reservations.index') }}" class="text-blue-500 text-sm underline">&larr; back to reservations list</a>
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Reservation Details</h1>
        <p class="text-sm text-slate-500 mb-6">View reservation information</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Name</p>
                <p class="text-gray-600 text-base">{{ $reservation->name }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Address</p>
                <p class="text-gray-600 text-base">{{ $reservation->address }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Pax</p>
                <p class="text-gray-600 text-base">{{ $reservation->pax }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Contact</p>
                <p class="text-gray-600 text-base">{{ $reservation->contact }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Car Unit Plate Number</p>
                <p class="text-gray-600 text-base">{{ $reservation->car_unit_plate_number }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Check-in</p>
                <p class="text-gray-600 text-base">{{ $reservation->check_in->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Check-out</p>
                <p class="text-gray-600 text-base">{{ $reservation->check_out->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Status</p>
                <p class="text-gray-600 text-base">{{ $reservation->status }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Total Amount</p>
                <p class="text-gray-600 text-base">{{ $reservation->total_amount }}</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            @if ($reservation->status == 'check in')
                <form action="{{ route('reservations.update', $reservation->id) }}" method="POST" class="inline-block mr-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="check out">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        Check Out
                    </button>
                </form>
            @endif
            <a href="{{ route('receipt', $reservation->id) }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                View Receipt
            </a>
        </div>
    </div>
</x-admin-layout>
