<x-layout>
    <x-navbar />
    <div class="bg-white py-44 ">
        <div class="container mx-auto px-4">
            <!-- Gallery Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Resort Gallery</h1>
                <p class="text-lg text-gray-600">Experience the beauty of our resort through our gallery</p>
            </div>

            <!-- Gallery Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($roomGalleries as $image)
                    <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300">
                        <div class="aspect-w-16 aspect-h-12 overflow-hidden">
                            <img
                                src="{{ asset( $image->image_path) }}"
                                alt="{{ $image->name }}"
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                            >
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-6">
                            <h3 class="text-xl font-semibold text-white">{{ $image->name }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <x-footer/>
</x-layout>
