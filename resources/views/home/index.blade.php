<x-layout>
    <x-navbar />
    <div
        class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-[60svh] flex justify-center items-center text-white pt-[100px]">
        <div class="w-[750px] text-center">
            <span class="text-[60px] font-semibold">Relax and unwind at Kandahar Cottages</span>
            <div class="mb-10">Experience comfort and luxury with hassle-free reservations. Your getaway awaits!
            </div>
            {{-- <a href="#" class="border border-white rounded-full py-3 px-7">Book now</a> --}}
        </div>
    </div>

    <section class="flex flex-col md:flex-row container mx-auto px-5 my-14">
        <!-- Content Section -->
        <div class="w-full my-auto">
            <h1 class="text-[50px] font-semibold text-gray-800">Welcome to Kandahar Cottages</h1>
            <p class="text-gray-700">Experience unparalleled comfort and hospitality at Kandahar Cottages, where we
                offer
                a variety of rooms and services tailored to make your stay unforgettable. Reservations are easy and
                convenient.</p>
            <div class="flex flex-col md:flex-row gap-0 mt-8 sm:gap-10 items-start sm:items-center">
                <div class="text-left sm:text-center">
                    <h1 class="text-blue-900 text-[50px]">
                        {{-- {{ $reservationCount }} --}}
                    </h1>
                    <a href="{{ route('room-list') }}">
                        <p class="text-sm text-gray-700 cursor-pointer">Book now</p>
                    </a>
                </div>

            </div>
        </div>
        <!-- Image Section -->
        <div class="w-full mt-8 md:mt-0">
            <img src="{{ asset('img/tables-sea-view.jpg') }}" class="rounded" alt="">
        </div>
    </section>

    <section>
        <!-- More content here -->
    </section>
    <x-footer />
</x-layout>
