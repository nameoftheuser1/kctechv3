<x-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-1/2 border rounded-lg bg-white mt-0 sm:mt-10">
        <a href="{{ route('room-list') }}" class="text-blue-500 text-sm underline">&larr; back to room list</a>
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Booking</h1>
        <p class="text-sm text-slate-500 mb-6">Add booking details</p>
        <p class="text-sm text-slate-500 mb-4">
            Please note that this reservation requires approval from the receptionist. After making a request, a 5-hour
            wait is needed before reattempting.
        </p>
        <p class="text-sm text-slate-500">
            Additionally, a GCash number is required to confirm your downpayment.
        </p>

        @if ($errors->any())
            <div class="mb-4 p-4 border rounded-lg bg-red-50 text-red-700">
                <h2 class="font-bold text-lg mb-2">There were some errors with your submission:</h2>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user-form.store') }}" method="POST" id="check-in-form"
            class="flex flex-col w-full justify-center">
            @csrf

            @php
                $fields = [
                    'name' => 'Enter guest name',
                    'address' => 'Enter guest address',
                    'pax' => 'Enter number of guests',
                    'contact' =>
                        'Enter G-cash number (This contact number should be the number that will pay the downpayment)',
                    'car_unit_plate_number' => 'Enter car unit plate number (Optional, only if)',
                    'email' => 'Enter your email address',
                ];
            @endphp

            @foreach ($fields as $field => $placeholder)
                <div class="mb-4 w-full">
                    <label for="{{ $field }}"
                        class="block text-gray-700 font-bold mb-2 text-sm">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
                    <input type="{{ $field === 'pax' ? 'number' : 'text' }}" name="{{ $field }}"
                        id="{{ $field }}" value="{{ old($field) }}"
                        class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                        placeholder="{{ $placeholder }}" required {{ $field === 'pax' ? 'min="1"' : '' }}>
                </div>

                @error($field)
                    <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                @enderror
            @endforeach

            @php
                $dateFields = ['check_in', 'check_out'];
            @endphp

            @foreach ($dateFields as $field)
                <div class="mb-4 w-full">
                    <label for="{{ $field }}"
                        class="block text-gray-700 font-bold mb-2 text-sm">{{ ucfirst(str_replace('_', ' ', $field)) }}
                        Date and Time</label>
                    <input type="datetime-local" name="{{ $field }}" id="{{ $field }}"
                        value="{{ old($field, request($field)) }}"
                        class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                        required>
                    <p class="text-sm text-gray-500 mt-1">Note: Reservations for day tours can only be made between 6:00
                        AM and 6:00 PM.</p>
                </div>

                @error($field)
                    <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                @enderror
            @endforeach

            <div class="mb-4 w-full">
                <label for="stay_type" class="block text-gray-700 font-bold mb-2 text-sm">Stay Type</label>
                <select id="stay_type" name="stay_type"
                    class="w-full bg-gray-50 border border-gray-300 text-gray-600 px-3 py-2 text-sm rounded-lg focus:ring-pink-500 focus:border-pink-500">
                    <option value="">Select Stay Type</option>
                    <option value="day tour" {{ old('stay_type') == 'day tour' ? 'selected' : '' }}>Day Tour</option>
                    <option value="overnight" {{ old('stay_type') == 'overnight' ? 'selected' : '' }}>Overnight
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <button type="button" id="check-availability-button"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                    Check Availability
                </button>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2 text-sm">
                    Rooms available on the selected dates:
                    <span id="selected-dates"></span>
                </label>
                <div id="room-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                    <!-- AJAX-loaded room items will be inserted here -->
                </div>
            </div>

            @error('rooms')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <div class="mb-6 w-full bg-gray-50 border rounded-lg p-4">
                <div class="text-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Payment Summary</h3>
                    <div class="w-full border-b-2 border-dashed border-gray-300 my-2"></div>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <label for="total_amount" class="text-gray-600 text-sm">Total Amount</label>
                        </div>
                        <div class="flex-1 relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-600">₱</span>
                            <input type="text" id="total_amount" name="total_amount"
                                value="{{ old('total_amount') }}"
                                class="w-full bg-transparent border-none text-right pr-3 text-lg font-bold text-gray-800 focus:ring-0"
                                readonly>
                        </div>
                    </div>

                    <div class="w-full border-b border-dotted border-gray-300"></div>

                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <label for="down_payment" class="text-gray-600 text-sm">
                                Down Payment (30%)
                            </label>
                        </div>
                        <div class="flex-1 relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-600">₱</span>
                            <input type="text" id="down_payment" name="down_payment"
                                value="{{ old('down_payment') }}"
                                class="w-full bg-transparent border-none text-right pr-3 text-lg font-bold text-gray-800 focus:ring-0"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="check-in-button"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 uppercase">
                Check in
            </button>
        </form>
    </div>

    <div id="spinner" class="flex justify-center items-center hidden">
        <div class="w-8 h-8 border-4 border-t-4 border-gray-300 border-t-pink-500 rounded-full animate-spin"></div>
    </div>

    @include('partials.confirmation-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAvailabilityButton = document.getElementById('check-availability-button');
            const roomContainer = document.getElementById('room-container');
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            const stayTypeSelect = document.getElementById('stay_type');
            const totalAmountInput = document.getElementById('total_amount');
            const downPaymentInput = document.getElementById('down_payment');
            const checkInButton = document.getElementById('check-in-button');
            const confirmationModal = document.getElementById('confirmation-modal');
            const confirmButton = document.getElementById('confirm-button');
            const cancelButton = document.getElementById('cancel-button');

            checkAvailabilityButton.addEventListener('click', function() {
                const checkInDate = checkInInput.value;
                const checkOutDate = checkOutInput.value;
                const stayType = stayTypeSelect.value;

                if (checkInDate && checkOutDate && stayType) {
                    fetchAvailableRooms(checkInDate, checkOutDate, stayType);
                } else {
                    alert('Please select check-in and check-out dates and stay type.');
                }
            });

            function fetchAvailableRooms(checkIn, checkOut, stayType) {
                fetch("{{ route('user-form.checkAvailability') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            check_in: checkIn,
                            check_out: checkOut,
                            stay_type: stayType
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        roomContainer.innerHTML = '';
                        if (data.rooms && data.rooms.length) {
                            roomContainer.classList.remove('hidden');
                            data.rooms.forEach(room => {
                                const roomDiv = document.createElement('div');
                                roomDiv.classList.add('room-item', 'flex', 'items-center');
                                roomDiv.innerHTML = `
                        <input type="checkbox" name="rooms[]" value="${room.id}" data-price="${room.price}" class="mr-2 room-checkbox">
                        <label class="text-gray-600 text-sm">${room.room_number} - ${room.room_type} - pax(${room.pax}) - ₱${room.price}</label>
                    `;
                                roomContainer.appendChild(roomDiv);
                            });

                            document.querySelectorAll('.room-checkbox').forEach(checkbox => {
                                checkbox.addEventListener('change', updateTotalAmount);
                            });
                        } else {
                            roomContainer.innerHTML =
                                '<p class="text-sm text-gray-600">No available rooms for the selected dates and stay type.</p>';
                            roomContainer.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching available rooms:', error);
                    });
            }


            function updateTotalAmount() {
                let total = 0;
                document.querySelectorAll('.room-checkbox:checked').forEach(checkbox => {
                    total += parseFloat(checkbox.getAttribute('data-price'));
                });
                totalAmountInput.value = total.toFixed(2);

                const downPayment = Math.round(total * 0.3);
                downPaymentInput.value = downPayment;
            }

            checkInButton.addEventListener('click', function() {
                confirmationModal.classList.remove('hidden');
            });

            confirmButton.addEventListener('click', function() {
                confirmationModal.classList.add('hidden');
                document.getElementById('check-in-form')
                    .submit();
            });

            cancelButton.addEventListener('click', function() {
                confirmationModal.classList.add('hidden');
            });

            document.querySelectorAll('.room-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateTotalAmount);
            });
        });
    </script>

</x-layout>
