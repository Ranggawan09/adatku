<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            User::create([
                'name'   => "Client User {$i}",
                'phone'  => "08123" . str_pad($i, 6, '0', STR_PAD_LEFT),
                'alamat' => "Alamat Klien {$i}, Jombang",
                'role'   => 'client',
                // kalau kolom email & password wajib isi, gunakan baris berikut:
                // 'email' => "client{$i}@email.com",
                // 'password' => Hash::make('pass1234'),
                // 'avatar' => 'user.png',
            ]);
        }
    }
}
