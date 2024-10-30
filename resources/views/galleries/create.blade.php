<x-admin-layout>
    <div class="container mx-auto px-4 py-8 w-full sm:w-1/2 border rounded-lg bg-white mt-0 sm:mt-10">
        <a href="{{ route('galleries.index') }}" class="text-blue-500 text-sm underline">&larr; back to gallery</a>
        <h1 class="text-3xl font-bold text-slate-700 mt-4">Add Gallery Image</h1>
        <p class="text-sm text-slate-500 mb-6">Upload new image or select from assets</p>

        <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col w-full justify-center">
            @csrf
            <div class="mb-4 w-full">
                <label for="name" class="block text-gray-700 font-bold mb-2 text-sm">Image Name</label>
                <input type="text" name="name" id="name"
                    class="w-full text-gray-600 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500"
                    placeholder="Enter image name" required>
            </div>
            @error('name')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <!-- Image Selection Tabs -->
            <div class="mb-4">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex">
                        <button type="button" onclick="switchTab('upload')"
                            class="tab-button w-1/2 py-2 px-1 text-center border-b-2 font-medium text-sm"
                            id="upload-tab">
                            Upload New
                        </button>
                        <button type="button" onclick="switchTab('existing')"
                            class="tab-button w-1/2 py-2 px-1 text-center border-b-2 font-medium text-sm"
                            id="existing-tab">
                            Select Existing
                        </button>
                    </nav>
                </div>

                <!-- Upload New Image Section -->
                <div id="upload-section" class="mt-4">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Upload Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                        <div class="space-y-1 text-center">
                            <img id="preview" class="mx-auto h-48 w-auto hidden mb-4">
                            <div class="flex text-sm text-gray-600">
                                <label for="image-upload"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Upload a file</span>
                                    <input id="image-upload" name="image" type="file" class="sr-only" accept="image/*"
                                        onchange="previewImage(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                </div>

                <!-- Select Existing Image Section -->
                <div id="existing-section" class="mt-4 hidden">
                    <label class="block text-gray-700 font-bold mb-2 text-sm">Select Existing Image</label>
                    <div class="grid grid-cols-3 gap-4 mt-2">
                        @foreach (File::files(public_path('img')) as $file)
                            @php
                                $filename = basename($file);
                                $path = 'img/' . $filename;
                            @endphp
                            <div class="relative">
                                <input type="radio" name="existing_image" value="{{ $path }}" id="{{ $filename }}"
                                    class="hidden peer" onchange="showExistingImage('{{ asset($path) }}')">
                                <label for="{{ $filename }}"
                                    class="block aspect-square border-2 border-gray-200 rounded-lg overflow-hidden peer-checked:border-blue-500 cursor-pointer">
                                    <img src="{{ asset($path) }}" alt="{{ $filename }}"
                                        class="w-full h-full object-cover">
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @error('image')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror
            @error('existing_image')
                <p class="text-sm text-red-600 mb-4">{{ $message }}</p>
            @enderror

            <div>
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    Add to Gallery
                </button>
            </div>
        </form>
    </div>

    <script>
        function switchTab(tab) {
            const uploadSection = document.getElementById('upload-section');
            const existingSection = document.getElementById('existing-section');
            const uploadTab = document.getElementById('upload-tab');
            const existingTab = document.getElementById('existing-tab');

            if (tab === 'upload') {
                uploadSection.classList.remove('hidden');
                existingSection.classList.add('hidden');
                uploadTab.classList.add('border-blue-500', 'text-blue-600');
                uploadTab.classList.remove('border-transparent', 'text-gray-500');
                existingTab.classList.remove('border-blue-500', 'text-blue-600');
                existingTab.classList.add('border-transparent', 'text-gray-500');
            } else {
                uploadSection.classList.add('hidden');
                existingSection.classList.remove('hidden');
                existingTab.classList.add('border-blue-500', 'text-blue-600');
                existingTab.classList.remove('border-transparent', 'text-gray-500');
                uploadTab.classList.remove('border-blue-500', 'text-blue-600');
                uploadTab.classList.add('border-transparent', 'text-gray-500');
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function showExistingImage(path) {
            const preview = document.getElementById('preview');
            preview.src = path;
            preview.classList.remove('hidden');
        }

        // Initialize the first tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            switchTab('upload');
        });
    </script>
</x-admin-layout>
