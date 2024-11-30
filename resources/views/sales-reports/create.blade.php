<x-admin-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-1/2 border rounded-lg bg-white mt-0 sm:mt-10">
        <a href="{{ route('sales-reports.index') }}" class="text-blue-500 text-sm underline">&larr; back to additions
            list</a>
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Create New addition</h1>
        <p class="text-sm text-slate-500 mb-6">Add addition details</p>
        <form action="{{ route('sales-reports.store') }}" method="POST" class="flex flex-col w-full justify-center">
            @csrf
            <div class="mb-4 w-full">
                <label for="reservation_id" class="block text-gray-700 font-bold mb-2 text-sm">Reservation</label>
                <select name="reservation_id" id="reservation_id"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    required>
                    <option value="" disabled selected>Select Reservation</option>
                    @foreach ($reservations as $reservation)
                        <option value="{{ $reservation->id }}">{{ $reservation->name }}</option>
                    @endforeach
                </select>
            </div>

            @error('reservation_id')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <div class="mb-4">
                <label for="amount" class="block text-gray-700 font-bold mb-2 text-sm">Amount</label>
                <input type="number" name="amount" id="amount" step="0.01"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    placeholder="0.00" required>
            </div>

            @error('amount')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <div>
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    Create additional
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
