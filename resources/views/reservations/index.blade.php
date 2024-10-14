<x-admin-layout>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4">Reservations</h1>

        <!-- Button to create a new reservation -->
        <a href="{{ route('reservations.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-4 inline-block">Create New Reservation</a>

        <!-- Table to display the reservations -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Reservation ID</th>
                        <th class="px-4 py-2 text-left">Contact</th>
                        <th class="px-4 py-2 text-left">Check-in</th>
                        <th class="px-4 py-2 text-left">Check-out</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $reservation->id }}</td>
                            <td class="px-4 py-2">{{ $reservation->contact }}</td>
                            <td class="px-4 py-2">{{ $reservation->check_in }}</td>
                            <td class="px-4 py-2">{{ $reservation->check_out }}</td>
                            <td class="px-4 py-2">{{ $reservation->status }}</td>
                            <td class="px-4 py-2">
                                <!-- Edit button -->
                                <a href="{{ route('reservations.edit', $reservation->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded-md">Edit</a>

                                <!-- Delete form -->
                                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
