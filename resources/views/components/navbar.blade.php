<div class="relative">
    <div class="w-full bg-white fixed top-0 left-0 right-0 z-50">
        <div class="container mx-auto px-4 sm:px-10">
            <div class="flex justify-between items-center h-[100px]">
                <p class="font-bold font-mono text-[20px] text-gray-800 sm:text-[30px]">Kandahar Cottages</p>
                <button id="menuButton" class="sm:hidden p-2 z-20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-menu">
                        <line x1="4" x2="20" y1="12" y2="12" />
                        <line x1="4" x2="20" y1="6" y2="6" />
                        <line x1="4" x2="20" y1="18" y2="18" />
                    </svg>
                </button>
                <div id="desktopNav" class="hidden sm:flex sm:gap-5 sm:h-[25px]">
                    <a href="{{route('home')}}" class="hover:border-b-[1px] border-black">Home</a>
                    <a href="{{route('room-list')}}" class="hover:border-b-[1px] border-black">Room</a>
                    <a href="{{route('gallery')}}" class="hover:border-b-[1px] border-black">Gallery</a>
                </div>
            </div>
        </div>
    </div>
    <div id="mobileNav" class="hidden fixed top-0 left-0 right-0 bg-white border-b border-gray-200 shadow-md z-40"
        style="padding-top: 100px;">
        <div class="container mx-auto px-4 sm:px-10 py-4">
            <a href="{{route('home')}}" class="block py-2 hover:bg-gray-100">Home</a>
            <a href="{{route('room-list')}}" class="hover:border-b-[1px] border-black">Room</a>
            <a href="{{route('gallery')}}" class="block py-2 hover:bg-gray-100">Gallery</a>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuButton = document.getElementById('menuButton');
        const mobileNav = document.getElementById('mobileNav');

        menuButton.addEventListener('click', function () {
            mobileNav.classList.toggle('hidden');
        });

        document.addEventListener('click', function (event) {
            if (!menuButton.contains(event.target) && !mobileNav.contains(event.target)) {
                mobileNav.classList.add('hidden');
            }
        });
    });
</script>
