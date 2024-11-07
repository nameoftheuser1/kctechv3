<x-admin-layout>
    <div>
        @if (session('success'))
            <x-flashMsg msg="{{ session('success') }}" />
        @elseif (session('deleted'))
            <x-flashMsg msg="{{ session('deleted') }}" bg="bg-red-500" />
        @elseif (session('error'))
            <x-flashMsg msg="{{ session('error') }}" bg="bg-red-500" />
        @endif
    </div>

    <div class="mb-4">
        <a href="{{ route('reservations.check-date') }}"
            class="text-sm bg-slate-600 text-white p-2 rounded-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 mr-1">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Check ins
        </a>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
            Check in
            <p class="mt-1 text-sm font-normal text-gray-500 ">Browse a list of reservations in our system.</p>

            <!-- Filter Form -->
            <form id="filter-form" class="flex flex-col md:flex-row mb-4">
                <!-- Search, Month, Day, and Filter Button Inputs -->
            </form>
        </caption>

        <div class="hidden md:block">
            <table class="min-w-full text-sm text-left rtl:text-right text-gray-500">
                <!-- Table Header and Table Rows -->
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Address</th>
                        <th scope="col" class="px-6 py-3">Pax</th>
                        <th scope="col" class="px-6 py-3">Contact</th>
                        <th scope="col" class="px-6 py-3">Car Unit/Plate No.</th>
                        <th scope="col" class="px-6 py-3">Check-in</th>
                        <th scope="col" class="px-6 py-3">Check-out</th>
                        <th scope="col" class="px-6 py-3">Checkout Time</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr class="bg-white border-b hover:bg-gray-100 cursor-pointer">
                            <td class="px-6 py-4">{{ $reservation->name }}</td>
                            <td class="px-6 py-4">{{ $reservation->address }}</td>
                            <td class="px-6 py-4">{{ $reservation->pax }}</td>
                            <td class="px-6 py-4">{{ $reservation->contact }}</td>
                            <td class="px-6 py-4">{{ $reservation->car_unit_plate_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $reservation->check_in->format('F j, Y h:i A') }}</td>
                            <td class="px-6 py-4">{{ $reservation->check_out->format('F j, Y h:i A') }}</td>
                            <td
                                class="px-6 py-4 {{ $reservation->checkout_time > $reservation->check_out ? 'text-red-500' : '' }}">
                                {{ $reservation->checkout_time ? $reservation->checkout_time->format('F j, Y h:i A') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 flex items-center space-x-4">
                                <span class="capitalize text-gray-800">{{ ucfirst($reservation->status) }}</span>
                                @if ($reservation->status == 'reserved' && auth()->user()->role == 'admin')
                                    <button type="button"
                                        class="text-blue-500 hover:text-blue-700 font-semibold py-1 px-3 border border-blue-500 rounded-md transition-all duration-200 ease-in-out hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        onclick="openCheckInModal({{ $reservation->id }}, '{{ $reservation->name }}')">
                                        Check
                                    </button>
                                    <button type="button"
                                        class="text-red-500 hover:text-red-700 font-semibold py-1 px-3 border border-red-500 rounded-md transition-all duration-200 ease-in-out hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500"
                                        onclick="openCancelModal({{ $reservation->id }}, '{{ $reservation->name }}')">
                                        Cancel
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ number_format($reservation->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-ConfirmationModal />
        <div class="p-4">
            {{ $reservations->links() }}
        </div>
    </div>

    <!-- Check-in Modal -->
    <div id="checkInModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
        <div class="bg-white p-4 rounded-lg w-1/3">
            <h3 class="text-lg font-semibold">Confirm Check-in</h3>
            <p>Are you sure you want to check in for <span id="checkInName"></span>?</p>
            <form id="checkInForm" action="" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Confirm</button>
                <button type="button" onclick="closeCheckInModal()" class="ml-2 text-gray-500">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancelModal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
        <div class="bg-white p-4 rounded-lg w-1/3">
            <h3 class="text-lg font-semibold">Confirm Cancellation</h3>
            <p>Are you sure you want to cancel the reservation for <span id="cancelName"></span>?</p>
            <form id="cancelForm" action="" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Confirm</button>
                <button type="button" onclick="closeCancelModal()" class="ml-2 text-gray-500">Cancel</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/confirmation.js') }}"></script>
    <script>
        // Open Check-in Modal
        function openCheckInModal(reservationId, name) {
            document.getElementById('checkInModal').classList.remove('hidden');
            document.getElementById('checkInName').innerText = name;
            document.getElementById('checkInForm').action = '/reservations/check-in/' + reservationId;
        }

        // Close Check-in Modal
        function closeCheckInModal() {
            document.getElementById('checkInModal').classList.add('hidden');
        }

        // Open Cancel Modal
        function openCancelModal(reservationId, name) {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelName').innerText = name;
            document.getElementById('cancelForm').action = '/reservations/cancel/' + reservationId;
        }

        // Close Cancel Modal
        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>
</x-admin-layout>
