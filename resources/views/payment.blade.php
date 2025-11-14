@extends('layouts.myapp') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
    {{-- Load script Midtrans Snap.js --}}
    <script type="text/javascript"
        src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <div class="bg-slate-50 min-h-screen flex justify-center items-center p-4 sm:p-6 lg:p-8">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-slate-100 p-6 border-b border-slate-200">
                <h2 class="text-2xl font-bold text-gray-800 text-center">Selesaikan Pembayaran</h2>
                <p class="text-center text-gray-500 mt-1">Satu langkah lagi untuk menyelesaikan pesanan Anda.</p>
            </div>

            <div class="p-6 sm:p-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Ringkasan Pesanan</h3>
                <div class="space-y-4 mb-6">
                    @foreach ($relatedReservations as $item)
                        <div class="flex justify-between items-start">
                            <div class="flex-grow">
                                <p class="font-semibold text-gray-800">{{ $item->pakaianAdat->nama }}</p>
                                <p class="text-sm text-gray-500">Ukuran: {{ $item->variant->size }} (x{{ $item->quantity }})</p>
                            </div>
                            <p class="text-gray-700 font-medium whitespace-nowrap">
                                Rp{{ number_format($item->total_price, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-dashed border-slate-300 my-6"></div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <p class="text-gray-500">Tanggal Sewa:</p>
                        <p class="font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($reservation->start_date)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($reservation->end_date)->format('d M Y') }}
                        </p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-gray-500">Durasi:</p>
                        <p class="font-medium text-gray-800">{{ $reservation->days }} hari</p>
                    </div>
                </div>

                <div class="border-t border-slate-200 my-6"></div>

                <div class="flex justify-between items-center mb-6">
                    <p class="text-lg font-semibold text-gray-800">Total Tagihan</p>
                    <p class="font-bold text-2xl text-pr-500">Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded-md text-sm mb-8">
                    <strong>Perhatian:</strong> Keterlambatan pengembalian akan dikenakan denda sebesar
                    <strong>Rp 50.000</strong> per hari.
                </div>

                <div class="flex justify-center">
                    <button id="pay-button"
                        class="w-full bg-pr-500 hover:bg-pr-600 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-pr-400 focus:ring-opacity-75">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        // Ambil tombol pembayaran
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            // Panggil snap.pay() dengan snap token dari controller
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    /* Anda dapat menangani sukses di sini, misalnya redirect */
                    console.log(result);
                    window.location.href = '{{ route('payment.finish', $reservation->id) }}'
                },
                onPending: function(result) {
                    /* Pelanggan belum menyelesaikan pembayaran */
                    console.log(result);
                    window.location.href = '{{ route('payment.finish', $reservation->id) }}'
                },
                onError: function(result) {
                    /* Terjadi kesalahan */
                    console.log(result);
                    alert("Pembayaran gagal!");
                },
                onClose: function() {
                    /* Pelanggan menutup popup tanpa menyelesaikan pembayaran */
                    alert('Anda menutup popup pembayaran sebelum selesai.');
                }
            });
        });
    </script>
@endsection