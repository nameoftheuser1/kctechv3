<x-admin-layout>
    <div>
        @if (session('success'))
            <x-flashMsg msg="{{ session('success') }}" />
        @elseif (session('deleted'))
            <x-flashMsg msg="{{ session('deleted') }}" bg="bg-red-500" />
        @endif
    </div>

    <div class="mb-4">
        <a href="{{ route('rooms.create') }}" class="text-sm bg-slate-600 text-white p-2 rounded-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 mr-1">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Add Room
        </a>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white ">
            Rooms
            <p class="mt-1 text-sm font-normal text-gray-500 ">Browse a list of available rooms in our reservation
                system.</p>

            <form id="search-form">
                <input type="text" name="search" id="search-input" placeholder="Search rooms..."
                    class="p-2 border text-sm rounded w-full focus:ring-pink-600 mb-1 mt-2"
                    value="{{ request('search') }}" />
                <button type="submit"
                    class="p-2 bg-blue-500 text-white rounded w-full text-sm hover:bg-blue-700">Search</button>
            </form>
        </caption>

        <div class="hidden md:block">
            <table class="min-w-full text-sm text-left rtl:text-right text-gray-500 ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">Room Number</th>
                        <th scope="col" class="px-6 py-3">Room Type</th>
                        <th scope="col" class="px-6 py-3">Price</th>
                        <th scope="col" class="px-6 py-3">Pax</th>
                        <th scope="col" class="px-6 py-3">Stay Type</th>
                        <th scope="col" class="px-6 py-3"><span class="sr-only">Edit</span></th>
                    </tr>
                </thead>
                <tbody id="rooms-table-body">
                    @foreach ($rooms as $room)
                        <tr class="bg-white border-b ">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $room->room_number }}
                            </th>
                            <td class="px-6 py-4">{{ $room->room_type }}</td>
                            <td class="px-6 py-4">₱{{ number_format($room->price, 2) }}</td>
                            <td class="px-6 py-4">{{ $room->pax }}</td>
                            <td class="px-6 py-4">{{ ucfirst($room->stay_type) }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('rooms.edit', $room) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                <button class="text-red-600 hover:underline ml-2"
                                    onclick="showConfirmationModal('Are you sure you want to delete this room?', 'delete-form-{{ $room->id }}')">
                                    Delete
                                </button>
                                <form id="delete-form-{{ $room->id }}"
                                    action="{{ route('rooms.destroy', $room) }}" method="POST" style="display: none;">
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
            @foreach ($rooms as $room)
                <div class="bg-white border rounded-lg p-4 shadow-md">
                    <h3 class="text-lg font-semibold">Room Number: {{ $room->room_number }}</h3>
                    <p><strong>Room Type:</strong> {{ $room->room_type }}</p>
                    <p>Price: ₱{{ number_format($room->price, 2) }}</p>
                    <p><strong>Pax:</strong> {{ $room->pax }}</p>
                    <p><strong>Stay Type:</strong> {{ ucfirst($room->stay_type) }}</p>
                    <div class="mt-2">
                        <a href="{{ route('rooms.edit', $room) }}" class="text-blue-600 hover:underline">Edit</a>
                        <button class="text-red-600 hover:underline ml-2"
                            onclick="confirmDelete('delete-form-{{ $room }}', 'room')">
                            Delete
                        </button>
                        <form id="delete-form-{{ $room }}" action="{{ route('rooms.destroy', $room) }}"
                            method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <x-ConfirmationModal/>
        <div class="p-4">
            {{ $rooms->links() }}
        </div>
    </div>
    <script src="{{ asset('js/confirmation.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeSearch('search-form', 'rooms-table-body', '{{ route('rooms.index') }}');
        });
    </script>
</x-admin-layout>
