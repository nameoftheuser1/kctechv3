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

            <!-- Spinner -->
            <div id="loading-spinner" class="hidden flex justify-center items-center mb-4">
                <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span class="ml-2 text-blue-500">Processing...</span>
            </div>

            <button type="submit" id="submit-button"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase">
                Submit Payment
            </button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reservation-receipt-form');
            const spinner = document.getElementById('loading-spinner');
            const submitButton = document.getElementById('submit-button');

            form.addEventListener('submit', function(event) {
                // Prevent multiple submissions
                submitButton.disabled = true;
                submitButton.classList.add('cursor-not-allowed', 'opacity-50');

                // Show spinner
                spinner.classList.remove('hidden');
            });
        });
    </script>
</x-layout>
