<x-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-1/2 border rounded-lg bg-white mt-0 sm:mt-10">
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Reservation Receipt</h1>
        <form action="{{ route('payments.store') }}" method="POST" id="reservation-receipt-form"
            class="flex flex-col w-full justify-center">
            @csrf
            <input type="hidden" name="reservation" value="{{ $reservation->id }}">

            <div class="mb-4 w-full">
                <label for="reference_number" class="block text-gray-700 font-bold mb-2 text-sm">Reference
                    Number</label>
                <input type="text" id="reference_number" name="reference_number"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    placeholder="Enter reference number" required>
                @error('reference_number')
                    <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 w-full">
                <label for="amount" class="block text-gray-700 font-bold mb-2 text-sm">Amount</label>
                <input type="number" id="amount" name="amount" step="0.01"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 cursor-not-allowed"
                    placeholder="Enter amount" value="{{ request('down_payment', '') }}" required readonly>
                @error('amount')
                    <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
                @enderror
            </div>

            @if (session('error'))
                <p class="text-sm text-red-600 mb-4">{{ session('error') }}</p>
            @endif

            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                Submit Payment
            </button>
        </form>
    </div>
</x-layout>
