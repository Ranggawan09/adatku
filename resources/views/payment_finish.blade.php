@extends('layouts.myapp') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
    <div class="flex flex-col justify-center items-center gap-8 my-12 mx-auto max-w-screen-xl">
        <div class="bg-white p-8 rounded-lg shadow-lg text-center md:w-1/2 w-full mx-4">

            @if (isset($success) && $success)
                {{-- TAMPILAN JIKA PEMBAYARAN SUKSES --}}
                <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-3xl font-bold mb-2">Terima Kasih!</h2>
                <p class="text-gray-600 mb-6">
                    Pembayaran Anda telah berhasil kami terima. Pesanan Anda sedang kami proses.
                </p>
                <div class="border-t pt-6 text-left">
                    <p class="mb-4"><span class="font-semibold">ID Pesanan:</span> {{ $masterOrderId }}</p>

                    <h3 class="font-semibold text-lg mb-2">Detail Pesanan:</h3>
                    <ul class="list-disc list-inside mb-4 text-gray-600">
                        @foreach ($relatedReservations as $item)
                            <li>
                                {{ $item->pakaianAdat->nama }} (Ukuran: {{ $item->variant->size }}) x
                                {{ $item->quantity }}
                            </li>
                        @endforeach
                    </ul>

                    <p class="mb-4 text-xl"><span class="font-semibold">Total Pembayaran:</span> <span
                            class="font-bold text-pr-400">Rp{{ number_format($totalPrice, 0, ',', '.') }}</span></p>
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-4">
                        <a href="{{ route('invoice', $reservation->id) }}" target="_blank"
                            class="w-full sm:w-auto inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300 text-center">
                            Cetak Invoice
                        </a>
                        <a href="{{ route('home') }}"
                            class="w-full sm:w-auto inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-300 text-center">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            @elseif (isset($isWaiting) && $isWaiting)
                {{-- TAMPILAN SAAT MENUNGGU KONFIRMASI SERVER --}}
                <svg class="w-16 h-16 mx-auto text-blue-500 mb-4 animate-spin" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <h2 class="text-3xl font-bold mb-2">Menunggu Konfirmasi...</h2>
                <p class="text-gray-600 mb-6">
                    Kami sedang menunggu konfirmasi akhir untuk pembayaran Anda dengan ID Pesanan
                    <strong>{{ $order_id }}</strong>. Mohon jangan tutup halaman ini.
                </p>
                <p class="text-sm text-gray-500">Halaman akan dimuat ulang secara otomatis.</p>
                <script>
                    // Refresh halaman setelah 15 detik untuk memeriksa status terbaru
                    setTimeout(function() {
                        location.reload();
                    }, 15000);
                </script>
            @elseif (isset($isPending) && $isPending)
                {{-- TAMPILAN JIKA PEMBAYARAN PENDING --}}
                <svg class="w-16 h-16 mx-auto text-yellow-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-3xl font-bold mb-2">Pembayaran Tertunda</h2>
                <p class="text-gray-600 mb-6">{{ $message }}</p>
                <p class="mb-4"><span class="font-semibold">ID Pesanan:</span> {{ $order_id }}</p>
                <a href="{{ route('home') }}"
                    class="w-full sm:w-auto inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-300 text-center">
                    Kembali ke Beranda
                </a>
            @else
                {{-- TAMPILAN JIKA PEMBAYARAN GAGAL --}}
                <svg class="w-16 h-16 mx-auto text-red-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h2 class="text-3xl font-bold mb-2">Pembayaran Gagal</h2>
                <p class="text-gray-600 mb-6">{{ $message ?? 'Terjadi kesalahan saat memproses pembayaran Anda.' }}</p>
                <p class="mb-4"><span class="font-semibold">ID Pesanan:</span> {{ $order_id }}</p>
                <a href="{{ route('home') }}"
                    class="w-full sm:w-auto inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-300 text-center">
                    Kembali ke Beranda
                </a>
            @endif
        </div>
    </div>
@endsection