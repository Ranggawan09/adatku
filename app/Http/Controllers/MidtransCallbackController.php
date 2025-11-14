<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            // Inisialisasi notifikasi dengan input dari request, bukan dari php://input
            $notification = new Notification($request->all());
        } catch (\Exception $e) {
            // Log error jika payload tidak valid
            \Illuminate\Support\Facades\Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid notification'], 400);
        }

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;
        $reservation = Reservation::where('order_id', $orderId)->first();

        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        // Verifikasi signature key
        // Pastikan gross_amount diformat sebagai string dengan .00 untuk konsistensi hash
        $grossAmount = number_format($reservation->total_price, 2, '.', '');
        $signatureKey = hash('sha512', $notification->order_id . $notification->status_code . $grossAmount . config('midtrans.server_key'));

        if ($notification->signature_key != $signatureKey) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Update status pembayaran berdasarkan notifikasi
        $originalStatus = $reservation->status;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            // Hanya update jika belum Lunas untuk mencegah trigger observer berulang
            if ($reservation->payment_status !== 'Lunas') {
                $reservation->payment_status = 'Lunas';
                $reservation->status = 'Disewa';
                $reservation->payment_method = $notification->payment_type;
            }
        } elseif ($transactionStatus == 'pending') {
            $reservation->payment_status = 'Pending';
            $reservation->status = 'Pending';
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // Jika status sebelumnya adalah 'Disewa' (misal karena settlement lalu di-cancel),
            // maka stok akan dikembalikan oleh Observer.
            // Kita set status pembayaran dan reservasi menjadi 'Canceled'.
            $reservation->payment_status = 'Canceled';
            $reservation->status = 'Canceled'; // Juga batalkan reservasi
        }

        $reservation->save();

        return response()->json(['message' => 'Notification handled successfully']);
    }
}