<x-admin-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4">Create a New Reservation</h1>

        <!-- Form to create a new reservation -->
        <form action="{{ route('reservations.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Check-in date -->
            <div>
                <label for="check_in" class="block text-sm font-medium text-gray-700">Check-in Date</label>
                <input type="date" name="check_in" id="check_in" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Check-out date -->
            <div>
                <label for="check_out" class="block text-sm font-medium text-gray-700">Check-out Date</label>
                <input type="date" name="check_out" id="check_out" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Contact information -->
            <div>
                <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                <input type="text" name="contact" id="contact" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Room selection (checkboxes) -->
            <div>
                <label for="rooms" class="block text-sm font-medium text-gray-700">Select Rooms</label>
                <div class="space-y-2 mt-2">
                    @foreach ($rooms as $room)
                        <div>
                            <input type="checkbox" name="rooms[]" value="{{ $room->id }}" id="room_{{ $room->id }}">
                            <label for="room_{{ $room->id }}" class="ml-2 text-sm font-medium text-gray-700">{{ $room->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit button -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Create Reservation</button>
        </form>
    </div>
</x-admin-layout>
