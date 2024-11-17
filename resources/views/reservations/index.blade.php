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
            Booking
        </a>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white">
            Bookings
            <p class="mt-1 text-sm font-normal text-gray-500">Browse a list of booking in our system.</p>

            <!-- Filter Form -->
            <form id="filter-form" class="flex flex-col md:flex-row mb-4">
                <input type="text" name="search" id="search-input" placeholder="Search by contact..."
                    class="p-2 border text-sm rounded w-full md:w-1/3 focus:ring-pink-600 mb-1 md:mr-2"
                    value="{{ request('search') }}" />
                <select name="month" class="p-2 border text-sm rounded w-full md:w-1/3 mb-1 md:mr-2">
                    <option value="">Filter by Month</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
                <input type="number" name="day" min="1" max="31" placeholder="Day"
                    class="p-2 border text-sm rounded w-full md:w-1/3 mb-1 md:mr-2" value="{{ request('day') }}" />
                <button type="submit"
                    class="p-2 bg-blue-500 text-white rounded w-full md:w-auto text-sm hover:bg-blue-700">Filter</button>
            </form>
        </caption>

        <div class="hidden md:block">
            <table class="min-w-full text-sm text-left rtl:text-right text-gray-500">
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
                <tbody id="reservations-table-body">
                    @forelse ($reservations as $reservation)
                        <tr class="bg-white border-b hover:bg-gray-100"
                            onclick="window.location='{{ route('reservations.show', $reservation) }}'">
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
                            <td class="px-6 py-4 flex items-center space-x-4" onclick="event.stopPropagation()">
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
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4 text-gray-500">No reservations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <div class="md:hidden grid grid-cols-1 gap-4 p-4">
        @foreach ($reservations as $reservation)
            <div class="bg-white border rounded-lg p-4 shadow-md">
                <h3 class="text-lg font-semibold">Name: {{ $reservation->name }}</h3>
                <p><strong>Address:</strong> {{ $reservation->address }}</p>
                <p><strong>Pax:</strong> {{ $reservation->pax }}</p>
                <p><strong>Contact:</strong> {{ $reservation->contact }}</p>
                <p><strong>Car Unit/Plate No.:</strong> {{ $reservation->car_unit_plate_number ?? 'N/A' }}</p>
                <p><strong>Check-in:</strong> {{ $reservation->check_in->format('Y-m-d') }}</p>
                <p><strong>Check-out:</strong> {{ $reservation->check_out->format('Y-m-d') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($reservation->status) }}</p>
                <p><strong>Total Amount:</strong> {{ number_format($reservation->total_amount, 2) }}</p>
                <div class="mt-2">
                    <a href="{{ route('reservations.edit', $reservation) }}"
                        class="text-blue-600 hover:underline">Edit</a>
                    <button class="text-red-600 hover:underline ml-2"
                        onclick="confirmDelete('delete-form-{{ $reservation->id }}', 'reservation')">
                        Delete
                    </button>
                    <form id="delete-form-{{ $reservation->id }}"
                        action="{{ route('reservations.destroy', $reservation) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <x-ConfirmationModal />
    <div class="p-4">
        {{ $reservations->links() }}
    </div>
    </div>

    <!-- Check-in Modal -->
    <div id="checkInModal"
        class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
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
    <div id="cancelModal"
        class="hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
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
    <script src="{{ asset('js/search.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeSearch('filter-form', 'reservations-table-body', '{{ route('reservations.index') }}');
        });

        // Open Check-in Modal
        function openCheckInModal(reservationId, name) {
            document.getElementById('checkInModal').classList.remove('hidden');
            document.getElementById('checkInName').innerText = name;
            document.getElementById('checkInForm').action = "{{ route('reservations.check-in', ':id') }}".replace(':id',
                reservationId);
        }

        // Close Check-in Modal
        function closeCheckInModal() {
            document.getElementById('checkInModal').classList.add('hidden');
        }

        // Open Cancel Modal
        function openCancelModal(reservationId, name) {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelName').innerText = name;
            document.getElementById('cancelForm').action = "{{ route('reservations.cancel', ':id') }}".replace(':id',
                reservationId);
        }

        // Close Cancel Modal
        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>
</x-admin-layout>
