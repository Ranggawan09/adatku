@extends('layouts.admin')


@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
<h2 class="text-xl font-semibold mb-4">Edit Admin Settings</h2>


@if (session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
{{ session('success') }}
</div>
@endif


<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
@csrf
@method('PUT')


<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Kolom Kiri: Detail Profil --}}
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}"
            class="w-full border rounded px-3 py-2" required>
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}"
            class="w-full border rounded px-3 py-2" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Avatar</label>
            <div class="flex items-center space-x-4">
            <img id="avatar-preview"
                 src="{{ $admin->avatar ? Storage::url($admin->avatar) : asset('storage/user.png') }}"
                 alt="Avatar" class="w-16 h-16 rounded-full object-cover">
            <input type="file" name="avatar" id="avatar-input" accept="image/*">
            </div>
            @error('avatar') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Kolom Kanan: Ubah Password --}}
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Current Password</label>
            <input type="password" name="current_password" class="w-full border rounded px-3 py-2" placeholder="Enter current password to change">
            @error('current_password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">New Password</label>
            <input type="password" name="new_password" class="w-full border rounded px-3 py-2">
            @error('new_password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="w-full border rounded px-3 py-2">
        </div>
    </div>
</div>

<div class="mt-6">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Save Changes
    </button>
</div>
</form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');

    avatarInput.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                avatarPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endpush