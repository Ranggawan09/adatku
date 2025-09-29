<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $admin = Auth::user();
        return view('admin.settings.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $admin = User::findOrFail(Auth::id());

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada dan bukan avatar default dari seeder
            if ($admin->avatar && $admin->avatar !== 'user.png' && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }

            $admin->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('new_password')) {
            $admin->password = Hash::make($request->new_password);
        }

        $admin->save();

        return redirect()->route('admin.settings.edit')->with('success', 'Admin settings updated successfully.');
    }
}
