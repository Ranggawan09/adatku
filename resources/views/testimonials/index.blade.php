@extends('layouts.myapp')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900">Testimoni Pelanggan</h1>
            <p class="mt-4 text-lg text-gray-600">Lihat apa kata mereka tentang layanan kami.</p>
        </div>

        <div class="flex justify-center mb-12">
            <a href="{{ route('testimonials.create') }}" class="bg-pr-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-pr-700 transition duration-300 shadow-md flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Tulis Testimoni Anda</span>
            </a>
        </div>

        @if($testimonials->isEmpty())
            <div class="text-center py-16">
                <p class="text-gray-500 text-xl">Belum ada testimoni. Jadilah yang pertama memberikan ulasan!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                    <div class="p-6 flex-grow">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-jawa-100 flex items-center justify-center text-jawa-600 font-bold text-xl">
                                {{-- Pastikan relasi ada sebelum diakses --}}
                                {{ $testimonial->reservation && $testimonial->reservation->user ? strtoupper(substr($testimonial->reservation->user->name, 0, 1)) : '?' }}
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold text-gray-900">{{ $testimonial->reservation->user->name ?? 'Pengguna Dihapus' }}</h3>
                                <div class="text-yellow-400 flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 fill-current {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"{{ $testimonial->comment }}"</p>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 text-right text-sm text-gray-500">
                        {{ $testimonial->created_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Tampilkan Paginasi --}}
            <div class="mt-12">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</div>
@endsection