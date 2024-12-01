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

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-12">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white">
            Account Security
            <p class="mt-1 text-sm font-normal text-gray-500">Manage your account security settings.</p>
        </caption>

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Account Security</h2>

            <!-- List Style for Account Security Option -->
            <ul class="space-y-4">
                <li>
                    <a href="{{ route('password.change') }}"
                        class="mt-2 block px-4 py-3 bg-gray-100 rounded-lg text-blue-600 font-semibold  hover:bg-blue-500 hover:text-white">
                        Change Password
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.editEmail') }}"
                        class="mt-2 block px-4 py-3 bg-gray-100 rounded-lg text-blue-600 font-semibold  hover:bg-blue-500 hover:text-white">
                        Update email
                    </a>
                </li>
                <li>
                    <a href="{{ route('settings.edit') }}"
                        class="mt-2 block px-4 py-3 bg-gray-100 rounded-lg text-blue-600 font-semibold  hover:bg-blue-500 hover:text-white">
                        Update years in dashboard
                    </a>
                </li>
            </ul>
        </div>
    </div>
</x-admin-layout>
