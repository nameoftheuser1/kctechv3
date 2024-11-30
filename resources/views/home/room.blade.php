<x-layout>
    <x-navbar />

    <div class="min-h-screen bg-gradient-to-b from-white to-gray-50 py-20 ">
        <div class="container mx-auto px-4 py-16">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Kandahar Cottages</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Experience the perfect blend of comfort and elegance in our thoughtfully designed rooms
                </p>
            </div>

            <div class="my-6">
                <button onclick="window.location.href='{{ route('user-form') }}'"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                    Book Now
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($roomsWithGalleries as $room)
                    <div class="group">
                        <div class="bg-white rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                            <div class="relative">
                                <div class="h-64 overflow-hidden">
                                    @if ($room->gallery_image)
                                        <img src="{{ asset($room->gallery_image) }}" alt="{{ $room->room_type }}"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                            <span class="text-gray-400">No image available</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                                            {{ $room->room_number }} - {{ $room->room_type }}
                                        </h2>
                                        <p class="text-3xl font-bold text-blue-600">₱{{ $room->price }}</p>
                                    </div>
                                </div>

                                <div class="space-y-3 mt-6">
                                    <div class="flex items-center text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span>{{ $room->room_type }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>Up to {{ $room->pax }} guests</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $room->stay_type }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <x-footer />
</x-layout>
