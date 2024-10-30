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
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Change Password</h1>

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
                    <a href="{{ route('password.change') }}" class="hover:text-blue-500">Change Password</a>
                </li>
            </ol>
        </nav>

        <!-- Change Password Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="current_password" class="block text-gray-700 font-semibold mb-2">Current
                        Password</label>
                    <input type="password" id="current_password" name="current_password"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500" required>
                    @error('current_password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="new_password" class="block text-gray-700 font-semibold mb-2">New Password</label>
                    <input type="password" id="new_password" name="new_password"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500" required>
                    @error('new_password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="new_password_confirmation" class="block text-gray-700 font-semibold mb-2">Confirm New
                        Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                        class="border rounded-lg w-full p-2 focus:ring-blue-500" required>
                </div>

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105">
                    Update Password
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
