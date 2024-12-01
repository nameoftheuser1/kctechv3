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

                {{-- <div class="mb-4">
                    <label for="predict_sales_month" class="block text-gray-700 font-semibold mb-2">Predict Sales
                        Month</label>
                    <input type="number" id="predict_sales_month" name="predict_sales_month"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500"
                        value="{{ old('predict_sales_month', $settings['predict_sales_month']) }}" min="1"
                        max="12">
                    @error('predict_sales_month')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div> --}}

                {{-- <div class="mb-4">
                    <label for="historical_sales_data" class="block text-gray-700 font-semibold mb-2">Historical Sales
                        Data (months)</label>
                    <input type="number" id="historical_sales_data" name="historical_sales_data"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500"
                        value="{{ old('historical_sales_data', $settings['historical_sales_data']) }}" min="1"
                        max="12">
                    @error('historical_sales_data')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="predict_reservations_month" class="block text-gray-700 font-semibold mb-2">Predict
                        Reservations Month</label>
                    <input type="number" id="predict_reservations_month" name="predict_reservations_month"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500"
                        value="{{ old('predict_reservations_month', $settings['predict_reservations_month']) }}"
                        min="1" max="12">
                    @error('predict_reservations_month')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="historical_reservations_data" class="block text-gray-700 font-semibold mb-2">Historical
                        Reservations Data (months)</label>
                    <input type="number" id="historical_reservations_data" name="historical_reservations_data"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500"
                        value="{{ old('historical_reservations_data', $settings['historical_reservations_data']) }}"
                        min="1" max="12">
                    @error('historical_reservations_data')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div> --}}

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105">
                    Save Settings
                </button>
            </form>
        </div>

        <!-- Global Error Display at the Bottom -->

    </div>
</x-admin-layout>
