<x-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <div class="bg-white shadow-md rounded-lg p-6 md:p-10 text-center">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-16 h-16 text-green-500 mx-auto mb-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">Thank You!</h1>
            <p class="text-gray-600 mb-4">
                Your reservation has been successfully submitted. We'll notify you with further updates.
            </p>
            <p class="text-gray-600 mb-4">
                For questions or concerns, feel free to contact us at
                <a href="mailto:kandahar365@gmail.com" class="text-blue-500 underline">Kandahar.on</a>.
            </p>
            <a href="{{ route('home') }}"
                class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600">
                Back to Home
            </a>
        </div>
    </div>
</x-layout>
