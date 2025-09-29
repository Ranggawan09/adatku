<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'pakaianAdat'])->orderBy('created_at', 'desc');

        // Handle search
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%")
                              ->orWhere('phone', 'like', "%{$searchTerm}%"); // This was correct
                })->orWhereHas('pakaianAdat', function ($pakaianAdatQuery) use ($searchTerm) {
                    $pakaianAdatQuery->where('nama', 'like', "%{$searchTerm}%")
                             ->orWhere('jenis', 'like', "%{$searchTerm}%");
                });
            });
        }

        $reservations = $query->paginate(10)->withQueryString();
        return view('admin.reservations.index', compact('reservations'));
    }

    // Edit and Update Payment status
    public function editPayment(Reservation $reservation)
    {
        return view('admin.reservations.edit_payment', compact('reservation'));
    }

    public function updatePayment(Reservation $reservation, Request $request)
    {
        $reservation->payment_status = $request->payment_status;
        $reservation->save();
        return redirect()->route('admin.reservations.index')->with('success', 'Payment status updated successfully.');
    }

    // Edit and Update Reservation Status
    public function editStatus(Reservation $reservation)
    {
        return view('admin.reservations.edit_status', compact('reservation'));
    }

    public function updateStatus(Reservation $reservation, Request $request)
    {
        $request->validate(['status' => 'required|in:Active,Pending,Ended,Canceled']);

        $reservation->status = $request->status;
        $pakaianAdat = $reservation->pakaianAdat;
        if ($request->status == 'Ended' || $request->status == 'Canceled') {
            $pakaianAdat->status = 'Tersedia';
            $pakaianAdat->save();
        }
        $reservation->save();
        return redirect()->route('admin.reservations.index')->with('success', 'Reservation status updated successfully.');
    }
}
