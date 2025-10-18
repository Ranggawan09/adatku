<?php

namespace App\Http\Controllers;

use App\Models\PakaianAdat;
use App\Models\Reservation;
use App\Models\PakaianVariant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;

class ClientReservationController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans saat controller diinisialisasi
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

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
        
        // Buat order_id unik setelah reservation memiliki ID
        $order_id = 'ADATKU-' . $reservation->id . '-' . time();
        $reservation->order_id = $order_id;

        // --- Integrasi Midtrans Dimulai Di Sini ---

        // 1. Buat detail transaksi untuk Midtrans
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $reservation->total_price,
        ];

        // 2. Buat detail item
        $item_details = [
            [
                'id' => $variant->id,
                'price' => $pakaianAdat->price_per_day,
                'quantity' => $reservation->days,
                'name' => $pakaianAdat->nama . ' (' . $variant->size . ')',
            ],
        ];

        // 3. Buat detail pelanggan
        $customer_details = [
            'first_name' => $user->name,
            'email' => $user->email, // Pastikan ada email, atau berikan nilai default
            'phone' => $user->phone,
            'billing_address' => [
                'address' => $user->alamat,
            ]
        ];

        // 4. Gabungkan semua parameter untuk dikirim ke Midtrans
        $transaction = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
            'callbacks' => [
                'finish' => route('payment.finish', $reservation->id),
            ],
        ];

        try {
            // 5. Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($transaction);
            
            // 6. Simpan snap_token ke database
            $reservation->snap_token = $snapToken;
            $reservation->save();

            // 7. Redirect ke halaman pembayaran
            return redirect()->route('payment', $reservation->id);

        } catch (\Exception $e) {
            // Tangani jika ada error dari Midtrans
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage())->withInput();
        }
    }

    public function payment(Reservation $reservation)
    {
        // Di sini kita tidak bisa memvalidasi dengan Auth::id() karena user tidak login
        // Namun, karena URL-nya unik dan hanya diketahui setelah reservasi, ini cukup aman untuk alur ini.
        return view('payment', [
            'snapToken' => $reservation->snap_token,
            'reservation' => $reservation,
        ]);
    }

    public function paymentFinish(Reservation $reservation)
{
    try {
        /** @var \stdClass $status */
        $status = \Midtrans\Transaction::status($reservation->order_id);

        if ($status->transaction_status === 'settlement' || $status->transaction_status === 'capture') {
            if ($reservation->payment_status !== 'Lunas') {
                $reservation->payment_status = 'Lunas';
                $reservation->status = 'Aktif';
                if (isset($status->payment_type)) {
                    $reservation->payment_method = $status->payment_type;
                }
                $reservation->save();
            }
        }

        $reservation->refresh();

        return view('payment_finish', compact('reservation'));
    } catch (\Exception $e) {
        return view('payment_finish', compact('reservation'));
    }
}

    /**
     * Handle Pay at Store option.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function payAtStore(Reservation $reservation)
    {
        // Set payment method and due date
        $reservation->payment_method = 'Bayar di Tempat';
        $reservation->save();

        return redirect()->route('thankyou', ['reservation' => $reservation->id]);
    }
}
