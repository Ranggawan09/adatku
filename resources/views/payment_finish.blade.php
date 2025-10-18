@extends('layouts.myapp') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
<div class="flex flex-col justify-center items-center gap-8 my-12 mx-auto max-w-screen-xl">
    <div class="bg-white p-8 rounded-lg shadow-lg text-center md:w-1/2 w-full mx-4">
        <svg class="w-16 h-16 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h2 class="text-3xl font-bold mb-2">Terima Kasih!</h2>
        <p class="text-gray-600 mb-6">
            Kami sedang memproses pembayaran Anda. Status pembayaran akan diperbarui secara otomatis.
        </p>
        <div class="border-t pt-6 text-left">
            <p class="mb-2"><span class="font-semibold">ID Reservasi:</span> #{{ $reservation->id }}</p>
            <p class="mb-2"><span class="font-semibold">Item:</span> {{ $reservation->pakaianAdat->nama }}</p>
            <p class="mb-4"><span class="font-semibold">Total:</span> Rp{{ number_format($reservation->total_price, 0, ',', '.') }}</p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('invoice', $reservation->id) }}" target="_blank" class="w-full sm:w-auto inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300 text-center">
                    Cetak Invoice
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition duration-300 text-center">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection