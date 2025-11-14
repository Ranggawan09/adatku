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
            'reservation_dates' => 'required|string',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'required|exists:pakaian_variants,id',
            'variants.*.quantity' => 'required|integer|min:1',
        ], [
            'variants.required' => 'Anda harus memilih setidaknya satu ukuran dan jumlahnya.'
        ]);

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

        // Check if the user has more than 2 Aktif reservations
        $userReservationsCount = Reservation::where('user_id', $user->id)->whereIn('status', ['Pending', 'Disewa'])->distinct('order_id')->count();
        if ($userReservationsCount >= 2) {
            return redirect()->back()->with('error', 'Kamu tidak dapat memiliki 2 pesanan aktif 😉.');
        }

        // extract start and end date from the request
        $reservation_dates = explode(' to ', $request->reservation_dates);
        $start = Carbon::parse($reservation_dates[0]);
        $end = Carbon::parse($reservation_dates[1]);

        $requestedVariants = $request->variants;
        $reservations = [];
        $totalPrice = 0;
        $item_details = [];

        // Loop through each requested variant to check stock and prepare reservation data
        foreach ($requestedVariants as $variantId => $details) {
            $variant = PakaianVariant::findOrFail($variantId);
            $requestedQuantity = (int) $details['quantity'];

            // Check stock for the date range
            $currentDate = $start->copy();
            while ($currentDate->lte($end)) {
                $reservationsCountOnDate = Reservation::where('pakaian_variant_id', $variant->id)
                    ->whereIn('status', ['Pending', 'Disewa'])
                    ->where('start_date', '<=', $currentDate->toDateString())
                    ->where('end_date', '>=', $currentDate->toDateString())
                    ->sum('quantity');

                if (($reservationsCountOnDate + $requestedQuantity) > $variant->quantity) {
                    return redirect()->back()
                        ->with('error', 'Stok untuk ukuran ' . $variant->size . ' tidak cukup pada tanggal ' . $currentDate->format('d-m-Y') . '.')
                        ->withInput();
                }
                $currentDate->addDay();
            }

            $days = $start->diffInDays($end) ?: 1;
            $itemTotalPrice = $days * $pakaianAdat->price_per_day * $requestedQuantity;
            $totalPrice += $itemTotalPrice;

            $reservation = new Reservation([
                'user_id' => $user->id,
                'pakaian_adat_id' => $pakaianAdat->id,
                'pakaian_variant_id' => $variant->id,
                'quantity' => $requestedQuantity,
                'start_date' => $start,
                'end_date' => $end,
                'days' => $days,
                'price_per_day' => $pakaianAdat->price_per_day,
                'total_price' => $itemTotalPrice,
                'status' => 'Pending',
                'payment_status' => 'Pending',
            ]);
            $reservations[] = $reservation;

            $item_details[] = [
                'id' => $variant->id,
                'price' => $pakaianAdat->price_per_day,
                'quantity' => $days * $requestedQuantity,
                'name' => $pakaianAdat->nama . ' (' . $variant->size . ')',
            ];
        }

        // If all stock checks pass, save all reservations
        $order_id = time(); // Buat satu ID pesanan unik untuk seluruh transaksi
        $masterReservation = null; // The first reservation will carry the payment details

        foreach ($reservations as $index => $reservation) {
            $reservation->order_id = $order_id; // Gunakan order_id yang sama untuk semua item
            $reservation->save();
            if ($index === 0) {
                $masterReservation = $reservation;
            }
        }

        // --- Integrasi Midtrans Dimulai Di Sini ---

        // 1. Buat detail transaksi untuk Midtrans
        $transaction_details = [
            'order_id' => $order_id, // Gunakan ID pesanan yang sudah dibuat
            'gross_amount' => $totalPrice,
        ];

        // 3. Buat detail pelanggan
        $customer_details = [
            'first_name' => $user->name,
            'email' => $user->email ?? $user->phone . '@adatku.com', // Fallback email
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
                'finish' => route('payment.finish', $masterReservation->id), // Redirect to the master reservation's finish page
            ],
        ];

        try {
            // 5. Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($transaction);
            
            // 6. Simpan snap_token ke database
            Reservation::whereIn('id', array_map(fn($r) => $r->id, $reservations))
                ->update(['snap_token' => $snapToken]);

            $masterReservation->refresh(); // Refresh to get the snap_token

            // 7. Redirect ke halaman pembayaran
            return redirect()->route('payment', $masterReservation->id);

        } catch (\Exception $e) {
            // Tangani jika ada error dari Midtrans
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage())->withInput();
        }
    }

    public function payment(Reservation $reservation)
    {
        // Di sini kita tidak bisa memvalidasi dengan Auth::id() karena user tidak login
        // Namun, karena URL-nya unik dan hanya diketahui setelah reservasi, ini cukup aman untuk alur ini.

        // Ambil semua item reservasi yang terkait dengan snap_token ini
        $relatedReservations = Reservation::where('snap_token', $reservation->snap_token)
            ->with(['pakaianAdat', 'variant']) // Eager load untuk menampilkan detail
            ->get();
        $totalPrice = $relatedReservations->sum('total_price');

        return view('payment', [
            'snapToken' => $reservation->snap_token,
            'reservation' => $reservation,
            'relatedReservations' => $relatedReservations,
            'totalPrice' => $totalPrice, // Kirim total harga yang benar ke view
        ]);
    }

    public function paymentFinish(Reservation $reservation)
{
    // Ambil semua reservasi yang terkait dengan transaksi ini menggunakan snap_token
    $relatedReservations = Reservation::where('snap_token', $reservation->snap_token)->get();
    $totalPrice = $relatedReservations->sum('total_price');

    // Gunakan order_id dari reservasi untuk memeriksa status transaksi di Midtrans.
    $masterOrderId = $reservation->order_id;
    try {
        /** @var \stdClass $status */
        $status = \Midtrans\Transaction::status($masterOrderId);

        if ($status->transaction_status === 'settlement' || $status->transaction_status === 'capture') {
            // Update semua reservasi terkait jika statusnya belum Lunas
            foreach ($relatedReservations as $res) {
                if ($res->payment_status !== 'Lunas') {
                    $res->payment_status = 'Lunas';
                    $res->status = 'Pending';
                    if (isset($status->payment_type)) {
                        $res->payment_method = $status->payment_type;
                    }
                    $res->save();
                }
            }
        }

        $reservation->refresh(); // Refresh data reservasi utama

        return view('payment_finish', compact('reservation', 'relatedReservations', 'totalPrice', 'masterOrderId'));
    } catch (\Exception $e) {
        return view('payment_finish', compact('reservation', 'relatedReservations', 'totalPrice', 'masterOrderId'));
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\View\View
     */
    public function invoice(Reservation $reservation)
    {
        // Ambil semua reservasi yang terkait dengan transaksi ini menggunakan snap_token
        $relatedReservations = Reservation::where('snap_token', $reservation->snap_token)->with(['pakaianAdat', 'variant'])->get();
        $totalPrice = $relatedReservations->sum('total_price');
        $masterOrderId = substr($reservation->order_id, 0, 10); // Timestamp memiliki 10 digit

        return view('invoice', compact('reservation', 'relatedReservations', 'totalPrice', 'masterOrderId'));
    }
}
