<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancelPendingReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:cancel-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel pending reservations for "Pay at Store" (24h) and abandoned online payments (2h)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai memeriksa reservasi yang tertunda untuk dibatalkan...');

        // --- Kasus 1: Batalkan reservasi online yang ditinggalkan setelah 2 jam ---
        $cutoffOnline = Carbon::now()->subHours(2);
        $abandonedReservations = Reservation::whereNull('payment_method') // Metode pembayaran masih kosong
            ->where('status', 'Pending')
            ->where('created_at', '<', $cutoffOnline)
            ->get();

        if ($abandonedReservations->isNotEmpty()) {
            $this->info("Menemukan {$abandonedReservations->count()} reservasi online yang ditinggalkan untuk dibatalkan.");
            foreach ($abandonedReservations as $reservation) {
                $reservation->status = 'Canceled';
                $reservation->payment_status = 'Canceled';
                $reservation->save();
                Log::info("Reservasi online #{$reservation->id} telah dibatalkan secara otomatis.");
                $this->line("-> Reservasi online #{$reservation->id} dibatalkan.");
            }
        }

        // --- Kasus 2: Batalkan reservasi "Bayar di Tempat" setelah 24 jam ---
        $cutoffCod = Carbon::now()->subHours(24);
        $codReservations = Reservation::where('payment_method', 'Bayar di Tempat')
            ->where('status', 'Pending')
            ->where('created_at', '<', $cutoffCod)
            ->get();

        if ($codReservations->isNotEmpty()) {
            $this->info("Menemukan {$codReservations->count()} reservasi 'Bayar di Tempat' yang kedaluwarsa untuk dibatalkan.");
            foreach ($codReservations as $reservation) {
                $reservation->status = 'Canceled';
                $reservation->payment_status = 'Canceled';
                $reservation->save();
                Log::info("Reservasi 'Bayar di Tempat' #{$reservation->id} telah dibatalkan secara otomatis.");
                $this->line("-> Reservasi 'Bayar di Tempat' #{$reservation->id} dibatalkan.");
            }
        }

        $this->info('Pemeriksaan selesai.');
    }
}
