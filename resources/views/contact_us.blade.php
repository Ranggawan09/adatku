@extends('layouts.myapp')
@section('content')
<div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-xl">
    <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900">Hubungi Kami</h2>
    <p class="mb-8 lg:mb-16 font-light text-center text-gray-500 sm:text-xl">Punya masalah teknis? Ingin mengirim masukan? Atau ada pertanyaan lain? Beri tahu kami.</p>

    <div class="flex justify-center">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16 max-w-4xl">
        <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 p-3 bg-jawa-100 rounded-full">
                    <svg class="w-6 h-6 text-jawa-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-1 4h1m5-4h1m-1 4h1m-1-4h1m-1 4h1"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Perusahaan</h3>
                    <p class="text-gray-600">Adatku</p>
                    <p class="text-gray-600">Jombang, Jawa Timur</p>
                </div>
            </div>
        <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 p-3 bg-jawa-100 rounded-full">
                    <svg class="w-6 h-6 text-jawa-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Alamat</h3>
                    <p class="text-gray-600">Jl. Dukuh, Desa Dukuhklopo, Peterongan,</p>
                    <p class="text-gray-600">Kab. Jombang, Jawa Timur 61481</p>
                </div>
            </div>
        <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 p-3 bg-jawa-100 rounded-full">
                    <svg class="w-6 h-6 text-jawa-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Jam Operasional</h3>
                    <p class="text-gray-600">Senin - Sabtu: 08:00 - 17:00</p>
                    <p class="text-gray-600">Minggu & Hari Libur: Tutup</p>
                </div>
            </div>
        <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 p-3 bg-jawa-100 rounded-full">
                    <svg class="w-6 h-6 text-jawa-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Hubungi Kami</h3>
                    <p class="text-gray-600">Hubungi kami untuk berbicara dengan tim kami. Kami selalu siap membantu.</p>
                    <p class="text-jawa-500 font-semibold mt-1">0812-3118-0775</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
