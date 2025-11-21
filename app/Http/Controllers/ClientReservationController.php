<?php

namespace App\Http\Controllers;

use App\Models\PakaianAdat;
use App\Models\Reservation;
use App\Models\PakaianVariant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Services\StockService;
use Midtrans\Config;
use Illuminate\Support\Facades\Cache;
use Midtrans\Snap;

class ClientReservationController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function create($pakaianAdat_id)
    {
        $pakaianAdat = PakaianAdat::findOrFail($pakaianAdat_id);
        $pakaianAdat->load(['variants' => function ($query) {
            $query->where('quantity', '>', 0);
        }]);
        return view('reservation.create', compact('pakaianAdat'));
    }

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

        $userReservationsCount = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'Disewa'])
            ->distinct('order_id')
            ->count();
            
        if ($userReservationsCount >= 2) {
            return redirect()->back()->with('error', 'Kamu tidak dapat memiliki 2 pesanan aktif 😉.');
        }

        $reservation_dates = explode(' to ', $request->reservation_dates);
        $start = Carbon::parse($reservation_dates[0]);
        $end = Carbon::parse($reservation_dates[1]);

        $requestedVariants = $request->variants;
        $reservations = [];
        $totalPrice = 0;
        $item_details = [];

        foreach ($requestedVariants as $variantId => $details) {
            $variant = PakaianVariant::findOrFail($variantId);
            $requestedQuantity = (int) $details['quantity'];

            $availableStock = $this->stockService->getAvailableStockForRange($variant, $start, $end);

            if ($requestedQuantity > $availableStock) {
                return redirect()->back()
                    ->with('error', 'Stok untuk ukuran ' . $variant->size . ' tidak cukup pada tanggal yang dipilih. Tersisa ' . $availableStock . ' buah.')
                    ->withInput();
            }

            $days = $start->diffInDays($end) ?: 1;
            $itemTotalPrice = $days * $pakaianAdat->price_per_day * $requestedQuantity;
            $totalPrice += $itemTotalPrice;

            $reservations[] = [
                'pakaian_adat_id' => $pakaianAdat->id,
                'pakaian_variant_id' => $variant->id,
                'quantity' => $requestedQuantity,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'days' => $days,
                'price_per_day' => $pakaianAdat->price_per_day,
                'total_price' => $itemTotalPrice,
            ];

            $item_details[] = [
                'id' => $variant->id,
                'price' => $pakaianAdat->price_per_day,
                'quantity' => $days * $requestedQuantity,
                'name' => $pakaianAdat->nama . ' (' . $variant->size . ')',
            ];
        }

        $order_id = time(); // Mengubah format order_id sesuai permintaan

        // Simpan data reservasi ke Cache selama 1 jam.
        Cache::put('pending_reservation_' . $order_id, [
            'order_id' => $order_id,
            'user_id' => $user->id,
            'items' => $reservations,
            'total_price' => $totalPrice,
        ], now()->addHour());

        $request->session()->put('current_order_id', $order_id); // Simpan order_id di sesi untuk referensi

        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => $totalPrice,
        ];

        $customer_details = [
            'first_name' => $user->name,
            'phone' => $user->phone,
            'billing_address' => [
                'address' => $user->alamat,
            ]
        ];

        $transaction = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
            'callbacks' => [
                'finish' => route('payment.finish.redirect'),
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($transaction);
            $request->session()->put('snap_token', $snapToken);

            return redirect()->route('payment.show');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage())->withInput();
        }
    }

    public function showPaymentPage(Request $request)
    {
        $order_id = $request->session()->get('current_order_id');
        $snapToken = $request->session()->get('snap_token');
        $pendingReservation = Cache::get('pending_reservation_' . $order_id);

        if (!$snapToken || !$pendingReservation || !$order_id) {
            return redirect()->route('home')->with('error', 'Sesi pembayaran tidak ditemukan atau telah kedaluwarsa.');
        }

        $displayItems = [];
        foreach ($pendingReservation['items'] as $item) {
            $pakaianAdat = PakaianAdat::find($item['pakaian_adat_id']);
            $variant = PakaianVariant::find($item['pakaian_variant_id']);

            if ($pakaianAdat && $variant) {
                $displayItems[] = (object) [ // Cast to object to mimic Eloquent model access
                    'pakaianAdat' => (object) ['nama' => $pakaianAdat->nama],
                    'variant' => (object) ['size' => $variant->size],
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                ];
            }
        }

        // Get reservation dates and days from the first item (assuming all items in a reservation share these)
        $firstItem = (object) $pendingReservation['items'][0];

        return view('payment', [
            'snapToken' => $snapToken,
            'totalPrice' => $pendingReservation['total_price'],
            'relatedReservations' => $displayItems, // Pass the constructed items
            'reservation' => $firstItem, // Pass the first item as 'reservation' for dates/days
            'orderId' => $pendingReservation['order_id'],
        ]);
    }

    /**
     * Redirect handler setelah user menyelesaikan/menutup popup Midtrans
     */
    public function paymentFinishRedirect(Request $request)
    {
        $order_id = $request->query('order_id');
        $transaction_status = $request->query('status');
        $payment_type = $request->query('type');

        $pendingReservationData = Cache::get('pending_reservation_' . $order_id);

        // Jika tidak ada order_id dari parameter, kemungkinan pembayaran pending/gagal/ditutup
        if (!$order_id) {
            // Ambil order_id dari sesi untuk halaman gagal
            $session_order_id = $request->session()->get('current_order_id', 'unknown');
            return redirect()->route('payment.finish', ['order_id' => $session_order_id, 'status' => 'failed'])->with('message', 'Pembayaran Anda belum selesai dan pesanan dibatalkan.');
        }
        
        // Hanya proses jika statusnya 'settlement' atau 'capture'
        if ($transaction_status === 'settlement' || $transaction_status === 'capture') {
            // Cek dulu apakah reservasi sudah dibuat (misalnya oleh notifikasi handler yang mungkin lebih cepat)
            $existingReservation = Reservation::where('order_id', $order_id)->first();

            // Jika reservasi belum ada DAN data sesi ada, kita buat sekarang.
            if (!$existingReservation && $pendingReservationData) {
                foreach ($pendingReservationData['items'] as $item) {
                    Reservation::create([
                        'order_id' => $order_id,
                        'user_id' => $pendingReservationData['user_id'],
                        'pakaian_adat_id' => $item['pakaian_adat_id'],
                        'pakaian_variant_id' => $item['pakaian_variant_id'],
                        'quantity' => $item['quantity'],
                        'start_date' => $item['start_date'],
                        'end_date' => $item['end_date'],
                        'days' => $item['days'],
                        'price_per_day' => $item['price_per_day'],
                        'total_price' => $item['total_price'],
                        'status' => 'Pending', // Status sewa awal setelah bayar
                        'payment_status' => 'Lunas',
                        'payment_method' => $payment_type ?? 'N/A',
                        'snap_token' => $request->session()->get('snap_token'),
                    ]);
                }
                // Hapus data dari cache setelah berhasil diproses
                Cache::forget('pending_reservation_' . $order_id);
            }
            return redirect()->route('payment.finish', ['order_id' => $order_id, 'status' => 'success']);
        } else {
            // Untuk status lain (pending, deny, expire), anggap gagal
            return redirect()->route('payment.finish', ['order_id' => $order_id, 'status' => 'failed'])->with('message', 'Pembayaran Anda tidak berhasil diselesaikan.');
        }
    }

    public function paymentFinish(Request $request, $order_id)
    {
        $status = $request->query('status');

        // Hapus data reservasi dari session untuk semua status kecuali pending
        if ($status === 'success' || $status === 'failed') {
            $request->session()->forget(['current_order_id', 'snap_token']);
        }

        if ($status === 'failed') {
            return view('payment_finish', [
                'success' => false,
                'message' => 'Pembayaran Anda gagal atau dibatalkan. Silakan coba lagi.',
                'order_id' => $order_id,
            ]);
        }

        // Untuk status 'success', kita cek apakah reservasi sudah dibuat
        $relatedReservations = Reservation::where('order_id', $order_id)
            ->with(['pakaianAdat', 'variant'])
            ->get();

        if ($relatedReservations->isEmpty()) {
            // Jika reservasi belum ada, mungkin notifikasi Midtrans belum diterima.
            // Tampilkan pesan tunggu dan instruksikan untuk refresh.
            return view('payment_finish', [
                'success' => false,
                'success' => false,
                'message' => 'Transaksi tidak ditemukan atau pembayaran belum selesai.',
                'order_id' => $order_id,
            ]);
        }

        $totalPrice = $relatedReservations->sum('total_price');
        $reservation = $relatedReservations->first();

        return view('payment_finish', [
            'success' => true,
            'reservation' => $reservation,
            'relatedReservations' => $relatedReservations,
            'totalPrice' => $totalPrice,
            'masterOrderId' => $order_id,
        ]);
    }

    public function invoice(Reservation $reservation)
    {
        $relatedReservations = Reservation::where('snap_token', $reservation->snap_token)
            ->with(['pakaianAdat', 'variant'])
            ->get();
            
        $totalPrice = $relatedReservations->sum('total_price');
        $masterOrderId = substr($reservation->order_id, 0, 10);

        return view('invoice', compact('reservation', 'relatedReservations', 'totalPrice', 'masterOrderId'));
    }

    /**
     * Midtrans notification handler.
     */
    public function notificationHandler(Request $request)
    {
        // Buat instance Midtrans notification
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Hanya proses jika transaksi berhasil (settlement)
        if ($transaction == 'settlement' || $transaction == 'capture') {
            // Webhook hanya bertugas sebagai backup jika redirect gagal atau lambat.
            $existingReservation = Reservation::where('order_id', $order_id)->first();

            // Jika reservasi belum ada, artinya redirect gagal/lambat. Buat dari data cache.
            if (!$existingReservation) {
                $pendingReservationData = Cache::get('pending_reservation_' . $order_id);

                if ($pendingReservationData) {
                    foreach ($pendingReservationData['items'] as $item) {
                        Reservation::create([
                            'order_id' => $order_id,
                            'user_id' => $pendingReservationData['user_id'],
                            'pakaian_adat_id' => $item['pakaian_adat_id'],
                            'pakaian_variant_id' => $item['pakaian_variant_id'],
                            'quantity' => $item['quantity'],
                            'start_date' => $item['start_date'],
                            'end_date' => $item['end_date'],
                            'days' => $item['days'],
                            'price_per_day' => $item['price_per_day'],
                            'total_price' => $item['total_price'],
                            'status' => 'Pending',
                            'payment_status' => 'Lunas',
                            'payment_method' => $type, // Ambil dari notifikasi
                        ]);
                    }
                    // Hapus data dari cache setelah berhasil diproses
                    Cache::forget('pending_reservation_' . $order_id);
                }
            }

        } else if ($transaction == 'cancel' || $transaction == 'expire') {
            // Jika ada notifikasi pembatalan atau kedaluwarsa dari Midtrans
            $reservationsToCancel = Reservation::where('order_id', $order_id)
                                                ->where('payment_status', 'Unpaid')
                                                ->get();
            
            foreach($reservationsToCancel as $reservation) {
                $reservation->update(['status' => 'Batal']);
            }
        }

        return response()->json(['message' => 'Notification processed.']);
    }
}