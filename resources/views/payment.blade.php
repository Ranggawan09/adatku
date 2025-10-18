@extends('layouts.myapp') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
    {{-- Load script Midtrans Snap.js --}}
    <script type="text/javascript" src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <div class="flex flex-col justify-center items-center gap-8 my-12 mx-auto max-w-screen-xl">
        <div class="bg-white p-8 rounded-lg shadow-lg text-center md:w-1/2 w-full mx-4">
            <h2 class="text-3xl font-bold mb-4">Selesaikan Pembayaran Anda</h2>
            <p class="text-gray-600 mb-2">Reservasi untuk: <span class="font-semibold">{{ $reservation->pakaianAdat->nama }}</span></p>
            <p class="text-gray-600 mb-6">Total Tagihan: <span class="font-bold text-xl text-pr-400">Rp{{ number_format($reservation->total_price, 0, ',', '.') }}</span></p>

            <div class="border-t pt-6">
                <p class="text-sm text-gray-500 mb-4">Klik tombol di bawah untuk melanjutkan ke pembayaran.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <button id="pay-button" class="w-full sm:w-auto bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                        Bayar Online
                    </button>
                    <form action="{{ route('payment.cod', $reservation->id) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                            Bayar di Tempat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Ambil tombol pembayaran
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            // Panggil snap.pay() dengan snap token dari controller
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    /* Anda dapat menangani sukses di sini, misalnya redirect */
                    console.log(result);
                    window.location.href = '{{ route('payment.finish', $reservation->id) }}'
                },
                onPending: function(result){
                    /* Pelanggan belum menyelesaikan pembayaran */
                    console.log(result);
                    window.location.href = '{{ route('payment.finish', $reservation->id) }}'
                },
                onError: function(result){
                    /* Terjadi kesalahan */
                    console.log(result);
                    alert("Pembayaran gagal!");
                },
                onClose: function(){
                    /* Pelanggan menutup popup tanpa menyelesaikan pembayaran */
                    alert('Anda menutup popup pembayaran sebelum selesai.');
                }
            });
        });
    </script>
@endsection