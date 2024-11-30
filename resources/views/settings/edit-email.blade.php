<x-admin-layout>
    <div>
        @if (session('success'))
            <x-flashMsg msg="{{ session('success') }}" />
        @elseif (session('error'))
            <x-flashMsg msg="{{ session('error') }}" bg="bg-red-500" />
        @endif
    </div>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Email Setting</h1>

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
                    <a href="{{ route('settings.updateEmail') }}" class="hover:text-blue-500">Edit Email</a>
                </li>
            </ol>
        </nav>

        <!-- Explanation Section -->
        <div class="bg-yellow-100 text-yellow-800 border border-yellow-300 rounded-lg p-4 mb-4">
            <p>
                <strong>Note:</strong> This email address will be used to receive notifications about reservations and
                payment updates for bookings.
                Make sure to provide a valid and active email address to stay informed.
            </p>
        </div>

        <!-- Edit Email Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('settings.updateEmail') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email Address</label>
                    <input type="email" id="email" name="email"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500" value="{{ $emailSetting->value }}"
                        required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105">
                    Update Email
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
