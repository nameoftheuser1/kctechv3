<x-admin-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-1/2 border rounded-lg bg-white mt-0 sm:mt-10">
        <a href="{{ route('reservations.index') }}" class="text-blue-500 text-sm underline">&larr; back to bookings
            list</a>
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Edit Booking</h1>
        <p class="text-sm text-slate-500 mb-6">Update booking details</p>

        @if ($errors->any())
            <div class="mb-6 p-4 border border-red-500 bg-red-100 text-red-700 rounded">
                <p class="font-bold">There are some errors in your submission:</p>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('reservations.update.full', $reservation->id) }}" method="POST"
            class="flex flex-col w-full justify-center">
            @csrf
            @method('PUT')

            @php
                $fields = [
                    'name' => 'Enter guest name',
                    'address' => 'Enter guest address',
                    'pax' => 'Enter number of guests',
                    'contact' => 'Enter contact name',
                    'car_unit_plate_number' => 'Enter car unit plate number (Optional)',
                ];
            @endphp

            @foreach ($fields as $field => $placeholder)
                <div class="mb-4 w-full">
                    <label for="{{ $field }}" class="block text-gray-700 font-bold mb-2 text-sm">
                        {{ ucfirst(str_replace('_', ' ', $field)) }}
                    </label>
                    <input type="{{ $field === 'pax' ? 'number' : 'text' }}" name="{{ $field }}"
                        id="{{ $field }}"
                        class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                        placeholder="{{ $placeholder }}" value="{{ old($field, $reservation->$field) }}"
                        {{ $field === 'pax' ? 'min="1"' : '' }}>
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
                    <label for="{{ $field }}" class="block text-gray-700 font-bold mb-2 text-sm">
                        {{ ucfirst(str_replace('_', ' ', $field)) }} Date and Time
                    </label>
                    <input type="datetime-local" name="{{ $field }}" id="{{ $field }}"
                        value="{{ old($field, $reservation->$field->format('Y-m-d\TH:i')) }}"
                        class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                        required>
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
                    <option value="day tour" {{ $reservation->stay_type === 'day tour' ? 'selected' : '' }}>Day Tour
                    </option>
                    <option value="overnight" {{ $reservation->stay_type === 'overnight' ? 'selected' : '' }}>Overnight
                    </option>
                </select>
            </div>

            <button type="button" id="check-availability-button"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                Check Room Availability
            </button>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2 text-sm">
                    @if ($selectedRoomIds)
                        Selected Rooms
                    @else
                        Available Rooms
                    @endif
                </label>

                <div id="selected-rooms-container" class="mb-4">
                    @if ($selectedRoomIds)
                        @foreach ($availableRooms->whereIn('id', $selectedRoomIds) as $room)
                            <div class="bg-gray-100 p-3 rounded-lg mb-2">
                                <p class="text-gray-600 text-sm">
                                    {{ $room->room_number }} - {{ $room->room_type }} -
                                    pax({{ $room->pax }}) - ₱{{ $room->price }}
                                </p>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div id="room-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                    @foreach ($availableRooms as $room)
                        <div class="room-item">
                            <div class="flex items-center">
                                <input type="checkbox" name="rooms[]" id="room_{{ $room->id }}"
                                    value="{{ $room->id }}" class="mr-2"
                                    {{ in_array($room->id, $selectedRoomIds) ? 'checked' : '' }}>
                                <label for="room_{{ $room->id }}" class="text-gray-600 text-sm">
                                    {{ $room->room_number }} - {{ $room->room_type }} -
                                    pax({{ $room->pax }}) - ₱{{ $room->price }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @error('rooms')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 uppercase">
                Update Reservation
            </button>
        </form>

        <div id="loading-spinner" class="hidden flex justify-center items-center mt-4">
            <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-500 border-opacity-75"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkInField = document.getElementById('check_in');
                const checkOutField = document.getElementById('check_out');
                const checkAvailabilityButton = document.getElementById('check-availability-button');
                const roomContainer = document.getElementById('room-container');
                const selectedRoomsContainer = document.getElementById('selected-rooms-container');
                const loadingSpinner = document.getElementById('loading-spinner');

                if (checkAvailabilityButton) {
                    checkAvailabilityButton.addEventListener('click', function() {
                        const checkIn = checkInField.value;
                        const checkOut = checkOutField.value;
                        const stayType = document.getElementById('stay_type').value;

                        if (!checkIn || !checkOut) {
                            alert('Please select check-in and check-out dates');
                            return;
                        }

                        // Show the spinner
                        loadingSpinner.classList.remove('hidden');

                        // CSRF Token for security
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content');

                        fetch('/user-form/check-availability', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify({
                                    check_in: checkIn,
                                    check_out: checkOut,
                                    stay_type: stayType
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Hide the spinner
                                loadingSpinner.classList.add('hidden');

                                // Hide the current selected rooms container
                                selectedRoomsContainer.innerHTML = '';

                                // Clear previous room options and show new rooms
                                roomContainer.innerHTML = '';

                                if (data.rooms.length > 0) {
                                    data.rooms.forEach(room => {
                                        const roomDiv = document.createElement('div');
                                        roomDiv.classList.add('room-item');
                                        roomDiv.innerHTML = `
                                        <div class="flex items-center">
                                            <input type="checkbox" name="rooms[]" id="room_${room.id}"
                                                value="${room.id}" class="mr-2">
                                            <label for="room_${room.id}" class="text-gray-600 text-sm">
                                                ${room.room_number} - ${room.room_type} - pax(${room.pax}) - ₱${room.price}
                                            </label>
                                        </div>
                                    `;
                                        roomContainer.appendChild(roomDiv);
                                    });

                                    // Remove the 'hidden' class to show the rooms
                                    roomContainer.classList.remove('hidden');
                                } else {
                                    roomContainer.innerHTML =
                                        '<p class="text-red-500">No rooms available for the selected dates.</p>';
                                    roomContainer.classList.remove('hidden');
                                }
                            })
                            .catch(error => {
                                // Hide the spinner
                                loadingSpinner.classList.add('hidden');
                                console.error('Error fetching room availability:', error);
                            });
                    });
                } else {
                    console.error('Check availability button not found.');
                }
            });
        </script>
    </div>
</x-admin-layout>
