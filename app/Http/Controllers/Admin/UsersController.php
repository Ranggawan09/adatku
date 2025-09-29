<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil query pencarian dari request
        $search = request('search');
 
        // Query dasar untuk user dengan role 'client'
        $clientsQuery = User::where('role', 'client');
 
        // Jika ada query pencarian, filter berdasarkan nama atau email
        if ($search) {
            $clientsQuery->where(fn($query) => $query->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%'));
        }
 
        // Lakukan paginasi dan tambahkan query string agar pencarian tetap aktif saat pindah halaman
        $clients = $clientsQuery->latest()->paginate(8)->withQueryString();
 
        return view('admin.users.index', compact('clients'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Eager load relasi 'reservations' dan 'pakaianAdat' untuk menghindari N+1 query problem
        $user->load('reservations.pakaianAdat');
        return view('admin.users.show', compact('user'));
    }
}