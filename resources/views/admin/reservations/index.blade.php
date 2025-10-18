@extends('layouts.admin')
@section('content')
    <div class="container mx-auto grid mb-16 ">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">
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
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
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
                        @forelse ($reservations as $reservation)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                        <div>
                                            <p class="font-semibold">{{ $reservation->user->name }}</p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $reservation->user->phone }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $reservation->pakaianAdat->nama ?? 'N/A' }} {{ $reservation->pakaianAdat->jenis ?? '' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $reservation->variant->size ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ \Carbon\Carbon::parse($reservation->start_date)->format('d M Y') }} to
                                    {{ \Carbon\Carbon::parse($reservation->end_date)->format('d M Y') }}
                                    <p class="text-xs text-gray-500">({{ $reservation->days }} days)</p>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    Rp{{ number_format($reservation->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @if ($reservation->payment_method)
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-100">
                                            {{ str_replace('_', ' ', $reservation->payment_method) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @if ($reservation->payment_status == 'Lunas')
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            {{ $reservation->payment_status }}
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                            {{ $reservation->payment_status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @if ($reservation->status == 'Active' || $reservation->status == 'Aktif')
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            {{ $reservation->status }}
                                        </span>
                                    @elseif($reservation->status == 'Ended')
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                                            {{ $reservation->status }}
                                        </span>
                                    @elseif($reservation->status == 'Canceled')
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                            {{ $reservation->status }}
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                            {{ $reservation->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex flex-col space-y-2">
                                        <a href="{{ route('admin.editPayment', ['reservation' => $reservation->id]) }}"
                                            class="p-2 text-xs text-white bg-green-500 hover:bg-green-600 font-medium rounded text-center">
                                            Edit Pembayaran
                                        </a>
                                        <a href="{{ route('admin.editStatus', ['reservation' => $reservation->id]) }}"
                                            class="p-2 text-xs text-white bg-blue-500 hover:bg-blue-600 font-medium rounded text-center">
                                            Edit Status
                                        </a>
                                        <a href="{{ route('invoice', ['reservation' => $reservation->id]) }}"
                                            class="p-2 text-xs text-white bg-gray-500 hover:bg-gray-400 font-medium rounded text-center" target="_blank">
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