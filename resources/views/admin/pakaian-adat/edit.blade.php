@extends('layouts.admin')
@section('content')
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Edit Pakaian Adat: <span class="text-jawa-500">{{ $pakaianAdat->nama }}</span>
        </h2>

        <div class="w-full p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('admin.pakaian-adat.update', $pakaianAdat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Nama Pakaian & Jenis (satu baris) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="text-gray-700 dark:text-gray-400">Nama Pakaian</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $pakaianAdat->nama) }}"
                                class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-jawa-400 focus:ring focus:ring-jawa-200 focus:ring-opacity-50"
                                placeholder="cth. Baju Bodo">
                            @error('nama')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="jenis" class="text-gray-700 dark:text-gray-400">Jenis</label>
                            <select name="jenis" id="jenis" class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-jawa-400 focus:ring focus:ring-jawa-200 focus:ring-opacity-50">
                                <option value="" disabled>Pilih Jenis</option>
                                <option value="Pria" @selected(old('jenis', $pakaianAdat->jenis) == 'Pria')>Pria</option>
                                <option value="Wanita" @selected(old('jenis', $pakaianAdat->jenis) == 'Wanita')>Wanita</option>
                                <option value="Anak Laki-Laki" @selected(old('jenis', $pakaianAdat->jenis) == 'Anak Laki-Laki')>Anak Laki-Laki</option>
                                <option value="Anak Perempuan" @selected(old('jenis', $pakaianAdat->jenis) == 'Anak Perempuan')>Anak Perempuan</option>
                            </select>
                            @error('jenis')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Asal Daerah -->
                    <div>
                        <label for="asal" class="text-gray-700 dark:text-gray-400">Asal Daerah</label>
                        <select name="asal" id="asal" class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-jawa-400 focus:ring focus:ring-jawa-200 focus:ring-opacity-50">
                            <option value="" disabled>Memuat provinsi...</option>
                            <!-- Opsi provinsi akan diisi oleh JavaScript -->
                        </select>
                        @error('asal')
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Warna & Status (satu baris) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="warna" class="text-gray-700 dark:text-gray-400">Warna Dominan</label>
                            <input type="text" name="warna" id="warna" value="{{ old('warna', $pakaianAdat->warna) }}"
                                class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-jawa-400 focus:ring focus:ring-jawa-200 focus:ring-opacity-50"
                                placeholder="cth. Merah, Emas">
                            @error('warna')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="status" class="text-gray-700 dark:text-gray-400">Status</label>
                            <select id="status" name="status"
                                class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-jawa-400 focus:ring focus:ring-jawa-200 focus:ring-opacity-50">
                                <option value="Tersedia" @selected(old('status', $pakaianAdat->status) == 'Tersedia')>Tersedia</option>
                                <option value="Tidak Tersedia" @selected(old('status', $pakaianAdat->status) == 'Tidak Tersedia')>Tidak Tersedia</option>
                            </select>
                            @error('status')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Harga Sewa & Diskon (satu baris) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price_per_day" class="text-gray-700 dark:text-gray-400">Harga Sewa / Hari</label>
                            <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day', $pakaianAdat->price_per_day) }}"
                                class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-jawa-400 focus:ring focus:ring-jawa-200 focus:ring-opacity-50"
                                placeholder="cth. 150000">
                            @error('price_per_day')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="reduce" class="text-gray-700 dark:text-gray-400">Diskon (%)</label>
                            <input type="number" name="reduce" id="reduce" value="{{ old('reduce', $pakaianAdat->reduce) }}"
                                class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-jawa-400 focus:ring focus:ring-jawa-200 focus:ring-opacity-50"
                                placeholder="cth. 10">
                            @error('reduce')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Varian Ukuran Dinamis -->
                    <div>
                        <label class="text-gray-700 dark:text-gray-400">Varian Ukuran & Stok</label>
                        <div id="variants-container" class="mt-2 space-y-4">
                            @foreach (old('variants', $pakaianAdat->variants->toArray()) as $index => $variant)
                                <div class="flex items-center gap-4 variant-row">
                                    <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] ?? '' }}">
                                    <input type="text" name="variants[{{ $index }}][size]" value="{{ $variant['size'] }}" class="block w-full text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Ukuran (cth. M)">
                                    <input type="number" name="variants[{{ $index }}][quantity]" value="{{ $variant['quantity'] }}" class="block w-full text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Jumlah Stok">
                                    <button type="button" class="remove-variant-btn px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-md shadow-sm hover:bg-red-700">-</button>
                                </div>
                            @endforeach
                        </div>
                        {{-- Hidden input to store IDs of deleted variants --}}
                        <div id="deleted-variants-container"></div>

                        @error('variants')
                            <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                        @error('variants.*.size')
                            <span class="text-xs text-red-600 dark:text-red-400">Kolom ukuran pada varian tidak boleh kosong.</span>
                        @enderror
                        @error('variants.*.quantity')
                            <span class="text-xs text-red-600 dark:text-red-400">Kolom stok pada varian tidak boleh kosong.</span>
                        @enderror
                        <button type="button" id="add-variant-btn"
                            class="px-3 py-1 mt-4 text-sm font-medium text-white bg-green-600 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            + Tambah Varian
                        </button>
                    </div>

                    <!-- Deskripsi & Foto (satu baris) -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="text-gray-700 dark:text-gray-400">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi"
                                class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-jawa-400 focus:ring focus:ring-jawa-200 focus:ring-opacity-50"
                                placeholder="Jelaskan tentang pakaian adat ini..." style="height: 150px;">{{ old('deskripsi', $pakaianAdat->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Foto Sampul -->
                        <div>
                            <label for="image" class="block text-gray-700 dark:text-gray-400">Ganti Foto Sampul</label>
                            <div class="flex justify-center w-full px-6 pt-5 pb-6 mt-1 border-2 border-gray-300 border-dashed rounded-md dark:border-gray-600" style="height: 150px;">
                                <div class="space-y-1 text-center">
                                    <img id="image-preview" src="{{ $pakaianAdat->image ? Storage::url($pakaianAdat->image) : '' }}" alt="Image preview" class="object-cover w-auto h-24 mx-auto mb-2 rounded {{ $pakaianAdat->image ? '' : 'hidden' }}">
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="image"
                                            class="relative font-medium text-jawa-600 bg-white rounded-md cursor-pointer dark:bg-gray-800 hover:text-jawa-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-jawa-500">
                                            <span>Ganti foto</span>
                                            <input id="image" name="image" type="file" class="sr-only" onchange="previewImage(event)">
                                        </label>
                                        <p class="pl-1">atau seret dan lepas</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF hingga 2MB</p>
                                </div>
                            </div>
                            @error('image')
                                <span class="text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6 space-x-4">
                    <a href="{{ route('admin.pakaian-adat.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white rounded-md shadow-sm bg-jawa-500 hover:bg-jawa-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jawa-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('variants-container');
    const addBtn = document.getElementById('add-variant-btn');
    const deletedContainer = document.getElementById('deleted-variants-container');
    // Start index from the last one rendered by PHP to avoid conflicts
    let variantIndex = {{ old('variants') ? count(old('variants')) : ($pakaianAdat->variants ? $pakaianAdat->variants->count() : 0) }};

    addBtn.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.classList.add('flex', 'items-center', 'gap-4', 'variant-row');
        newRow.innerHTML = `
            <input type="hidden" name="variants[${variantIndex}][id]" value="">
            <input type="text" name="variants[${variantIndex}][size]" class="block w-full text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Ukuran (cth. M)">
            <input type="number" name="variants[${variantIndex}][quantity]" class="block w-full text-sm border-gray-300 rounded-md shadow-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700" placeholder="Jumlah Stok">
            <button type="button" class="remove-variant-btn px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-md shadow-sm hover:bg-red-700">-</button>
        `;
        container.appendChild(newRow);
        variantIndex++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-variant-btn')) {
            const row = e.target.closest('.variant-row');
            const variantIdInput = row.querySelector('input[type="hidden"]');

            // If it's an existing variant (has an ID), mark it for deletion
            if (variantIdInput && variantIdInput.value) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'deleted_variants[]';
                hiddenInput.value = variantIdInput.value;
                deletedContainer.appendChild(hiddenInput);
            }

            // Remove the row from the view
            row.remove();
        }
    });
});
</script>
<script>
    function previewImage(event) {
        const reader = new FileReader();
        const imagePreview = document.getElementById('image-preview');

        reader.onload = function(e){
            if (e.target.readyState === 2) {
                imagePreview.src = reader.result;
                imagePreview.classList.remove('hidden');
            }
        };

        if(event.target.files[0]){
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('asal');
        const selectedAsal = "{{ old('asal', $pakaianAdat->asal) }}";

        fetch('https://api.binderbyte.com/wilayah/provinsi?api_key=cae2f8ef8cbafc83440b5a8f8fa394dd1ddef4caad4378eb29dfeab6df3d7d80')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                provinceSelect.innerHTML = '<option value="" disabled>Pilih Provinsi</option>'; // Reset
                data.value.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.name;
                    option.textContent = province.name;
                    if (province.name === selectedAsal) {
                        option.selected = true;
                    }
                    provinceSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching provinces:', error);
                provinceSelect.innerHTML = '<option value="" disabled>Gagal memuat provinsi</option>';
            });
    });
</script>
@endpush
