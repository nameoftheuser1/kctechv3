<x-admin-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-1/2 border rounded-lg bg-white mt-0 sm:mt-10">
        <a href="{{ route('reservations.index') }}" class="text-blue-500 text-sm underline">&larr; back to reservation
            list</a>
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Check In</h1>
        <p class="text-sm text-slate-500 mb-6">Add check-in details</p>
        <form action="{{ route('reservations.store') }}" method="POST" id="check-in-form"
            class="flex flex-col w-full justify-center">
            @csrf

            @php
                $fields = [
                    'name' => 'Enter guest name',
                    'address' => 'Enter guest address',
                    'pax' => 'Enter number of guests',
                    'contact' => 'Enter contact number',
                    'car_unit_plate_number' => 'Enter car unit plate number (Optional, only if)',
                ];
            @endphp

            @foreach ($fields as $field => $placeholder)
                <div class="mb-4 w-full">
                    <label for="{{ $field }}"
                        class="block text-gray-700 font-bold mb-2 text-sm">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
                    <input type="{{ $field === 'pax' ? 'number' : 'text' }}" name="{{ $field }}"
                        id="{{ $field }}"
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
                    <option value="day tour">Day Tour</option>
                    <option value="overnight">Overnight</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2 text-sm">Available rooms from
                    {{ request('check_in') }} to {{ request('check_out') }}</label>
                <div id="room-container" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                    @foreach ($rooms as $room)
                        <div class="room-item" data-stay-type="{{ $room->stay_type }}"
                            data-room-type="{{ $room->room_type }}">
                            <div class="flex items-center">
                                <input type="checkbox" name="rooms[]" id="room_{{ $room->id }}"
                                    value="{{ $room->id }}" class="mr-2">
                                <label for="room_{{ $room->id }}" class="text-gray-600 text-sm">
                                    {{ $room->room_number }} - {{ $room->room_type }} - pax({{$room->pax}}) - ₱{{ $room->price }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @error('rooms')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <button type="button" id="check-in-button"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 uppercase">
                Check in
            </button>
        </form>
    </div>

    <div id="confirmation-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 sm:w-1/2">
            <h2 class="text-xl font-bold mb-4 text-center">RULES AND REGULATIONS</h2>
            <p class="mb-4">Please read and accept the following rules and regulations before proceeding:</p>

            <ul class="list-disc list-inside text-sm text-gray-700 mb-6">
                <li>Guests are given the upon check-in and we will collect <strong class="underline">Php 1,000 as a
                        Security Deposit</strong> which is fully refundable upon check-out if there's no damage upon
                    inspection of the accommodation.</li>
                <li>Do not smoke in an area where it is easy to cause a fire, <strong>"NO smoking in bed".</strong></li>
                <li>Rooms can only be used by guests who are duly registered at the reception with a valid identity
                    document.</li>
                <li>From 10:00 PM to 07:00 AM is the time of night peace. We invite every guest to be careful during
                    this period and not cause noise that can disturb other guests.</li>
                <li>Resort bears no responsibility for any damage or loss of your personal belongings.</li>
                <li>All rooms have air conditioning. Please note that air conditioning does not work if windows are
                    opened or if windows are not properly closed.</li>
                <li>For your convenience, resort has arranged parking in front of the resort. Resort takes no
                    responsibility for any damage or loss of your vehicle in the parking area.</li>
                <li>All late check-out times will be charged an additional cost of <strong class="underline">Php 1,000
                        per hour</strong> for 8-15 persons and <strong class="underline">Php 500 per hour</strong> for
                    2-7 persons to Guest's account. For open cottages or tables, an additional 200 per head for day tour
                    and 300 per head for overnight stay will be charged.</li>
                <li>If during your stay, for whatever reason, you need to check out earlier, it is necessary to inform
                    Reception 24hrs prior to departure. Otherwise, a full night's stay will be charged.</li>
                <li>In case of cancellation of further Guest stay caused by misbehavior and disrespect of the Resort
                    Rules, Resort reserves the right to charge for the full length of stay as per reservation.</li>
                <li>If you have any suggestion on the resort service or if you have any technical problem in your room,
                    please feel free to inform us at your earliest convenience. In case of late notice for any
                    complaints that you might have, when the resort has no chance to react, those situations will not be
                    considered as a reason for accommodation rate discount.</li>
            </ul>

            <p>By accepting this, I acknowledge that I have read, understand, and agree to the rules and regulations of
                the resort.</p>

            <div class="flex justify-end mt-4">
                <button id="confirm-button"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg mr-2">
                    I understand
                </button>
                <button id="cancel-button"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <style>
        #confirmation-modal {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkInButton = document.getElementById('check-in-button');
            const confirmationModal = document.getElementById('confirmation-modal');
            const checkInForm = document.getElementById('check-in-form');
            const confirmButton = document.getElementById('confirm-button');
            const cancelButton = document.getElementById('cancel-button');
            const stayTypeSelect = document.getElementById('stay_type');
            const roomContainer = document.getElementById('room-container');
            const roomItems = roomContainer.getElementsByClassName('room-item');

            checkInButton.addEventListener('click', function() {
                confirmationModal.classList.remove('hidden');
            });

            confirmButton.addEventListener('click', function() {
                confirmationModal.classList.add('hidden');
                checkInForm.submit();
            });

            cancelButton.addEventListener('click', function() {
                confirmationModal.classList.add('hidden');
            });

            stayTypeSelect.addEventListener('change', function() {
                const selectedStayType = this.value;

                roomContainer.classList.toggle('hidden', !selectedStayType);

                Array.from(roomItems).forEach(item => {
                    item.style.display = (selectedStayType === '' || item.dataset.stayType ===
                        selectedStayType) ? 'flex' : 'none';
                });
            });
        });
    </script>
</x-admin-layout>
