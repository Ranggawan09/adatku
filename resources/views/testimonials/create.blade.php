@extends('layouts.myapp')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6 sm:p-8">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold text-gray-900">Beri Testimoni</h1>
                        <p class="mt-2 text-gray-600">Silakan isi formulir di bawah ini untuk memberikan testimoni Anda.</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-6" role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ada beberapa masalah dengan input Anda.</span>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('testimonials.store') }}" method="POST" class="mt-8 space-y-6">
                        @csrf
                        <div>
                            <label for="invoice_number" class="block text-sm font-medium text-gray-700">Nomor Invoice</label>
                            <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" required
                                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-pr-500 focus:border-pr-500 sm:text-sm">
                            <p class="mt-2 text-sm text-gray-500">Masukkan nomor invoice dari reservasi Anda yang telah selesai.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="flex items-center justify-center gap-1 star-rating-container">
                                @for ($i = 5; $i >= 1; $i--)
                                <label class="star-label cursor-pointer">
                                    <input type="radio" name="rating" value="{{$i}}" {{ old('rating', 0) == $i ? 'checked' : '' }} required class="hidden star-input" />
                                    <span class="star-char text-4xl">★</span>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700">Komentar Anda</label>
                            <textarea id="comment" name="comment" rows="4" required
                                      class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-pr-500 focus:border-pr-500 sm:text-sm">{{ old('comment') }}</textarea>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pr-600 hover:bg-pr-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pr-500 transition duration-300">
                                Kirim Testimoni
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star-rating-container {
    direction: rtl;
    display: inline-flex;
}

.star-label {
    display: inline-block;
    transition: transform 0.2s ease;
}

.star-label:hover {
    transform: scale(1.1);
}

.star-char {
    color: #D1D5DB;
    transition: color 0.2s ease;
    user-select: none;
}

/* Hover effect */
.star-label:hover .star-char,
.star-label:hover ~ .star-label .star-char {
    color: #FBBF24 !important;
}

/* Checked state menggunakan :has() selector */
.star-label:has(input:checked) .star-char,
.star-label:has(input:checked) ~ .star-label .star-char {
    color: #FBBF24 !important;
}

/* Fallback untuk browser yang tidak support :has() */
.star-input:checked + .star-char {
    color: #FBBF24 !important;
}
</style>

<script>
// JavaScript fallback untuk memastikan rating berfungsi di semua browser
document.addEventListener('DOMContentLoaded', function() {
    const starLabels = document.querySelectorAll('.star-label');
    
    starLabels.forEach((label, index) => {
        const input = label.querySelector('input');
        
        input.addEventListener('change', function() {
            if (this.checked) {
                updateStars(index);
            }
        });
    });
    
    function updateStars(selectedIndex) {
        const starChars = document.querySelectorAll('.star-char');
        starChars.forEach((star, index) => {
            if (index >= selectedIndex) {
                star.style.color = '#FBBF24';
            } else {
                star.style.color = '#D1D5DB';
            }
        });
    }
    
    // Set initial state jika ada old value
    const checkedInput = document.querySelector('.star-input:checked');
    if (checkedInput) {
        const checkedLabel = checkedInput.closest('.star-label');
        const allLabels = Array.from(starLabels);
        const checkedIndex = allLabels.indexOf(checkedLabel);
        updateStars(checkedIndex);
    }
});
</script>
@endsection