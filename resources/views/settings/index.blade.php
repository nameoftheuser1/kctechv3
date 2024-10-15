<x-admin-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Settings</h1>

        <!-- Breadcrumbs -->
        <nav class="mb-4" aria-label="Breadcrumb">
            <ol class="list-reset flex text-gray-700">
                <li>
                    <a href="{{ route('settings') }}" class="hover:text-blue-500">Settings</a>
                </li>
            </ol>
        </nav>

        <div>
            @if (session('success'))
                <x-flashMsg msg="{{ session('success') }}" />
            @elseif (session('deleted'))
                <x-flashMsg msg="{{ session('deleted') }}" bg="bg-red-500" />
            @elseif (session('error'))
                <x-flashMsg msg="{{ session('error') }}" bg="bg-red-500" />
            @endif
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Account Security</h2>
            <a href="{{ route('password.change') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105">
                Change Password
            </a>
        </div>
    </div>
</x-admin-layout>
