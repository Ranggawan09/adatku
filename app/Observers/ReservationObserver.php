<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Models\PakaianVariant;
use Illuminate\Support\Facades\Log;

class ReservationObserver
{
    /**
     * Handle the Reservation "updating" event.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return void
     */
    public function updating(Reservation $reservation)
    {
        // Periksa apakah field 'status' benar-benar berubah
        if ($reservation->isDirty('status')) {
            $originalStatus = $reservation->getOriginal('status');
            $newStatus = $reservation->status;
            $variant = $reservation->variant;

            if (!$variant) {
                Log::warning("ReservationObserver: PakaianVariant not found for Reservation ID: {$reservation->id}");
                return;
            }

            // Logika utama untuk penyesuaian stok
            // 1. Stok berkurang ketika reservasi menjadi 'Disewa'
            if ($newStatus === 'Disewa' && $originalStatus !== 'Disewa') {
                if ($variant->quantity > 0) {
                    $variant->decrement('quantity');
                    Log::info("Stock decreased for Variant ID: {$variant->id}. New quantity: {$variant->quantity}");
                } else {
                    Log::warning("Stock for Variant ID: {$variant->id} is already 0. Cannot decrement.");
                }
            }
            // 2. Stok kembali ketika reservasi yang tadinya 'Disewa' berubah status (misal: Selesai, Dibatalkan)
            elseif ($originalStatus === 'Disewa' && $newStatus !== 'Disewa') {
                $variant->increment('quantity');
                Log::info("Stock increased for Variant ID: {$variant->id}. New quantity: {$variant->quantity}");
            }
        }
    }
}