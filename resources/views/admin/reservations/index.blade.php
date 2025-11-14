@extends('layouts.admin')
@section('content')
    <div class="container mx-auto grid mb-16 ">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <h2 class="text-3xl font-semibold text-gray-700 dark:text-gray-200">
                Reservasi
            </h2>
            {{-- Search bar --}}
            <div class="w-full md:w-1/3">
                <form action="{{ route('admin.reservations.index') }}" method="GET">
                    <label for="default-search"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" name="search" id="default-search" value="{{ request('search') }}"
                            class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-pr-500 focus:border-pr-500"
                            placeholder="Search by Client Name, Phone, or Pakaian Adat...">
                        <button type="submit"
                            class="text-white absolute right-2.5 bottom-2.5 bg-pr-400 hover:bg-pr-500 focus:ring-4 focus:outline-none focus:ring-pr-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Reservations Table -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs mt-8">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap table-auto">
                    <thead>
                        <tr
                            class="text-base font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Pakaian Adat</th>
                            <th class="px-4 py-3">Ukuran</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Total Harga</th>
                            <th class="px-4 py-3">Metode Bayar</th>
                            <th class="px-4 py-3">Status Pembayaran</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 ">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @forelse ($reservations as $orderId => $items)
                            @php
                                // Ambil item pertama sebagai perwakilan untuk data umum (user, tanggal, status)
                                $reservation = $items->first();
                                // Hitung total harga untuk seluruh pesanan
                                $orderTotalPrice = $items->sum('total_price');
                            @endphp
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-base">
                                        <div>
                                            <p class="font-semibold text-base">{{ $reservation->user->name ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $reservation->user->phone ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-base">
                                    {{-- Gabungkan nama pakaian jika berbeda, atau tampilkan yang pertama --}}
                                    {{ $items->pluck('pakaianAdat.nama')->unique()->join(', ') }}
                                </td>
                                <td class="px-4 py-3 text-base">
                                    {{-- Gabungkan semua ukuran dan jumlahnya --}}
                                    @foreach($items as $item)
                                        <div>{{ $item->variant->size ?? 'N/A' }} (x{{ $item->quantity }})</div>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-base">
                                    @php
                                        $startDate = \Carbon\Carbon::parse($reservation->start_date);
                                        $endDate = \Carbon\Carbon::parse($reservation->end_date);

                                        if ($startDate->isSameYear($endDate)) {
                                            if ($startDate->isSameMonth($endDate)) {
                                                $dateString = $startDate->format('d') . ' to ' . $endDate->format('d M Y');
                                            } else {
                                                $dateString = $startDate->format('d M') . ' to ' . $endDate->format('d M Y');
                                            }
                                        } else {
                                            $dateString = $startDate->format('d M Y') . ' to ' . $endDate->format('d M Y');
                                        }
                                    @endphp 
                                    {{ $dateString }} 
                                    <p class="text-sm text-gray-500">({{ $reservation->days }} days)</p>
                                </td>
                                <td class="px-4 py-3 text-base">
                                    Rp{{ number_format($orderTotalPrice, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-base">
                                    @if ($reservation->payment_method)
                                        <span
                                            class="px-3 py-1.5 text-sm font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-100">
                                            {{ str_replace('_', ' ', $reservation->payment_method) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-base">
                                    @if ($reservation->payment_status == 'Lunas')
                                        <span
                                            class="px-3 py-1.5 text-sm font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            {{ $reservation->payment_status }}
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1.5 text-sm font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                            {{ $reservation->payment_status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-base">
                                    @php
                                        $statusClass = '';
                                        switch ($reservation->status) {
                                            case 'Disewa':
                                                $statusClass = 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100';
                                                break;
                                            case 'Selesai':
                                                $statusClass = 'text-gray-700 bg-gray-100 dark:text-gray-100 dark:bg-gray-700';
                                                break;
                                            case 'Dibatalkan':
                                                $statusClass = 'text-red-700 bg-red-100 dark:text-red-100 dark:bg-red-700';
                                                break;
                                            case 'Terlambat':
                                                $statusClass = 'text-orange-700 bg-orange-100 dark:text-white dark:bg-orange-600';
                                                break;
                                            case 'Dibayar':
                                                $statusClass = 'text-blue-700 bg-blue-100 dark:text-white dark:bg-blue-600';
                                                break;
                                            default: // Menunggu Pembayaran
                                                $statusClass = 'text-yellow-700 bg-yellow-100 dark:bg-yellow-700 dark:text-yellow-100';
                                                break;
                                        }
                                    @endphp
                                    <span
                                        class="px-3 py-1.5 text-sm font-semibold leading-tight rounded-full {{ $statusClass }}">
                                        {{ $reservation->status }}
                                        <span
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-base">
                                    <div class="flex flex-col space-y-2">
                                        <a href="{{ route('admin.editPayment', ['reservation' => $reservation->id]) }}" class="px-3 py-2 text-sm text-white bg-green-500 hover:bg-green-600 font-medium rounded text-center">
                                            Edit Pembayaran
                                        </a>
                                        <a href="{{ route('admin.editStatus', ['reservation' => $reservation->id]) }}" class="px-3 py-2 text-sm text-white bg-blue-500 hover:bg-blue-600 font-medium rounded text-center">
                                            Edit Status
                                        </a>
                                        <a href="{{ route('invoice', ['reservation' => $reservation->id]) }}" class="px-3 py-2 text-sm text-white bg-gray-500 hover:bg-gray-600 font-medium rounded text-center" target="_blank">
                                            Cetak Invoice
                                        </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-gray-500">No reservations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
@endsection