<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class InvoiceController extends Controller
{
    /**
     * @param Reservation $reservation
     * @return \Illuminate\View\View
     */
    public function show(Reservation $reservation)
    {
        // Ambil semua item reservasi yang terkait dengan order_id ini.
        $relatedReservations = Reservation::where('order_id', $reservation->order_id)
            ->with(['pakaianAdat', 'variant'])
            ->get();

        // Hitung total harga dari semua item yang terkait.
        $totalPrice = $relatedReservations->sum('total_price');

        return view('invoice', compact('reservation', 'relatedReservations', 'totalPrice'));
    }
}
