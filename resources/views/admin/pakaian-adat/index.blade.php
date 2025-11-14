@extends('layouts.admin')
@section('content')
    <div class="container mx-auto grid mb-16">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <h2 class="text-3xl font-semibold text-gray-700 dark:text-gray-200">
                    Pakaian Adat
                </h2>
                <a href="{{ route('admin.pakaian-adat.create') }}" class="bg-pr-400 px-4 py-2 text-white rounded-md shadow-lg hover:bg-pr-600 text-base font-medium">
                    Tambah Pakaian Adat
                </a>
            </div>
            <div class="w-full md:w-1/3">
                <form action="{{ route('admin.pakaian-adat.index') }}" method="GET">
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
                            placeholder="Cari berdasarkan nama, jenis...">
                        <button type="submit"
                            class="text-white absolute right-2.5 bottom-2.5 bg-pr-400 hover:bg-pr-500 focus:ring-4 focus:outline-none focus:ring-pr-300 font-medium rounded-lg text-sm px-4 py-2">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- pakaian-adat Table -->
        <div class="w-full overflow-hidden rounded-lg shadow-xs mt-8">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap table-auto">
                <thead class="text-sm text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Image
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Pakaian
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jenis
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Ukuran
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Asal Daerah
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Harga/Hari
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Stok
                        </th>
                        
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach ($pakaianAdats as $pakaianAdat)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-sm">
                            <td class="px-4 py-3">
                                <img loading="lazy" class="h-16 w-24 object-cover rounded-md" src="{{ $pakaianAdat->image_url }}" alt="Foto {{ $pakaianAdat->nama }}">

                            </td>
                            <th scope="row"
                                class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $pakaianAdat->nama }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $pakaianAdat->jenis }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    @foreach($pakaianAdat->variants as $variant)
                                        <span>{{ $variant->size }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $pakaianAdat->asal }}
                            </td>
                            <td class="px-6 py-4 font-medium">
                                Rp{{ number_format($pakaianAdat->price_per_day, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                {{ $pakaianAdat->total_quantity }}
                            </td>
                            
                            <td class="px-6 py-4">
                                @if ($pakaianAdat->status == 'Tersedia')
                                    <span class="px-2 py-1 text-sm font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">Tersedia</span>
                                @else
                                    <span class="px-2 py-1 text-sm font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 flex items-center space-x-2">
                                <a href="{{ route('admin.pakaian-adat.edit', ['pakaianAdat' => $pakaianAdat->id]) }}"
                                    class="px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Edit</a>
                                <form action="{{ route('admin.pakaian-adat.destroy', ['pakaianAdat' => $pakaianAdat->id]) }}" method="POST" onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <div class="px-4 py-3 text-gray-700 dark:text-gray-400">
                {{ $pakaianAdats->links() }}
            </div>
        </div>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 3500
                });
            </script>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function confirmDelete(event) {
        event.preventDefault(); // Mencegah form submit secara langsung
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit(); // Lanjutkan submit form jika dikonfirmasi
            }
        });
    }
</script>
@endpush
