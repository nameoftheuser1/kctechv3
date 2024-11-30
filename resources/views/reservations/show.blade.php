<x-admin-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-3/4 border rounded-lg bg-white mt-0 sm:mt-10">
        <a href="{{ route('reservations.index') }}" class="text-blue-500 text-sm underline">&larr; back to reservations
            list</a>
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Reservation Details</h1>
        <p class="text-sm text-slate-500 mb-6">View reservation information</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Name</p>
                <p class="text-gray-600 text-base">{{ $reservation->name }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Address</p>
                <p class="text-gray-600 text-base">{{ $reservation->address }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Pax</p>
                <p class="text-gray-600 text-base">{{ $reservation->pax }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Contact</p>
                <p class="text-gray-600 text-base">{{ $reservation->contact }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Car Unit Plate Number</p>
                <p class="text-gray-600 text-base">{{ $reservation->car_unit_plate_number }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Check-in</p>
                <p class="text-gray-600 text-base">{{ $reservation->check_in->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Check-out</p>
                <p class="text-gray-600 text-base">{{ $reservation->check_out->format('M d, Y h:i A') }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Status</p>
                <p class="text-gray-600 text-base">{{ $reservation->status }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Amount</p>
                <p class="text-gray-600 text-base">₱{{ $reservation->formatted_total_amount }}</p>
            </div>


            <!-- Sales Report Section -->
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Additional Charge</p>
                <p class="text-gray-600 text-base">
                    @php
                        $salesReportsTotal = $reservation->salesReports->sum('amount');
                    @endphp
                    @if ($salesReportsTotal > 0)
                        ₱{{ number_format($salesReportsTotal, 2) }}
                    @else
                        No additional includes
                    @endif
                </p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Total Amount (Including Additional Charges)</p>
                <p class="text-gray-600 text-base">
                    @php
                        $salesReportsTotal = $reservation->salesReports->sum('amount');
                        $totalAmountIncludingSalesReports = $reservation->total_amount + $salesReportsTotal;
                    @endphp
                    ₱{{ number_format($totalAmountIncludingSalesReports, 2) }}
                </p>
            </div>
            <div>
                <p class="text-gray-700 font-bold mb-2 text-sm">Is this commissioned?</p>
                <p class="text-gray-600 text-base">{{ $reservation->is_commissioned ? 'Yes' : 'No' }}</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end">
            @if ($reservation->status == 'check in')
                <form action="{{ route('reservations.update', $reservation->id) }}" method="POST"
                    class="inline-block mr-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="check out">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        Check Out
                    </button>
                </form>
            @endif

            <!-- Reserve Button -->
            @if ($reservation->status == 'pending')
                <form action="{{ route('reservations.reserve', $reservation->id) }}" method="POST"
                    class="inline-block mr-4">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        Reserve
                    </button>
                </form>
            @endif

            <!-- Apply Commission Button -->
            @if (!$reservation->is_commissioned)
                <p class="text-gray-700 mt-2 me-3">
                    This will be the amount given as commission:
                    <strong>{{ number_format($reservation->total_amount * (config('settings.commission_percent') ?? 10 / 100), 2, '.', ',') }}</strong>
                </p>
                <form action="{{ route('reservations.applyCommission', $reservation->id) }}" method="POST"
                    class="inline-block mr-4" id="apply-commission-form">
                    @csrf
                    <button type="button" onclick="openConfirmationModal()"
                        class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                        Apply Commission
                    </button>
                </form>
            @endif

            <a href="{{ route('receipt', $reservation->id) }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 me-2 ">
                View Receipt
            </a>
            @if (!in_array($reservation->status, ['check in', 'check out']))
                <a href="{{ route('reservations.edit', $reservation->id) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Edit Reservation
                </a>
            @endif
        </div>
    </div>

    <div id="confirmation-modal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white p-6 rounded-lg w-96">
            <h3 class="text-xl font-bold text-gray-700 mb-4">Confirm Apply Commission</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to apply the commission of
                <strong>{{ number_format($reservation->total_amount * (config('settings.commission_percent') ?? 10 / 100), 2, '.', ',') }}</strong>?
                This action cannot be undone.
            </p>
            <div class="flex justify-end">
                <button onclick="closeConfirmationModal()"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg mr-2">
                    Cancel
                </button>
                <button onclick="submitCommissionForm()"
                    class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-lg">
                    Apply Commission
                </button>
            </div>
        </div>
    </div>

    <script>
        // Open the confirmation modal
        function openConfirmationModal() {
            document.getElementById('confirmation-modal').classList.remove('hidden');
        }

        // Close the confirmation modal
        function closeConfirmationModal() {
            document.getElementById('confirmation-modal').classList.add('hidden');
        }

        // Submit the commission form
        function submitCommissionForm() {
            document.getElementById('apply-commission-form').submit();
        }
    </script>
</x-admin-layout>
