<x-admin-layout>
    <div>
        @if (session('success'))
            <x-flashMsg msg="{{ session('success') }}" />
        @elseif (session('deleted'))
            <x-flashMsg msg="{{ session('deleted') }}" bg="bg-red-500" />
        @endif
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white">
            Payments
            <p class="mt-1 text-sm font-normal text-gray-500">Browse a list of payments in our system.</p>

            <form id="search-form">
                <input type="text" name="search" id="search-input" placeholder="Search payments..."
                    class="p-2 border text-sm rounded w-full focus:ring-pink-600 mb-1 mt-2"
                    value="{{ request('search') }}" />
                <button type="submit"
                    class="p-2 bg-blue-500 text-white rounded w-full text-sm hover:bg-blue-700">Search</button>
            </form>
        </caption>

        <div class="hidden md:block">
            <table class="min-w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Reservation ID</th>
                        <th scope="col" class="px-6 py-3">Reference Number</th>
                        <th scope="col" class="px-6 py-3">Amount</th>
                        <th scope="col" class="px-6 py-3">Created At</th>
                        <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody id="payments-table-body">
                    @foreach ($payments as $payment)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4">
                                <a href="{{ route('reservations.show', $payment->reservation_id) }}"
                                    class="text-blue-600 hover:underline">
                                    {{ $payment->reservation_id }}
                                </a>
                            </td>
                            <td class="px-6 py-4">{{ $payment->reference_number }}</td>
                            <td class="px-6 py-4">₱{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-4">{{ $payment->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('payments.edit', $payment) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                <button class="text-red-600 hover:underline ml-2"
                                    onclick="showConfirmationModal('Are you sure you want to delete this payment?', 'delete-form-{{ $payment->id }}')">
                                    Delete
                                </button>
                                <form id="delete-form-{{ $payment->id }}"
                                    action="{{ route('payments.destroy', $payment) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="md:hidden grid grid-cols-1 gap-4 p-4">
            @foreach ($payments as $payment)
                <div class="bg-white border rounded-lg p-4 shadow-md">
                    <h3 class="text-lg font-semibold">
                        <a href="{{ route('reservations.show', $payment->reservation_id) }}"
                            class="text-blue-600 hover:underline">
                            Reservation ID: {{ $payment->reservation_id }}
                        </a>
                    </h3>
                    <p><strong>Reference Number:</strong> {{ $payment->reference_number }}</p>
                    <p><strong>Amount:</strong> ₱{{ number_format($payment->amount, 2) }}</p>
                    <p><strong>Created At:</strong> {{ $payment->created_at->format('Y-m-d H:i:s') }}</p>
                    <div class="mt-2">
                        <a href="{{ route('payments.edit', $payment) }}" class="text-blue-600 hover:underline">Edit</a>
                        <button class="text-red-600 hover:underline ml-2"
                            onclick="showConfirmationModal('delete-form-{{ $payment->id }}', 'payment')">
                            Delete
                        </button>
                        <form id="delete-form-{{ $payment->id }}" action="{{ route('payments.destroy', $payment) }}"
                            method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <x-ConfirmationModal />

        <div class="p-4">
            {{ $payments->links() }}
        </div>
    </div>

    <script src="{{ asset('js/confirmation.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeSearch('search-form', 'payments-table-body', '{{ route('payments.index') }}');
        });
    </script>
</x-admin-layout>
