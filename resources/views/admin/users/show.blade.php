@extends('layouts.admin')
@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- User Profile Sidebar -->
            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md h-fit">
                <div class="flex flex-col items-center gap-4">
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <hr class="my-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Total Reservasi</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-500">Disewa</dt>
                            <dd class="text-sm font-semibold text-green-600">{{ $user->reservations->where('status', 'Active')->count() }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-500">Pending</dt>
                            <dd class="text-sm font-semibold text-yellow-500">{{ $user->reservations->where('status', 'Pending')->count() }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-500">Berakhir</dt>
                            <dd class="text-sm font-semibold text-gray-800">{{ $user->reservations->where('status', 'Ended')->count() }}</dd>
                        </div>
                        <div class="flex justify-between items-center">
                            <dt class="text-sm font-medium text-gray-500">Dibatalkan</dt>
                            <dd class="text-sm font-semibold text-red-600">{{ $user->reservations->where('status', 'Canceled')->count() }}</dd>
                        </div>
                        <div class="flex justify-between items-center border-t pt-3 mt-3">
                            <dt class="text-base font-medium text-gray-800">Total</dt>
                            <dd class="text-base font-bold text-pr-400">{{ $user->reservations->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Reservations List -->
            <div class="lg:col-span-3 space-y-6">
                @if ($user->reservations->isEmpty())
                    <div class="bg-white p-8 rounded-lg shadow-md text-center">
                        <p class="text-gray-500">Pengguna ini belum memiliki reservasi.</p>
                    </div>
                @endif
                @foreach ($user->reservations as $reservation)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row">
                        <div class="md:w-1/3">
                            <img loading="lazy" class="w-full h-48 md:h-full object-cover"
                                src="{{ Storage::url($reservation->pakaianAdat->image) }}"
                                alt="Image of {{ $reservation->pakaianAdat->nama }}">
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">{{ $reservation->pakaianAdat->nama }}</h2>
                                <p class="text-sm text-gray-500">{{ $reservation->pakaianAdat->asal }} -
                                    {{ $reservation->pakaianAdat->jenis }}</p>

                                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <p class="font-medium text-gray-500">Mulai dari</p>
                                        <p class="font-semibold text-gray-800">
                                            {{ Carbon\Carbon::parse($reservation->start_date)->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-500">Sampai</p>
                                        <p class="font-semibold text-gray-800">
                                            {{ Carbon\Carbon::parse($reservation->end_date)->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-500">Total Harga</p>
                                        <p class="font-semibold text-gray-800">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-4">
                                @php
                                    $paymentBadges = [
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Paid' => 'bg-green-100 text-green-800',
                                        'Canceled' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusBadges = [
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Active' => 'bg-green-100 text-green-800',
                                        'Ended' => 'bg-gray-100 text-gray-800',
                                        'Canceled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="text-xs font-semibold inline-block py-1 px-3 rounded-full {{ $paymentBadges[$reservation->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    Payment: {{ $reservation->payment_status }}
                                </span>
                                <span
                                    class="text-xs font-semibold inline-block py-1 px-3 rounded-full {{ $statusBadges[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    Status: {{ $reservation->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
