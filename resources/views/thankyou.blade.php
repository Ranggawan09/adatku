<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thank you</title>
    @vite('resources/css/app.css')
    <link rel="icon" type="image/x-icon" href="/images/logos/LOGOtext.png">
</head>

<body class="bg-slate-100 font-sans">
    <div class="min-h-screen flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl p-8 sm:p-10 text-center">
            <div class="w-24 mx-auto mb-6">
                <img loading="lazy" src="/storage/logos/LOGO.png" alt="Adatku Logo">
            </div>
            <div class="mb-8">
                <h1 class="font-bold text-gray-900 text-4xl sm:text-5xl">Terima Kasih ❤️</h1>
                <p class="text-gray-600 mt-2 text-lg">Terima kasih telah memilih dan mempercayai Adatku.</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-6">
                <h2 class="font-semibold text-xl text-gray-800">Langkah Selanjutnya</h2>
                <p class="text-gray-600 mt-3">
                    Silakan kunjungi toko kami dan tunjukkan faktur reservasi Anda (digital atau cetak) untuk melakukan pembayaran dan pengambilan pakaian adat.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('invoice', ['reservation' => $reservation->id]) }}" target="_blank"
                        class="w-full sm:w-auto px-6 py-3 text-white bg-pr-400 font-semibold rounded-lg hover:bg-pr-500 transition-colors duration-300 flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Unduh Invoice</span>
                    </a>
                    <a href="{{ route('home') }}"
                        class="w-full sm:w-auto px-6 py-3 text-gray-700 bg-gray-200 font-semibold rounded-lg hover:bg-gray-300 transition-colors duration-300 flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
