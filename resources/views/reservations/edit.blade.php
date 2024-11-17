<x-admin-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-1/2 border rounded-lg bg-white mt-0 sm:mt-10">
        <a href="{{ route('reservations.index') }}" class="text-blue-500 text-sm underline">&larr; back to bookings
            list</a>
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Edit Bookings</h1>
        <p class="text-sm text-slate-500 mb-6">Update booking details</p>
        <form action="{{ route('reservations.update', $reservation->id) }}" method="POST" id="edit-reservation-form"
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
                    <label for="{{ $field }}"
                        class="block text-gray-700 font-bold mb-2 text-sm">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
                    <input type="{{ $field === 'pax' ? 'number' : 'text' }}" name="{{ $field }}"
                        id="{{ $field }}"
                        class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                        placeholder="{{ $placeholder }}" value="{{ old($field, $reservation->$field) }}" required
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
                    <label for="{{ $field }}"
                        class="block text-gray-700 font-bold mb-2 text-sm">{{ ucfirst(str_replace('_', ' ', $field)) }}
                        Date and Time</label>
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

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2 text-sm">Available rooms from
                    {{ old('check_in', $reservation->check_in) }} to
                    {{ old('check_out', $reservation->check_out) }}</label>
                <div id="room-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                    @foreach ($availableRooms as $room)
                        <div class="room-item" data-stay-type="{{ $room->stay_type }}"
                            data-room-type="{{ $room->room_type }}">
                            <div class="flex items-center">
                                <input type="checkbox" name="rooms[]" id="room_{{ $room->id }}"
                                    value="{{ $room->id }}" class="mr-2"
                                    {{ in_array($room->id, $reservation->rooms->pluck('id')->toArray()) ? 'checked' : '' }}>
                                <label for="room_{{ $room->id }}" class="text-gray-600 text-sm">
                                    {{ $room->room_number }} - {{ $room->room_type }} - pax({{ $room->pax }}) -
                                    ₱{{ $room->price }}
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stayTypeSelect = document.getElementById('stay_type');
            const roomContainer = document.getElementById('room-container');
            const roomItems = roomContainer.getElementsByClassName('room-item');

            stayTypeSelect.addEventListener('change', function() {
                const selectedStayType = this.value;
                roomContainer.classList.toggle('hidden', !selectedStayType);

                Array.from(roomItems).forEach(item => {
                    item.style.display = (selectedStayType === '' || item.dataset.stayType ===
                        selectedStayType) ? 'flex' : 'none';
                });
            });

            // Trigger the change event on page load to show/hide rooms based on the initial selection
            stayTypeSelect.dispatchEvent(new Event('change'));
        });
    </script>
</x-admin-layout>
