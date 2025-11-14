@extends('layouts.admin')
@section('content')
    <div class="container mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">
            Dashboard
        </h2>

        <!-- Cards -->
        <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3">
            <!-- Card Total Clients -->
            <a href="{{ route('admin.users.index') }}" class="block">
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="p-3 mr-4 text-jawa-600 bg-jawa-100 rounded-full">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-medium text-gray-600">
                            Total Pelanggan
                        </p>
                        <p class="text-3xl font-semibold text-gray-700">
                            {{ $userCount }}
                        </p>
                    </div>
                </div>
            </a>

            <!-- Card Pakaian Adat Tersedia -->
            <a href="{{ route('admin.pakaian-adat.index') }}" class="block">
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="p-3 mr-4 text-jawa-600 bg-jawa-100 rounded-full">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-medium text-gray-600">
                            Total Pakaian Adat
                        </p>
                        <p class="text-3xl font-semibold text-gray-700">
                            {{ $PakaianAdatCount }}
                        </p>
                    </div>
                </div>
            </a>

            <!-- Card Reservasi Aktif -->
            <a href="{{ route('admin.reservations.index') }}" class="block">
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="p-3 mr-4 text-jawa-600 bg-jawa-100 rounded-full">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v1H5V4zM5 7h10v9a2 2 0 01-2 2H7a2 2 0 01-2-2V7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-medium text-gray-600">
                            Reservasi Aktif
                        </p>
                        <p class="text-3xl font-semibold text-gray-700">
                            {{ $reservationCount }}
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Charts -->
        <div class="grid gap-6 mb-8 md:grid-cols-1 xl:grid-cols-2">
            <!-- Reservations Chart -->
            <div class="p-4 bg-white rounded-lg shadow-md">
                <h3 class="mb-4 text-xl font-semibold text-gray-700">Reservasi 30 Hari Terakhir</h3>
                <div id="reservations-chart"></div>
            </div>

            <!-- Popular Pakaian Adat Chart -->
            <div class="p-4 bg-white rounded-lg shadow-md">
                <h3 class="mb-4 text-xl font-semibold text-gray-700">Top 5 Pakaian Adat Terpopuler</h3>
                <div id="popular-pakaian-chart"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Reservations Chart
                const reservationOptions = {
                    chart: {
                        type: 'area',
                        height: 350,
                        toolbar: {
                            show: false
                        },
                    },
                    series: [{
                        name: 'Jumlah Reservasi',
                        data: @json($reservationValues)
                    }],
                    colors: ['#b08471'],
                    xaxis: {
                        categories: @json($reservationLabels)
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.3,
                            stops: [0, 90, 100]
                        }
                    },
                    tooltip: {
                        x: {
                            format: 'dd MMM yyyy'
                        },
                    },
                };

                const reservationsChart = new ApexCharts(document.querySelector("#reservations-chart"), reservationOptions);
                reservationsChart.render();

                // Popular Pakaian Adat Chart
                const popularPakaianOptions = {
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: false
                        },
                    },
                    series: [{
                        name: 'Jumlah Disewa',
                        data: @json($popularPakaianValues)
                    }],
                    xaxis: {
                        categories: @json($popularPakaianLabels)
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            borderRadius: 4,
                            columnWidth: '50%',
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    colors: ['#c9a899']
                };

                const popularPakaianChart = new ApexCharts(document.querySelector("#popular-pakaian-chart"), popularPakaianOptions);
                popularPakaianChart.render();
            });
        </script>
    @endpush
@endsection
