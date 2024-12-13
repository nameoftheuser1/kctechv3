<x-admin-layout>
    <div>
        @if (session('success'))
            <x-flashMsg msg="{{ session('success') }}" />
        @elseif (session('deleted'))
            <x-flashMsg msg="{{ session('deleted') }}" bg="bg-red-500" />
        @elseif (session('error'))
            <x-flashMsg msg="{{ session('error') }}" bg="bg-red-500" />
        @endif
    </div>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Settings</h1>

        <!-- Breadcrumb Navigation -->
        <nav class="mb-4" aria-label="Breadcrumb">
            <ol class="list-reset flex text-gray-700">
                <li>
                    <a href="{{ route('settings') }}" class="hover:text-blue-500">Settings</a>
                </li>
                <li>
                    <span class="mx-2">/</span>
                </li>
                <li>
                    <span class="text-gray-500">Update Year</span>
                </li>
            </ol>
        </nav>

        <!-- Settings Form -->

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="mb-4">
                    <label for="total_revenue_year" class="block text-gray-700 font-semibold mb-2">Total Revenue
                        Year</label>
                    <input type="text" id="total_revenue_year" name="total_revenue_year"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500"
                        value="{{ old('total_revenue_year', $settings['total_revenue_year']) }}"
                        max="{{ now()->year }}">
                    @error('total_revenue_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="total_expenses_year" class="block text-gray-700 font-semibold mb-2">Total Expenses
                        Year</label>
                    <input type="text" id="total_expenses_year" name="total_expenses_year"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500"
                        value="{{ old('total_expenses_year', $settings['total_expenses_year']) }}"
                        max="{{ now()->year }}">
                    @error('total_expenses_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="total_salaries_year" class="block text-gray-700 font-semibold mb-2">Total Salaries
                        Year</label>
                    <input type="text" id="total_salaries_year" name="total_salaries_year"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500"
                        value="{{ old('total_salaries_year', $settings['total_salaries_year']) }}"
                        max="{{ now()->year }}">
                    @error('total_salaries_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="reservation_year" class="block text-gray-700 font-semibold mb-2">Reservation
                        Year</label>
                    <input type="text" id="reservation_year" name="reservation_year"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500"
                        value="{{ old('reservation_year', $settings['reservation_year']) }}"
                        max="{{ now()->year }}">
                    @error('reservation_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105">
                    Save Settings
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
