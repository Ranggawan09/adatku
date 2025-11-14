@extends('layouts.myapp')
@section('content')
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">
            {{-- -------------------------------------------- right (now left) -------------------------------------------- --}}
                <div class="md:w-4/12 flex flex-col justify-start items-center p-6 md:p-8 md:border-r md:border-gray-200">
                <div class="relative w-full h-96 overflow-hidden rounded-xl">
                    <img loading="lazy" class="h-full w-full object-cover" src="{{ Storage::url($pakaianAdat->image) }}" alt="product image" />
                    @if ($pakaianAdat->reduce > 0)
                        <span class="absolute top-0 left-0 m-2 rounded-full bg-pr-400 px-2.5 py-1 text-center text-sm font-medium text-white">
                            {{ $pakaianAdat->reduce }}% OFF
                        </span>
                    @endif
                </div>

                <div class="w-full mt-6 text-center md:text-left">
                    <h3 class="font-pakaianAdat font-bold text-2xl text-gray-800 md:hidden">{{ $pakaianAdat->nama }}</h3>
                    <div class="w-full mb-4">
                        <p class="text-gray-600 text-lg">Warna:
                            <span class="font-semibold text-gray-800">{{ $pakaianAdat->warna }}</span>
                        </p>
                    </div>
                    <p class="text-gray-600 text-lg text-justify">{{ $pakaianAdat->deskripsi }}</p>
                </div>
            </div>

            {{-- -------------------------------------------- left -------------------------------------------- --}}
                <div class="md:w-8/12 p-6 md:p-8 md:pl-12">

                <h2 class="font-pakaianAdat font-bold text-3xl md:text-5xl text-gray-800">
                    {{ $pakaianAdat->nama }}
                </h2>
                <p class="text-lg text-gray-500 mt-1">{{ $pakaianAdat->jenis }} - {{ $pakaianAdat->asal }}</p>

                <div class="flex items-baseline mt-6">
                    <p class="text-3xl font-bold text-pr-400">
                        Rp{{ number_format($pakaianAdat->price_per_day, 0, ',', '.') }} / hari
                    </p>
                    @if ($pakaianAdat->reduce > 0)
                        @php
                            $originalPrice = ($pakaianAdat->reduce < 100) ? ($pakaianAdat->price_per_day * 100) / (100 - $pakaianAdat->reduce) : $pakaianAdat->price_per_day;
                        @endphp
                        <span class="text-lg text-gray-500 line-through ml-3">Rp{{ number_format($originalPrice, 0, ',', '.') }}</span>
                    @endif
                </div>

                <div class="flex items-center justify-around my-8">
                    <div class="flex-grow h-px bg-gray-300"></div>
                    <p class="mx-4 text-gray-500 font-semibold">Informasi Pesanan</p>
                    <div class="flex-grow h-px bg-gray-300"></div>
                </div>

                <div>
                    <form id="reservation_form" action="{{ route('pakaian-adat.reservationStore', ['pakaianAdat' => $pakaianAdat->id]) }}"
                        method="POST">
                        @csrf

                        <!-- Size Selection Badges -->
                        <div class="mb-12">
                            <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Pilih Ukuran & Jumlah</label>
                            <div id="size-selector" class="space-y-3">
                                @forelse ($pakaianAdat->variants as $variant)
                                    <div class="variant-item flex items-center gap-4 p-3 border-2 rounded-lg transition-colors duration-200 has-[:checked]:border-pr-400 has-[:checked]:bg-pr-50">
                                        <input type="checkbox" id="variant-{{ $variant->id }}" name="variants[{{ $variant->id }}][id]" value="{{ $variant->id }}" class="variant-checkbox h-5 w-5 rounded border-gray-300 text-pr-400 focus:ring-pr-400 cursor-pointer">
                                        <label for="variant-{{ $variant->id }}" class="flex-1 cursor-pointer">
                                            <span class="block font-semibold text-gray-800">Ukuran: {{ $variant->size }}</span>
                                            <span class="block text-xs text-gray-500">Stok tersedia: {{ $variant->quantity }}</span>
                                        </label>
                                        <input type="number" name="variants[{{ $variant->id }}][quantity]" min="1" max="{{ $variant->quantity }}" placeholder="Jumlah" class="quantity-input block w-24 rounded-md border-gray-300 shadow-sm focus:border-pr-400 focus:ring-pr-400 sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed" disabled>
                                    </div>
                                @empty
                                    <p class="text-gray-500">Tidak ada ukuran yang tersedia saat ini.</p>
                                @endforelse
                            </div>
                            @error('variants')
                                <span class="text-xs text-red-500 mt-2 block">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="grid grid-cols-1 gap-x-6 gap-y-8">
                            <!-- Name and Phone on one line -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                                <div>
                                    <label for="full-name" class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                                    <div class="mt-2">
                                        <input type="text" name="full-name" id="full-name" value="{{ old('full-name') }}" placeholder="cth. Budi Sanjaya"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pr-400 sm:text-sm sm:leading-6">
                                    </div>
                                    @error('full-name')
                                        <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Nomor Telepon</label>
                                    <div class="mt-2">
                                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Nomor telepon aktif Anda"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pr-400 sm:text-sm sm:leading-6">
                                    </div>
                                    @error('phone')
                                        <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="alamat" class="block text-sm font-medium leading-6 text-gray-900">Alamat</label>
                                <div class="mt-2">
                                    <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" placeholder="Alamat lengkap Anda"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pr-400 sm:text-sm sm:leading-6">
                                </div>
                                @error('alamat')
                                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="reservation" class="block text-sm font-medium leading-6 text-gray-900">Tanggal Reservasi</label>
                                <x-flatpickr range id="laravel-flatpickr" name="reservation_dates" class="w-full rounded-md"
                                    placeholder="Pilih tanggal mulai dan selesai" />
                            </div>

                            <!-- Estimasi Durasi dan Harga -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8 mt-4">
                                <div>
                                    <p id="duration" class="text-gray-600 text-lg">Durasi Sewa:
                                        <span class="ml-2 font-medium text-gray-700 border border-pr-400 p-2 rounded-md">- hari</span>
                                    </p>
                                </div>
                                <div>
                                    <p id="total-price" class="text-gray-600 text-lg">Biaya Sewa:
                                        <span class="ml-2 font-medium text-gray-700 border border-pr-400 p-2 rounded-md">Rp -</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <button type="submit" id="desktop_submit_button"
                            class="mt-12 w-full rounded-lg bg-slate-900 p-3 text-white font-bold shadow-xl transition-colors duration-300 hover:bg-pr-500 hover:shadow-none">
                            Pesan Sekarang
                        </button>
                    </form>
                </div>
            </div>

        </div>

        @if (session('error'))
            <script>
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });
                Toast.fire({
                    icon: "error",
                    title: "{{ session('error') }}"
                });
            </script>
        @endif

    <script>
        $(document).ready(function() {
            // Size and quantity logic
            $('.variant-checkbox').on('change', function() {
                const quantityInput = $(this).closest('.variant-item').find('.quantity-input');

                if (this.checked) {
                    quantityInput.prop('disabled', false).val(1).focus();
                } else {
                    quantityInput.prop('disabled', true).val('');
                }
                updatePrice();
            });

            // Listen for changes on any quantity input
            $('#size-selector').on('input', '.quantity-input', function() {
                // Prevent entering a value greater than max stock
                const max = parseInt($(this).attr('max'));
                if (parseInt($(this).val()) > max) $(this).val(max);
                updatePrice();
            });

            // Flatpickr and price calculation logic
            const flatpickrElement = document.getElementById('laravel-flatpickr');
            let flatpickrInstance;
            if (flatpickrElement && flatpickrElement._flatpickr) {
                flatpickrInstance = flatpickrElement._flatpickr;
                flatpickrInstance.config.onChange.push(function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        updatePrice();
                        const startDate = selectedDates[0];
                        const endDate = selectedDates[1];
                        const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) || 1;

                        if (duration > 7) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Batas Maksimal Sewa',
                                text: 'Anda hanya dapat menyewa maksimal selama 7 hari.',
                            });
                        }
                    }
                });
            }

            function updatePrice() {
                const selectedDates = flatpickrInstance ? flatpickrInstance.selectedDates : [];
                if (selectedDates.length < 2) {
                    $('#duration span').text('- hari');
                    $('#total-price span').text('Rp -');
                    return;
                }

                const startDate = selectedDates[0];
                const endDate = selectedDates[1];
                let duration = 0;
                let totalItemsPrice = 0;

                if (startDate && endDate && startDate <= endDate) {
                    duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) || 1;
                }

                // Disable submit button if duration is more than 7 days
                if (duration > 7) {
                    $('#desktop_submit_button').prop('disabled', true).addClass('bg-slate-400 hover:bg-slate-400 cursor-not-allowed').removeClass('bg-slate-900 hover:bg-pr-500');
                } else {
                    $('#desktop_submit_button').prop('disabled', false).removeClass('bg-slate-400 hover:bg-slate-400 cursor-not-allowed').addClass('bg-slate-900 hover:bg-pr-500');
                }

                $('.variant-checkbox:checked').each(function() {
                    const quantityInput = $(this).closest('.variant-item').find('.quantity-input');
                    const quantity = parseInt(quantityInput.val()) || 0;
                    const pricePerDay = {{ $pakaianAdat->price_per_day }};
                    totalItemsPrice += quantity * pricePerDay;
                });

                const finalTotalPrice = totalItemsPrice * duration;

                if (finalTotalPrice > 0) {
                    const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(finalTotalPrice);
                    $('#duration span').text(duration + ' hari');
                    $('#total-price span').text(formattedPrice);
                } else {
                    $('#duration span').text('- hari');
                    $('#total-price span').text('Rp -');
                }
            }

        });
    </script>

@endsection
