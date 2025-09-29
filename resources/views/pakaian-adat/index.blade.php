@extends('layouts.myapp')
@section('content')
    <div class="mx-auto max-w-screen-xl mt-10 px-4">
        <!-- Search Bar -->
        <form action="{{ route('pakaianAdat') }}" method="GET" class="mb-4">
            <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Cari</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" name="nama" id="default-search" value="{{ request('nama') }}" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-jawa-500 focus:border-jawa-500" placeholder="Cari nama pakaian adat...">
                <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-jawa-500 hover:bg-jawa-600 focus:ring-4 focus:outline-none focus:ring-jawa-300 font-medium rounded-lg text-sm px-4 py-2">Cari</button>
            </div>
        </form>

        <!-- Filter Section -->
        <div class="bg-gray-200 p-4 rounded-md shadow-xl">
            <form action="{{ route('pakaianAdat') }}" method="GET">
                <input type="hidden" name="nama" value="{{ request('nama') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
                    <!-- Filter by Jenis -->
                    <select name="jenis" class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-jawa-500 sm:text-sm">
                        <option value="">Semua Jenis</option>
                        <option value="Pria" @selected(request('jenis') == 'Pria')>Pria</option>
                        <option value="Wanita" @selected(request('jenis') == 'Wanita')>Wanita</option>
                        <option value="Anak Laki-Laki" @selected(request('jenis') == 'Anak Laki-Laki')>Anak Laki-Laki</option>
                        <option value="Anak Perempuan" @selected(request('jenis') == 'Anak Perempuan')>Anak Perempuan</option>
                    </select>
                    <!-- Filter by Asal Daerah -->
                    <input type="text" placeholder="Asal Daerah" name="asal" value="{{ request('asal') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-jawa-500 sm:text-sm">
                    <!-- Filter by Warna -->
                    <input type="text" placeholder="Warna" name="warna" value="{{ request('warna') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-jawa-500 sm:text-sm">
                    <!-- Filter by Min Price -->
                    <input type="number" placeholder="Harga Minimum" name="min_price" value="{{ request('min_price') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-jawa-500 sm:text-sm">
                    <!-- Filter by Max Price -->
                    <input type="number" placeholder="Harga Maksimum" name="max_price" value="{{ request('max_price') }}" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-jawa-500 sm:text-sm">
                    <!-- Submit Button -->
                    <button class="bg-jawa-500 rounded-md text-white p-2 w-full font-medium hover:bg-jawa-600" type="submit">Filter</button>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-6 mb-2 mx-auto max-w-screen-xl px-4">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-items-center">
        @forelse ($pakaianAdats as $pakaianAdat)
            <div class="relative flex w-full max-w-xs flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md">
                <a class="relative mx-2 mt-2 flex aspect-square overflow-hidden rounded-xl" href="{{ route('pakaian-adat.reservation', ['pakaianAdat' => $pakaianAdat->id]) }}">
                    <img loading="lazy" class="object-cover w-full" src="{{ Storage::url($pakaianAdat->image) }}" alt="product image" />
                    <span
                        class="absolute top-0 left-0 m-2 rounded-full bg-jawa-500 px-2 py-0.5 text-center text-xs font-medium text-white">{{ $pakaianAdat->reduce }}
                        %
                        OFF</span>
                </a>
                <div class="mt-2 px-3 pb-3">
                    <div>
                        <h5 class="font-bold text-base tracking-tight text-pr-900 truncate">{{ $pakaianAdat->nama }} {{ $pakaianAdat->jenis }}
                            {{ $pakaianAdat->asal }}</h5>
                    </div>
                    <div class="mt-1 mb-3 flex items-center justify-between">
                        <p>
                            <span class="text-xl font-bold text-pr-900">Rp{{ number_format($pakaianAdat->price_per_day, 0, ',', '.') }}</span>
                            <span class="text-xs text-pr-900 line-through">Rp{{ number_format(intval(($pakaianAdat->price_per_day * 100) / (100 - $pakaianAdat->reduce)), 0, ',', '.') }}
                            </span>
                        </p>
                    </div>
                    <a href="{{ route('pakaian-adat.reservation', ['pakaianAdat' => $pakaianAdat->id]) }}"
                        class="flex items-center justify-center rounded-md bg-pr-700 hover:bg-jawa-500 px-3 py-2 text-center text-xs font-medium text-white focus:outline-none focus:ring-4 focus:ring-jawa-300">
                        <svg id="thisicon" class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" height="1em"
                            viewBox="0 0 512 512">
                            <style>
                                #thisicon {
                                    fill: #ffffff
                                }
                            </style>
                            <path
                                d="M184 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H96c-35.3 0-64 28.7-64 64v16 48V448c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H376V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H184V24zM80 192H432V448c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V192zm176 40c-13.3 0-24 10.7-24 24v48H184c-13.3 0-24 10.7-24 24s10.7 24 24 24h48v48c0 13.3 10.7 24 24 24s24-10.7 24-24V352h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H280V256c0-13.3-10.7-24-24-24z" />
                        </svg>
                        Reserve </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16">
                <p class="text-2xl font-medium text-gray-500">Pakaian adat tidak ditemukan.</p>
            </div>
        @endforelse
        </div>
    </div>


    <div class="flex justify-center mb-12 w-full">
        {{ $pakaianAdats->links('pagination::tailwind') }}
    </div>
@endsection
