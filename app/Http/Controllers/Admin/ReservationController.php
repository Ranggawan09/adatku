<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'pakaianAdat', 'variant'])->latest();

        // Handle search
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // Search by user name or phone
                $q->whereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', "%{$searchTerm}%")
                              ->orWhere('phone', 'like', "%{$searchTerm}%");
                })
                // Search by Pakaian Adat name or jenis
                ->orWhereHas('pakaianAdat', function ($pakaianAdatQuery) use ($searchTerm) {
                    $pakaianAdatQuery->where('nama', 'like', "%{$searchTerm}%")
                                     ->orWhere('jenis', 'like', "%{$searchTerm}%");
                })
                // Search by order_id
                ->orWhere('order_id', 'like', "%{$searchTerm}%");
            });
        }

        // Get all matching reservations and group them by order_id
        $groupedReservations = $query->get()->groupBy('order_id');

        // Manual pagination for the grouped collection
        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage('page');
        $currentPageItems = $groupedReservations->slice(($currentPage - 1) * $perPage, $perPage);
        $reservations = new LengthAwarePaginator($currentPageItems, $groupedReservations->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

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
        $request->validate(['status' => 'required|in:Disewa,Selesai,Dibatalkan,Dibayar,Menunggu Pembayaran']);

        // Update status for all items in the same order
        $newStatus = $request->status;
        Reservation::where('order_id', $reservation->order_id)->update(['status' => $newStatus]);

        // If the status is 'Selesai' or 'Dibatalkan', we can consider updating stock logic here if needed.
        // For now, this logic is simplified as stock is checked during reservation creation.
        if ($newStatus === 'Selesai' || $newStatus === 'Dibatalkan') {
            // Logic to release stock can be added here if necessary.
            // For example, iterating through items and updating variant quantities.
        }

        return redirect()->route('admin.reservations.index')->with('success', 'Reservation status updated successfully.');
    }
}
