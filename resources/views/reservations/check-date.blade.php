<x-admin-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-1/2 border rounded-lg bg-white mt-0 sm:mt-10">
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Check Availability</h1>
        <p class="text-sm text-slate-500 mb-6">Please enter your check-in and check-out dates and times.</p>

        <form action="{{ route('reservations.create') }}" method="GET" class="flex flex-col w-full justify-center">
            <div class="mb-4 w-full">
                <label for="check_in" class="block text-gray-700 font-bold mb-2 text-sm">Check-in Date and Time</label>
                <input type="datetime-local" name="check_in" id="check_in"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    required>
            </div>

            <div class="mb-4 w-full">
                <label for="check_out" class="block text-gray-700 font-bold mb-2 text-sm">Check-out Date and Time</label>
                <input type="datetime-local" name="check_out" id="check_out"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    required>
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 uppercase">
                    Check Availability
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
