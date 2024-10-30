<x-admin-layout>
    <div>
        @if (session('success'))
            <x-flashMsg msg="{{ session('success') }}" />
        @elseif (session('deleted'))
            <x-flashMsg msg="{{ session('deleted') }}" bg="bg-red-500" />
        @endif
    </div>

    <div class="mb-4">
        <a href="{{ route('galleries.create') }}" class="text-sm bg-slate-600 text-white p-2 rounded-lg flex items-center w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 mr-1">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Add Image
        </a>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
        <div class="p-5">
            <h2 class="text-lg font-semibold text-left text-gray-900">Room Gallery</h2>
            <p class="mt-1 text-sm font-normal text-gray-500">Browse all images in the room gallery.</p>

            <form id="search-form" class="mt-4">
                <input type="text" name="search" id="search-input" placeholder="Search images..."
                    class="p-2 border text-sm rounded w-full focus:ring-pink-600 mb-1"
                    value="{{ request('search') }}" />
                <button type="submit"
                    class="p-2 bg-blue-500 text-white rounded w-full text-sm hover:bg-blue-700">Search</button>
            </form>
        </div>

        <div id="gallery-grid" class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($roomGallery as $image)
                    <div class="bg-white border rounded-lg overflow-hidden shadow-md">
                        <div class="aspect-square relative group">
                            <img src="{{ asset($image->image_path) }}"
                                 alt="{{ $image->name }}"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />

                            <!-- Overlay with actions -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center space-x-2">
                                <!-- View Button -->
                                <button onclick="openImageModal('{{ asset($image->image_path) }}', '{{ $image->name }}')"
                                        class="bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button onclick="showConfirmationModal('Are you sure you want to delete this image?', 'delete-form-{{ $image->id }}')"
                                        class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <form id="delete-form-{{ $image->id }}" action="{{ route('galleries.destroy', $image) }}"
                                    method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $image->name }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Image View Modal -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
            <div class="max-w-4xl w-full mx-4">
                <div class="relative">
                    <img id="modalImage" src="" alt="" class="w-full h-auto rounded-lg">
                    <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <h3 id="modalImageName" class="text-white text-xl font-semibold absolute bottom-4 left-4 bg-black bg-opacity-50 px-4 py-2 rounded"></h3>
                </div>
            </div>
        </div>

        <x-ConfirmationModal />

        <div class="p-4">
            {{ $roomGallery->links() }}
        </div>
    </div>

    <script src="{{ asset('js/confirmation.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeSearch('search-form', 'gallery-grid', '{{ route('galleries.index') }}');
        });

        function openImageModal(imageSrc, imageName) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalImageName = document.getElementById('modalImageName');

            modalImage.src = imageSrc;
            modalImageName.textContent = imageName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Close modal when clicking outside the image
            modal.onclick = function(e) {
                if (e.target === modal) {
                    closeImageModal();
                }
            };
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-admin-layout>
