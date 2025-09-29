<?php

namespace App\Http\Controllers;

use App\Models\PakaianAdat;
use App\Models\Reservation;
use App\Models\PakaianVariant;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ClientReservationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create($pakaianAdat_id)
    {
        $pakaianAdat = PakaianAdat::findOrFail($pakaianAdat_id);
        // Eager load variants that have quantity > 0
        $pakaianAdat->load(['variants' => function ($query) {
            $query->where('quantity', '>', 0);
        }]);
        return view('reservation.create', compact('pakaianAdat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $pakaianAdat_id)
    {
        $request->validate([
            'full-name' => ['required', 'string', 'max:255'],   
            'phone' => ['required', 'string', 'min:11', 'max:20'],
            'alamat' => ['required', 'string'],
            'variant_id' => 'required|exists:pakaian_variants,id',
            'reservation_dates' => 'required|string',
        ]);


        $variant = PakaianVariant::findOrFail($request->variant_id);
        $pakaianAdat = PakaianAdat::findOrFail($pakaianAdat_id);

        // Cari pengguna berdasarkan nama DAN nomor telepon.
        // Jika tidak ada yang cocok, buat pengguna baru.
        $user = User::firstOrCreate(
            [
                'name' => $request->input('full-name'),
                'phone' => $request->phone,
            ],
            [
            'name' => $request->input('full-name'),
            'alamat' => $request->alamat,
            'phone' => $request->phone,
            'role' => 'client',
            ]
        );

        // Check if the user has more than 2 active reservations
        $userReservationsCount = Reservation::where('user_id', $user->id)->whereIn('status', ['Pending', 'Aktif'])->count();
        if ($userReservationsCount >= 2) {
            return redirect()->back()->with('error', 'You cannot have more than 2 active reservations 😉.');
        }

        // extract start and end date from the request
        $reservation_dates = explode(' to ', $request->reservation_dates);
        $start = Carbon::parse($reservation_dates[0]);
        $end = Carbon::parse($reservation_dates[1]);

        // Pengecekan stok berdasarkan tanggal
        $stock = $variant->quantity;
        $currentDate = $start->copy();
        while ($currentDate->lte($end)) {
            // Hitung jumlah reservasi yang ada pada tanggal ini
            $reservationsCountOnDate = Reservation::where('pakaian_variant_id', $variant->id)
                ->whereIn('status', ['Pending', 'Aktif'])
                ->where('start_date', '<=', $currentDate->toDateString())
                ->where('end_date', '>=', $currentDate->toDateString())
                ->count();
            
            // Jika jumlah reservasi sudah sama atau lebih dari stok, tolak reservasi
            if ($reservationsCountOnDate >= $stock) {
                return redirect()->back()
                    ->with('error', 'Maaf, stok untuk ukuran ' . $variant->size . ' tidak tersedia pada tanggal ' . $currentDate->format('d-m-Y') . '. Silakan pilih tanggal lain.')
                    ->withInput();
            }
            
            $currentDate->addDay();
        }

        $reservation = new Reservation();
        $reservation->user()->associate($user);
        $reservation->pakaian_adat_id = $pakaianAdat->id;
        $reservation->pakaian_variant_id = $variant->id;
        $reservation->start_date = $start;
        $reservation->end_date = $end;
        $reservation->days = $start->diffInDays($end) ?: 1; // Ensure at least 1 day
        $reservation->price_per_day = $pakaianAdat->price_per_day;
        $reservation->total_price = $reservation->days * $reservation->price_per_day;
        $reservation->status = 'Pending';
        $reservation->payment_status = 'Pending'; 
        $reservation->save();

        return view('thankyou', ['reservation' => $reservation]);
    }
}
