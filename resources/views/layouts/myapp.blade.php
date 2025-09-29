<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RealRentCar') }}</title>
    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('flatpickr::components.style')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="bg-sec-400">

    {{-- -------------------------------------------------------------- Header -------------------------------------------------------------- --}}
    <header class="bg-white/80 backdrop-blur-sm shadow-sm sticky top-0 z-50">
        <nav class="px-4 lg:px-6 py-4">
            <div class="flex flex-wrap justify-between items-center mx-auto">
                {{-- LOGO --}}
                <a href="{{ route('home') }}" class="flex items-center">
                    <img loading="lazy" src="/storage/logos/LOGO.png" class="mr-3 h-10 sm:h-12" alt="Adatku Logo" />
                </a>

                {{-- Mobile menu button --}}
                <div class="flex items-center lg:hidden">
                    <button data-collapse-toggle="mobile-menu-2" type="button"
                        class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                {{-- Menu --}}
                <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                        <li>
                            <a href="/" class="relative block py-2 pr-4 pl-3 text-gray-700 group">
                                <span>Beranda</span>
                                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-jawa-400 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-in-out"></span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pakaianAdat') }}" class="relative block py-2 pr-4 pl-3 text-gray-700 group">
                                <span>Pakaian Adat</span>
                                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-jawa-400 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-in-out"></span>
                            </a>
                        </li>
                        <li>
                            <a href="/location" class="relative block py-2 pr-4 pl-3 text-gray-700 group">
                                <span>Lokasi</span>
                                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-jawa-400 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-in-out"></span>
                            </a>
                        </li>
                        <li>
                            <a href="/contact_us" class="relative block py-2 pr-4 pl-3 text-gray-700 group">
                                <span>Hubungi Kami</span>
                                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-jawa-400 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 ease-in-out"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    {{-- --------------------------------------------------------------- Main  --------------------------------------------------------------- --}}
    <main>
        @yield('content')
    </main>
    {{-- --------------------------------------------------------------- Footer  --------------------------------------------------------------- --}}
    <footer class="bg-gray-800 text-gray-400 rounded-t-2xl">
        <div class="mx-auto px-4 py-16 sm:px-6 lg:px-8 relative">
            <div class="md:flex md:justify-between">
                <div class="mb-12 md:mb-0 flex justify-center md:justify-start">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img loading="lazy" src="/storage/logos/LOGO.png" class="mr-3 h-10 md:h-20" alt="Adatku Logo" />
                    </a>
                    <h2 class="px-4 text-lg font-semibold flex items-center">By</h2>
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img loading="lazy" src="/storage/logos/gps.png" class="mr-3 h-10 md:h-20" alt="GPS Logo" />
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-8 text-center md:text-left">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold uppercase text-white">Ikuti Kami</h2>
                        <div class="flex space-x-5 justify-center md:justify-start">
                            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white">
                                <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M2 6C2 3.79086 3.79086 2 6 2H18C20.2091 2 22 3.79086 22 6V18C22 20.2091 20.2091 22 18 22H6C3.79086 22 2 20.2091 2 18V6ZM6 4C4.89543 4 4 4.89543 4 6V18C4 19.1046 4.89543 20 6 20H12V13H11C10.4477 13 10 12.5523 10 12C10 11.4477 10.4477 11 11 11H12V9.5C12 7.567 13.567 6 15.5 6H16.1C16.6523 6 17.1 6.44772 17.1 7C17.1 7.55228 16.6523 8 16.1 8H15.5C14.6716 8 14 8.67157 14 9.5V11H16.1C16.6523 11 17.1 11.4477 17.1 12C17.1 12.5523 16.6523 13 16.1 13H14V20H18C19.1046 20 20 19.1046 20 18V6C20 4.89543 19.1046 4 18 4H6Z" fill="#c9a899"></path> </g></svg>
                                <span class="sr-only">Facebook page</span>
                            </a>
                            <a href="https://instagram.com/ranggawan._" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white">
                                <svg class="w-10 h-10" viewBox="-1.2 -1.2 26.40 26.40" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18ZM12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z" fill="#c9a899"></path> <path d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z" fill="#c9a899"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M1.65396 4.27606C1 5.55953 1 7.23969 1 10.6V13.4C1 16.7603 1 18.4405 1.65396 19.7239C2.2292 20.8529 3.14708 21.7708 4.27606 22.346C5.55953 23 7.23969 23 10.6 23H13.4C16.7603 23 18.4405 23 19.7239 22.346C20.8529 21.7708 21.7708 20.8529 22.346 19.7239C23 18.4405 23 16.7603 23 13.4V10.6C23 7.23969 23 5.55953 22.346 4.27606C21.7708 3.14708 20.8529 2.2292 19.7239 1.65396C18.4405 1 16.7603 1 13.4 1H10.6C7.23969 1 5.55953 1 4.27606 1.65396C3.14708 2.2292 2.2292 3.14708 1.65396 4.27606ZM13.4 3H10.6C8.88684 3 7.72225 3.00156 6.82208 3.0751C5.94524 3.14674 5.49684 3.27659 5.18404 3.43597C4.43139 3.81947 3.81947 4.43139 3.43597 5.18404C3.27659 5.49684 3.14674 5.94524 3.0751 6.82208C3.00156 7.72225 3 8.88684 3 10.6V13.4C3 15.1132 3.00156 16.2777 3.0751 17.1779C3.14674 18.0548 3.27659 18.5032 3.43597 18.816C3.81947 19.5686 4.43139 20.1805 5.18404 20.564C5.49684 20.7234 5.94524 20.8533 6.82208 20.9249C7.72225 20.9984 8.88684 21 10.6 21H13.4C15.1132 21 16.2777 20.9984 17.1779 20.9249C18.0548 20.8533 18.5032 20.7234 18.816 20.564C19.5686 20.1805 20.1805 19.5686 20.564 18.816C20.7234 18.5032 20.8533 18.0548 20.9249 17.1779C20.9984 16.2777 21 15.1132 21 13.4V10.6C21 8.88684 20.9984 7.72225 20.9249 6.82208C20.8533 5.94524 20.7234 5.49684 20.564 5.18404C20.1805 4.43139 19.5686 3.81947 18.816 3.43597C18.5032 3.27659 18.0548 3.14674 17.1779 3.0751C16.2777 3.00156 15.1132 3 13.4 3Z" fill="#c9a899"></path> </g></svg>
                                <span class="sr-only">Instagram page</span>
                            </a>
                            <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" class="text-gray-400 hover:text-white">
                                <svg class="w-10 h-10" width="205px" height="205px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M21 8V16C21 18.7614 18.7614 21 16 21H8C5.23858 21 3 18.7614 3 16V8C3 5.23858 5.23858 3 8 3H16C18.7614 3 21 5.23858 21 8Z" stroke="#c9a899" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M10 12C8.34315 12 7 13.3431 7 15C7 16.6569 8.34315 18 10 18C11.6569 18 13 16.6569 13 15V6C13.3333 7 14.6 9 17 9" stroke="#c9a899" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                                <span class="sr-only">TikTok page</span>
                            </a>
                        </div>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold  uppercase text-white">Legal</h2>
                        <ul class=" text-gray-400">
                            <li class="mb-4">
                                <a href="{{ route('privacy_policy') }}" class="hover:underline">Kebijakan Privasi</a>
                            </li>
                            <li>
                                <a href="{{ route('terms_conditions') }}" class="hover:underline">Syarat & Ketentuan</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-gray-700 sm:mx-auto lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-center text-center">
                <span class="text-sm text-gray-400">© {{ date('Y') }} <a
                        href="https://www.instagram.com/ranggawan._/" target='_blank'
                        class="hover:underline">Ranggawan</a>. All Rights Reserved.</span>
            </div>
        </div>
    </footer>

    {{-- Scroll to Top Button --}}
    <button id="scroll-to-top" onclick="scrollToTop();"
        class="fixed bottom-5 right-5 z-50 p-3 bg-jawa-400 text-white rounded-full shadow-lg hover:bg-jawa-500 transition-all duration-300 opacity-0 pointer-events-none"
        aria-label="Scroll to top">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </button>

</body>
<script>
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Show/hide scroll-to-top button
    const scrollToTopBtn = document.getElementById('scroll-to-top');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
            scrollToTopBtn.classList.add('opacity-100');
        } else {
            scrollToTopBtn.classList.add('opacity-0', 'pointer-events-none');
            scrollToTopBtn.classList.remove('opacity-100');
        }
    });
</script>
@include('flatpickr::components.script')

</html>
