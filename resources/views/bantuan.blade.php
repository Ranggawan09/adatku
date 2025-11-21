@extends('layouts.myapp')

@section('content')
<div class="bg-slate-50 py-12 sm:py-16 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                Cara Mudah Menyewa Pakaian Adat
            </h1>
            <p class="mt-4 text-lg leading-8 text-gray-600">
                Ikuti langkah-langkah sederhana di bawah ini untuk pengalaman penyewaan yang lancar.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                {{-- Kolom Gambar --}}
                <div class="relative">
                    <img src="/storage/images/how-to.jpg" alt="Proses Penyewaan Pakaian Adat"
                        class="h-full w-full object-cover">
                    {{-- Anda dapat mengganti `src` gambar di atas dengan gambar yang relevan. --}}
                    {{-- Saya telah menempatkan path placeholder. Pastikan gambar ada di public/storage/images/how-to.jpg --}}
                </div>

                {{-- Kolom Teks Penjelasan --}}
                <div class="p-8 sm:p-10 lg:p-12">
                    <ol class="space-y-8">
                        {{-- Langkah 1 --}}
                        <li class="flex">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-jawa-100 text-jawa-600 rounded-full font-bold text-xl">1</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Pilih Pakaian Adat</h3>
                                <p class="mt-1 text-gray-600">Jelajahi koleksi kami dan pilih pakaian adat yang Anda inginkan dari halaman <a href="{{ route('pakaianAdat') }}" class="text-pr-500 hover:underline">Pakaian Adat</a>.</p>
                            </div>
                        </li>
                        {{-- Langkah 2 --}}
                        <li class="flex">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-jawa-100 text-jawa-600 rounded-full font-bold text-xl">2</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Isi Detail & Tanggal Sewa</h3>
                                <p class="mt-1 text-gray-600">Lengkapi formulir pemesanan dengan data diri, pilih ukuran, jumlah, dan tentukan tanggal mulai hingga selesai sewa.</p>
                            </div>
                        </li>
                        {{-- Langkah 3 --}}
                        <li class="flex">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-jawa-100 text-jawa-600 rounded-full font-bold text-xl">3</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Lakukan Pembayaran</h3>
                                <p class="mt-1 text-gray-600">Selesaikan pembayaran dengan metode yang Anda pilih. Pesanan Anda akan segera kami proses setelah pembayaran berhasil.</p>
                            </div>
                        </li>
                        {{-- Langkah 4 --}}
                        <li class="flex">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-green-100 text-green-600 rounded-full font-bold text-xl">✓</div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Selesai!</h3>
                                <p class="mt-1 text-gray-600">Pakaian adat siap diambil di Griya Paes Salsabila.</p>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection