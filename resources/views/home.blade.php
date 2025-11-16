@extends('layouts.myapp')
@section('content')

        <div class="bg-sec-100 ">
            {{-- hero --}}
            <div class="flex justify-center md:py-10 py-12 mx-auto">
                <div class="flex flex-col justify-start md:justify-center md:w-3/5 px-6 md:px-0 md:ms-20">
                    <h1 class="md:text-start text-center font-pakaianAdat font-bold text-gray-900 mb-6 md:mb-8 text-4xl md:text-7xl leading-tight"><span class="text-pr-400">CEPAT DAN MUDAH</span> UNTUK MENYEWA
                        PAKAIAN ADAT</h1>
                <div class="w-4/5 mx-auto md:w-3/5 md:hidden my-6">
                    <img loading="lazy" src="/storage/home.png" alt="home pakaianAdat" class="w-full h-auto rounded-lg shadow-lg">
                    </div>
                    <p class="text-justify text-gray-600 dark:text-gray-400 text-base md:text-lg">Baik Anda merencanakan acara spesial, pernikahan, atau sekadar ingin tampil beda, kami siap membantu. Dengan beragam pilihan pakaian adat dan sistem pemesanan yang praktis, menyewa pakaian adat tidak pernah semudah ini.</p>
                    <div class="flex flex-col sm:flex-row justify-center md:justify-start mt-8 md:mt-12 gap-4">
                        <a href="{{ route('pakaianAdat') }}" class="w-full sm:w-auto">
                            <button
                                class="w-full sm:w-auto bg-pr-600 p-3 border-2 border-pr-600 rounded-md text-white hover:bg-pr-400 font-bold transition-colors duration-300">SEWA SEKARANG</button>
                        </a>
                        <a href="/contact_us" class="w-full sm:w-auto">
                            <button class="w-full sm:w-auto border-2 border-pr-400 text-black p-3 rounded-md hover:bg-pr-500 hover:text-white font-bold transition-colors duration-300">HUBUNGI KAMI</button>
                        </a>
                    </div>
                </div>
                <div class="md:w-3/5 hidden md:block  ">
                    <img loading="lazy" src="/storage/home.png" alt="home pakaianAdat">
                </div>

            </div>

            {{-- pakaian_adats Section --}}
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-center">
                    <hr class="mt-8 h-0.5 w-full bg-pr-500">
                    <p class="my-2 mx-4 p-2 font-pakaianAdat font-bold text-pr-400 text-xl text-center whitespace-nowrap">KOLEKSI KAMI</p>
                    <hr class="mt-8 h-0.5 w-full bg-pr-500">
                    <hr>
                </div>
                <div class="mb-4 flex justify-end">
                    <a href="{{ route('pakaianAdat') }}">
                        <button
                            class="border-2 border-pr-400 text-black px-4 py-2 rounded-md hover:bg-pr-400 hover:text-white transition-colors duration-300">Lihat Semua</button>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6 md:gap-8 p-4 mx-auto">
                @foreach ($pakaianAdats as $pakaianAdat)
                    <div
                        class="relative flex w-full flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md transition-transform duration-300 hover:scale-105">
                        <a class="relative mx-3 mt-3 flex h-60 overflow-hidden rounded-xl" href="{{ route('pakaian-adat.reservation', ['pakaianAdat' => $pakaianAdat->id]) }}"
                           aria-label="Lihat detail {{ $pakaianAdat->nama }}">
                            <img loading="lazy" class="h-full w-full object-cover" src="{{ Storage::url($pakaianAdat->image) }}" alt="Foto {{ $pakaianAdat->nama }}" />
                            <span
                                class="absolute top-0 left-0 m-2 rounded-full bg-pr-400 px-2 text-center text-sm font-medium text-white">{{ $pakaianAdat->reduce }}
                                %
                                OFF</span>
                        </a>
                        <div class="mt-4 px-5 pb-5">
                            <div >
                                <h5 class="font-bold text-xl tracking-tight text-pr-900 truncate">{{ $pakaianAdat->nama }}
                                    {{ $pakaianAdat->jenis }}
                                </h5>
                                <p class="text-sm text-gray-500">{{ $pakaianAdat->asal }}</p>
                            </div>
                            <div class="mt-2 mb-5 flex items-center justify-between">
                                <p>
                                    <span class="text-3xl font-bold text-pr-900">Rp{{ number_format($pakaianAdat->price_per_day, 0, ',', '.') }}</span>
                                    @if($pakaianAdat->reduce > 0)
                                        @php
                                            $originalPrice = ($pakaianAdat->reduce < 100) ? ($pakaianAdat->price_per_day * 100) / (100 - $pakaianAdat->reduce) : $pakaianAdat->price_per_day;
                                        @endphp
                                        <span class="text-sm text-pr-900 line-through">Rp{{ number_format($originalPrice, 0, ',', '.') }}</span>
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('pakaian-adat.reservation', ['pakaianAdat' => $pakaianAdat->id]) }}"
                                class="flex items-center justify-center rounded-md bg-pr-700 hover:bg-pr-400 px-5 py-2.5 text-center text-sm font-medium text-white focus:outline-none focus:ring-4 focus:ring-blue-300 transition-colors duration-300">
                                <svg id="thisicon" class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                                    <style>
                                        #thisicon {
                                            fill: #ffffff
                                        }
                                    </style>
                                    <path
                                        d="M184 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H96c-35.3 0-64 28.7-64 64v16 48V448c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H376V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H184V24zM80 192H432V448c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V192zm176 40c-13.3 0-24 10.7-24 24v48H184c-13.3 0-24 10.7-24 24s10.7 24 24 24h48v48c0 13.3 10.7 24 24 24s24-10.7 24-24V352h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H280V256c0-13.3-10.7-24-24-24z" />
                                </svg>
                                Pesan Sekarang</a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Our numbers section --}}
            <div class="mx-auto px-4 sm:px-6 lg:px-8 my-16 md:my-24">
                <div class="px-4 mb-8 md:mb-12">
                    <h2 class="text-center font-pakaianAdat text-3xl md:text-4xl font-medium text-pr-400"> <span
                            class=" text-gray-900">Angka</span> Kami</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="bg-gradient-to-br from-jawa-400 to-jawa-900 text-white p-8 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                        <h3 class="font-pakaianAdat font-bold text-7xl text-pr-200">30+</h3>
                        <p class="font-pakaianAdat text-2xl mt-2">Koleksi Pakaian</p>
                    </div>
                    <div class="bg-gradient-to-br from-jawa-400 to-jawa-900 text-white p-8 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                        <h3 class="font-pakaianAdat font-bold text-7xl text-pr-200">4500+</h3>
                        <p class="font-pakaianAdat text-2xl mt-2">Klien Puas</p>
                    </div>
                    <div class="bg-gradient-to-br from-jawa-400 to-jawa-900 text-white p-8 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                        <h3 class="font-pakaianAdat font-bold text-7xl text-pr-200">7000+</h3>
                        <p class="font-pakaianAdat text-2xl mt-2">Reservasi</p>
                    </div>
                </div>
            </div>


            {{-- Why us section  --}}
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="font-pakaianAdat text-3xl md:text-4xl font-medium text-pr-400"> <span
                            class=" text-gray-900">Kenapa</span> Pilih Kami</h2>
                </div>
                <div class="mt-7 mb-16">
                    <p class="md:text-center text-lg text-justify text-gray-600 dark:text-gray-400 max-w-4xl mx-auto">Kami memprioritaskan kepuasan Anda dan berusaha untuk membuat pengalaman sewa pakaian adat Anda semudah mungkin. Dengan beragam pilihan koleksi yang terawat baik, harga kompetitif, dan proses pemesanan yang sederhana, Anda dapat mempercayai kami untuk memenuhi kebutuhan Anda.</p>
                </div>

                <div
                    class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 my-8">
                    <div class="flex items-start gap-4">
                        <div class="bg-jawa-200 p-3 rounded-lg flex-shrink-0">
                            <svg class="fill-jawa-500" xmlns="http://www.w3.org/2000/svg" height="2.5em" viewBox="0 0 512 512">
                                <path
                                    d="M280 0C408.1 0 512 103.9 512 232c0 13.3-10.7 24-24 24s-24-10.7-24-24c0-101.6-82.4-184-184-184c-13.3 0-24-10.7-24-24s10.7-24 24-24zm8 192a32 32 0 1 1 0 64 32 32 0 1 1 0-64zm-32-72c0-13.3 10.7-24 24-24c75.1 0 136 60.9 136 136c0 13.3-10.7 24-24 24s-24-10.7-24-24c0-48.6-39.4-88-88-88c-13.3 0-24-10.7-24-24zM117.5 1.4c19.4-5.3 39.7 4.6 47.4 23.2l40 96c6.8 16.3 2.1 35.2-11.6 46.3L144 207.3c33.3 70.4 90.3 127.4 160.7 160.7L345 318.7c11.2-13.7 30-18.4 46.3-11.6l96 40c18.6 7.7 28.5 28 23.2 47.4l-24 88C481.8 499.9 466 512 448 512C200.6 512 0 311.4 0 64C0 46 12.1 30.2 29.5 25.4l88-24z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-pakaianAdat font-bold text-gray-900 text-2xl">Dukungan Pelanggan</h3>
                            <p class="font-pakaianAdat text-gray-700 text-sm">Tim kami yang berdedikasi siap memberikan dukungan pelanggan yang luar biasa kapan pun Anda membutuhkannya.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="bg-jawa-200 p-3 rounded-lg flex-shrink-0">
                            <svg class="fill-jawa-500 w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-pakaianAdat font-bold text-gray-900 text-2xl">Koleksi Terbaik</h3>
                            <p class="font-pakaianAdat text-gray-700 text-sm">Rasakan pengalaman menggunakan koleksi pakaian adat terbaik kami yang pasti akan meninggalkan kesan mendalam.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="bg-jawa-200 p-3 rounded-lg flex-shrink-0">
                            <svg class="fill-jawa-500" xmlns="http://www.w3.org/2000/svg" height="2.5em" viewBox="0 0 640 512">
                                <path
                                    d="M323.4 85.2l-96.8 78.4c-16.1 13-19.2 36.4-7 53.1c12.9 17.8 38 21.3 55.3 7.8l99.3-77.2c7-5.4 17-4.2 22.5 2.8s4.2 17-2.8 22.5l-20.9 16.2L512 316.8V128h-.7l-3.9-2.5L434.8 79c-15.3-9.8-33.2-15-51.4-15c-21.8 0-43 7.5-60 21.2zm22.8 124.4l-51.7 40.2C263 274.4 217.3 268 193.7 235.6c-22.2-30.5-16.6-73.1 12.7-96.8l83.2-67.3c-11.6-4.9-24.1-7.4-36.8-7.4C234 64 215.7 69.6 200 80l-72 48V352h28.2l91.4 83.4c19.6 17.9 49.9 16.5 67.8-3.1c5.5-6.1 9.2-13.2 11.1-20.6l17 15.6c19.5 17.9 49.9 16.6 67.8-2.9c4.5-4.9 7.8-10.6 9.9-16.5c19.4 13 45.8 10.3 62.1-7.5c17.9-19.5 16.6-49.9-2.9-67.8l-134.2-123zM16 128c-8.8 0-16 7.2-16 16V352c0 17.7 14.3 32 32 32H64c17.7 0 32-14.3 32-32V128H16zM48 320a16 16 0 1 1 0 32 16 16 0 1 1 0-32zM544 128V352c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V144c0-8.8-7.2-16-16-16H544zm32 208a16 16 0 1 1 32 0 16 16 0 1 1 -32 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-pakaianAdat font-bold text-gray-900 text-2xl">Pembatalan Mudah</h3>
                            <p class="font-pakaianAdat text-gray-700 text-sm">Nikmati fleksibilitas pembatalan yang mudah, memberikan Anda ketenangan jika rencana berubah.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="bg-jawa-200 p-3 rounded-lg flex-shrink-0">
                            <svg class="fill-jawa-500" xmlns="http://www.w3.org/2000/svg" height="2.5em" viewBox="0 0 576 512">
                                <path
                                    d="M312 24V34.5c6.4 1.2 12.6 2.7 18.2 4.2c12.8 3.4 20.4 16.6 17 29.4s-16.6 20.4-29.4 17c-10.9-2.9-21.1-4.9-30.2-5c-7.3-.1-14.7 1.7-19.4 4.4c-2.1 1.3-3.1 2.4-3.5 3c-.3 .5-.7 1.2-.7 2.8c0 .3 0 .5 0 .6c.2 .2 .9 1.2 3.3 2.6c5.8 3.5 14.4 6.2 27.4 10.1l.9 .3 0 0c11.1 3.3 25.9 7.8 37.9 15.3c13.7 8.6 26.1 22.9 26.4 44.9c.3 22.5-11.4 38.9-26.7 48.5c-6.7 4.1-13.9 7-21.3 8.8V232c0 13.3-10.7 24-24 24s-24-10.7-24-24V220.6c-9.5-2.3-18.2-5.3-25.6-7.8c-2.1-.7-4.1-1.4-6-2c-12.6-4.2-19.4-17.8-15.2-30.4s17.8-19.4 30.4-15.2c2.6 .9 5 1.7 7.3 2.5c13.6 4.6 23.4 7.9 33.9 8.3c8 .3 15.1-1.6 19.2-4.1c1.9-1.2 2.8-2.2 3.2-2.9c.4-.6 .9-1.8 .8-4.1l0-.2c0-1 0-2.1-4-4.6c-5.7-3.6-14.3-6.4-27.1-10.3l-1.9-.6c-10.8-3.2-25-7.5-36.4-14.4c-13.5-8.1-26.5-22-26.6-44.1c-.1-22.9 12.9-38.6 27.7-47.4c6.4-3.8 13.3-6.4 20.2-8.2V24c0-13.3 10.7-24 24-24s24 10.7 24 24zM568.2 336.3c13.1 17.8 9.3 42.8-8.5 55.9L433.1 485.5c-23.4 17.2-51.6 26.5-80.7 26.5H192 32c-17.7 0-32-14.3-32-32V416c0-17.7 14.3-32 32-32H68.8l44.9-36c22.7-18.2 50.9-28 80-28H272h16 64c17.7 0 32 14.3 32 32s-14.3 32-32 32H288 272c-8.8 0-16 7.2-16 16s7.2 16 16 16H392.6l119.7-88.2c17.8-13.1 42.8-9.3 55.9 8.5zM193.6 384l0 0-.9 0c.3 0 .6 0 .9 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-pakaianAdat font-bold text-gray-900 text-2xl">Harga Terbaik</h3>
                            <p class="font-pakaianAdat text-gray-700 text-sm">Kami menjamin harga terbaik untuk penyewaan pakaian adat kami, memastikan Anda mendapatkan nilai terbaik.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="bg-jawa-200 p-3 rounded-lg flex-shrink-0">
                            <svg class="fill-jawa-500" xmlns="http://www.w3.org/2000/svg" height="2.5em" viewBox="0 0 576 512">
                                <path
                                    d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384V285.7l-86.8 86.8c-10.3 10.3-17.5 23.1-21 37.2l-18.7 74.9c-2.3 9.2-1.8 18.8 1.3 27.5H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zM549.8 235.7l14.4 14.4c15.6 15.6 15.6 40.9 0 56.6l-29.4 29.4-71-71 29.4-29.4c15.6-15.6 40.9-15.6 56.6 0zM311.9 417L441.1 287.8l71 71L382.9 487.9c-4.1 4.1-9.2 7-14.9 8.4l-60.1 15c-5.5 1.4-11.2-.2-15.2-4.2s-5.6-9.7-4.2-15.2l15-60.1c1.4-5.6 4.3-10.8 8.4-14.9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-pakaianAdat font-bold text-gray-900 text-2xl">Proses Mudah</h3>
                            <p class="font-pakaianAdat text-gray-700 text-sm">Proses kami yang disederhanakan membuat penyewaan pakaian adat menjadi cepat dan mudah, menghemat waktu Anda.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="bg-jawa-200 p-3 rounded-lg flex-shrink-0">
                            <svg class="fill-jawa-500" xmlns="http://www.w3.org/2000/svg" height="2.5em" viewBox="0 0 448 512">
                                <path
                                    d="M128 40c0-22.1 17.9-40 40-40s40 17.9 40 40V188.2c8.5-7.6 19.7-12.2 32-12.2c20.6 0 38.2 13 45 31.2c8.8-9.3 21.2-15.2 35-15.2c25.3 0 46 19.5 47.9 44.3c8.5-7.7 19.8-12.3 32.1-12.3c26.5 0 48 21.5 48 48v48 16 48c0 70.7-57.3 128-128 128l-16 0H240l-.1 0h-5.2c-5 0-9.9-.3-14.7-1c-55.3-5.6-106.2-34-140-79L8 336c-13.3-17.7-9.7-42.7 8-56s42.7-9.7 56 8l56 74.7V40zM240 304c0-8.8-7.2-16-16-16s-16 7.2-16 16v96c0 8.8 7.2 16 16 16s16-7.2 16-16V304zm48-16c-8.8 0-16 7.2-16 16v96c0 8.8 7.2 16 16 16s16-7.2 16-16V304c0-8.8-7.2-16-16-16zm80 16c0-8.8-7.2-16-16-16s-16 7.2-16 16v96c0 8.8 7.2 16 16 16s16-7.2 16-16V304z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-pakaianAdat font-bold text-gray-900 text-2xl">Layanan Digital</h3>
                            <p class="font-pakaianAdat text-gray-700 text-sm">Manfaatkan layanan digital kami yang praktis, membuat pengalaman sewa Anda lebih nyaman dan efisien.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection