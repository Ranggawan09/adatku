<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RealRentCar') }} - Admin</title>
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

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-jawa-500" aria-label="Sidebar">
            <div class="h-full flex flex-col px-3 py-4 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center ps-9 mb-5">
                    <img loading="lazy" src="/storage/logos/LOGObg.png" class="h-12" alt="Logo" />
                </a>
                <ul class="space-y-2 font-medium flex-grow">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-jawa-700 group {{ request()->routeIs('admin.dashboard') ? 'bg-jawa-700' : '' }}">
                            <svg class="w-5 h-5 text-jawa-300 transition duration-75 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span class="ms-3">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.pakaian-adat.index') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-jawa-700 group {{ request()->routeIs('admin.pakaian-adat.*') ? 'bg-jawa-700' : '' }}">
                            <svg class="w-5 h-5 text-jawa-300 transition duration-75 group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Pakaian Adat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reservations.index') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-jawa-700 group {{ request()->routeIs('admin.reservations.*') ? 'bg-jawa-700' : '' }}">
                            <svg class="w-5 h-5 text-jawa-300 transition duration-75 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Reservasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-jawa-700 group {{ request()->routeIs('admin.users.*') ? 'bg-jawa-700' : '' }}">
                            <svg class="w-5 h-5 text-jawa-300 transition duration-75 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Pelanggan</span>
                        </a>
                    </li>
                </ul>
                <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-jawa-700">
                    <li>
                        <a href="{{ route('admin.settings.edit') }}" class="flex items-center p-2 rounded-lg text-white hover:bg-jawa-700 group {{ request()->routeIs('admin.settings.edit') ? 'bg-jawa-700' : '' }}">
                            <svg class="w-5 h-5 text-jawa-300 transition duration-75 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Pengaturan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
                           class="flex items-center p-2 rounded-lg text-white hover:bg-jawa-700 group">
                            <svg class="w-5 h-5 text-jawa-300 transition duration-75 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="flex-1 ms-3 whitespace-nowrap">Logout</span>
                        </a>
                        <form id="logout-form-sidebar" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main content -->
        <div id="main-content" class="flex-1 flex flex-col overflow-hidden sm:ml-64 transition-all duration-300 ease-in-out">
            <!-- Top bar -->
            <header class="sticky top-0 bg-white shadow-md z-20">
                <div class="flex justify-between items-center px-4 sm:px-6 py-3">
                    <!-- Hamburger Menu untuk Mobile -->
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="text-gray-500 focus:outline-none focus:text-gray-700">
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="relative">
                        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                            class="text-black bg-gray-100 hover:bg-gray-200 font-medium rounded-lg text-sm px-3 py-2.5 text-center inline-flex items-center dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600" type="button">
                            <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('storage/user.png') }}"
                                 alt="user avatar"
                                 class="mr-3 rounded-full object-cover h-6">
                            <p> Admin ( {{ Auth::user()->name }} ) </p>
                            <svg class="w-4 h-4 ml-2" aria-hidden="true" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div id="dropdown"
                            class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownDefaultButton">
                                <li>
                                    <a href="{{ route('home') }}" target="_blank"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">View Site</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.settings.edit') }}"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                                </li>
                                <!-- Logout dipindahkan ke sidebar -->
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 sm:p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white p-4 text-center text-sm text-gray-500 border-t">
                © {{ date('Y') }} <a href="https://www.instagram.com/ranggawan._/" target='_blank' class="hover:underline">Ranggawan</a>. All Rights Reserved.
            </footer>
        </div>
    </div>

    <script>
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const mainContent = document.getElementById('main-content');

            sidebarToggle.addEventListener('click', () => {
                // Toggle sidebar
                const isHidden = sidebar.classList.toggle('-translate-x-full');

                // Toggle responsive classes for proper hiding on all screen sizes
                if (isHidden) {
                    // Saat sidebar disembunyikan
                    sidebar.classList.remove('sm:translate-x-0');
                    mainContent.classList.remove('sm:ml-64');
                } else {
                    // Saat sidebar ditampilkan
                    sidebar.classList.add('sm:translate-x-0');
                    mainContent.classList.add('sm:ml-64');
                }
            });

        });
    </script>
    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @include('flatpickr::components.script')
    @stack('scripts')

</body>

</html>