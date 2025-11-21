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
            // Inisialisasi notifikasi
            $notification = new Notification();
            
            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            \Log::info('Midtrans Webhook Received:', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payment_type' => $paymentType,
                'fraud_status' => $fraudStatus,
            ]);

            // Ambil semua reservasi dengan order_id ini
            $reservations = Reservation::where('order_id', $orderId)->get();

            if ($reservations->isEmpty()) {
                \Log::warning('Reservation not found for order_id: ' . $orderId);
                return response()->json(['error' => 'Reservation not found'], 404);
            }

            // Ambil satu reservasi untuk verifikasi signature
            $firstReservation = $reservations->first();
            
            // Verifikasi signature key
            $grossAmount = number_format($firstReservation->total_price, 2, '.', '');
            $signatureKey = hash('sha512', $notification->order_id . $notification->status_code . $grossAmount . config('midtrans.server_key'));

            if ($notification->signature_key != $signatureKey) {
                \Log::error('Invalid signature for order_id: ' . $orderId);
                return response()->json(['error' => 'Invalid signature'], 403);
            }

            // Update status pembayaran berdasarkan notifikasi
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    // Transaksi capture berhasil (untuk credit card)
                    foreach ($reservations as $reservation) {
                        if ($reservation->payment_status !== 'Lunas') {
                            $reservation->update([
                                'payment_status' => 'Lunas',
                                'status' => 'Disewa',
                                'payment_method' => $paymentType,
                            ]);
                        }
                    }
                    \Log::info('Payment captured successfully for order_id: ' . $orderId);
                }
            } elseif ($transactionStatus == 'settlement') {
                // Transaksi settlement (berhasil untuk non-credit card)
                foreach ($reservations as $reservation) {
                    if ($reservation->payment_status !== 'Lunas') {
                        $reservation->update([
                            'payment_status' => 'Lunas',
                            'status' => 'Disewa',
                            'payment_method' => $paymentType,
                        ]);
                    }
                }
                \Log::info('Payment settled successfully for order_id: ' . $orderId);
            } elseif ($transactionStatus == 'pending') {
                // Pembayaran pending (menunggu)
                foreach ($reservations as $reservation) {
                    $reservation->update([
                        'payment_status' => 'Pending',
                        'status' => 'Pending',
                        'payment_method' => $paymentType,
                    ]);
                }
                \Log::info('Payment pending for order_id: ' . $orderId);
            } elseif ($transactionStatus == 'deny') {
                // Pembayaran ditolak
                foreach ($reservations as $reservation) {
                    $reservation->update([
                        'payment_status' => 'Gagal',
                        'status' => 'Canceled',
                    ]);
                }
                \Log::info('Payment denied for order_id: ' . $orderId);
            } elseif ($transactionStatus == 'expire') {
                // Pembayaran kedaluwarsa
                foreach ($reservations as $reservation) {
                    $reservation->update([
                        'payment_status' => 'Kedaluwarsa',
                        'status' => 'Canceled',
                    ]);
                }
                \Log::info('Payment expired for order_id: ' . $orderId);
            } elseif ($transactionStatus == 'cancel') {
                // Pembayaran dibatalkan
                foreach ($reservations as $reservation) {
                    $reservation->update([
                        'payment_status' => 'Dibatalkan',
                        'status' => 'Canceled',
                    ]);
                }
                \Log::info('Payment cancelled for order_id: ' . $orderId);
            }

            return response()->json(['message' => 'Notification handled successfully']);

        } catch (\Exception $e) {
            \Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Invalid notification'], 400);
        }
    }
}