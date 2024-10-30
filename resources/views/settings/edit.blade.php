<x-admin-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Settings</h1>

        <!-- Breadcrumb Navigation -->
        <nav class="mb-4" aria-label="Breadcrumb">
            <ol class="list-reset flex text-gray-700">
                <li>
                    <a href="{{ route('settings.index') }}" class="hover:text-blue-500">Settings</a>
                </li>
                <li>
                    <span class="mx-2">/</span>
                </li>
                <li>
                    <a href="{{ route('settings.edit', $setting->id) }}" class="hover:text-blue-500">Edit</a>
                </li>
            </ol>
        </nav>

        <div>
            @if (session('success'))
                <x-flashMsg msg="{{ session('success') }}" />
            @elseif (session('error'))
                <x-flashMsg msg="{{ session('error') }}" bg="bg-red-500" />
            @endif
        </div>

        <!-- Edit Setting Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('settings.update', $setting->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="key" class="block text-gray-700 font-semibold mb-2">Key</label>
                    <input type="text" id="key" name="key"
                           class="border rounded-lg w-full p-2"
                           value="{{ old('key', $setting->key) }}" readonly>
                </div>

                <div class="mb-4">
                    <label for="value" class="block text-gray-700 font-semibold mb-2">Value</label>
                    <input type="text" id="value" name="value"
                           class="border rounded-lg w-full p-2"
                           value="{{ old('value', $setting->value) }}" required>
                    @error('value')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out transform hover:scale-105">
                    Update Setting
                </button>
            </form>
        </div>
    </div>
</x-admin-layout>
