<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua testimoni, urutkan dari yang terbaru, dan gunakan paginasi.
        $testimonials = Testimonial::with('reservation.user')->latest()
                                     ->paginate(9); // 9 testimoni per halaman

        return view('testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        return view('testimonials.create');
    }

    /**
     * Store a newly created testimonial in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => [
                'required',
                'string',
                // Validasi kustom untuk memeriksa invoice
                function ($attribute, $value, $fail) {
                    $reservation = Reservation::where('order_id', $value)->first();

                    if (!$reservation) {
                        return $fail('Nomor invoice tidak ditemukan.');
                    }

                    if ($reservation->status !== 'Selesai') {
                        return $fail('Anda hanya bisa memberikan testimoni untuk reservasi yang sudah selesai.');
                    }

                    if ($reservation->testimonial()->exists()) {
                        return $fail('Anda sudah pernah memberikan testimoni untuk nomor invoice ini.');
                    }
                },
            ],
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        // Cari reservasi berdasarkan nomor invoice
        $reservation = Reservation::where('order_id', $request->invoice_number)->firstOrFail();

        // Buat testimoni baru
        Testimonial::create([
            'reservation_id' => $reservation->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('testimonials.index')->with('success', 'Terima kasih atas testimoni Anda!');
    }
}
