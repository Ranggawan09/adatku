@extends('layouts.admin')
@section('content')
    <div class="container mx-auto grid mb-16 ">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <h2 class="text-3xl font-semibold text-gray-700 dark:text-gray-200">
                Pelanggan
            </h2>
            {{-- Search bar --}}
            <div class="w-full md:w-1/3">
                <form action="{{ route('admin.users.index') }}" method="GET">
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
                            class="block w-full p-4 pl-10 text-base text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-pr-500 focus:border-pr-500"
                            placeholder="Cari berdasarkan Nama atau Email...">
                        <button type="submit"
                            class="text-white absolute right-2.5 bottom-2.5 bg-pr-400 hover:bg-pr-500 focus:ring-4 focus:outline-none focus:ring-pr-300 font-medium rounded-lg text-base px-4 py-2">Search</button>
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
                            class="text-sm font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="text-center px-4 py-3 w-48">Name</th>
                            <th class="text-center px-4 py-3 w-24">No Whatsapp</th>
                            <th class="text-center px-4 py-3 w-24">Tanggal Bergabung</th>
                            <th class="text-center w-56 px-4 py-3">Reservasi</th>
                            <th class="text-center px-4 py-3 w-26">Aksi</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">


                        @forelse ($clients as $client)
                            <tr class="bg-white hover:bg-gray-50">
                                <td class="px-4 py-3 text-base text-center">
                                    <p class="font-semibold">
                                        {{ $client->name }} 
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-base text-center">
                                    <p>
                                        {{ $client->phone }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-base w-32 text-center">
                                    <p>
                                        {{ Carbon\Carbon::parse($client->created_at)->format('Y-m-d') }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-base text-center">
                                    @if ($client->reservations->count() > 0)
                                        <p>
                                            <span class="font-bold">{{ $client->reservations->count() }}</span>
                                             Reservasi
                                        </p>
                                    @else
                                        <span class="text-gray-500">Belum ada reservasi</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-base w-32 text-center">
                                    <a href="{{ route('admin.users.show', ['user' => $client->id]) }}"
                                       class="inline-block bg-blue-500 text-white px-4 py-2 text-base font-semibold rounded-md hover:bg-blue-600 transition-colors duration-200">
                                            Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 px-4 text-gray-500">
                                    <p class="text-lg">No users found.</p>
                                    @if(request('search'))
                                        <p class="text-sm mt-2">Try adjusting your search query.</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                    {{ $clients->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
