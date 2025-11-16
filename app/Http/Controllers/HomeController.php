<?php

namespace App\Http\Controllers;

use App\Models\PakaianAdat;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan beberapa koleksi pakaian adat.
     */
    public function index()
    {
        // Mengambil 5 pakaian adat terbaru yang statusnya "Tersedia"
        $pakaianAdats = PakaianAdat::where('status', 'Tersedia')
                                  ->latest()
                                  ->take(5)
                                  ->get();
        
        // Mengambil 5 testimoni terbaru
        $testimonials = Testimonial::with('reservation.user')->latest()
                                     ->take(5)
                                     ->get();

        // Mengirim data ke view 'home'
        return view('home', compact('pakaianAdats', 'testimonials'));
    }
}