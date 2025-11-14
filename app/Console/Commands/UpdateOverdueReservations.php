<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class UpdateOverdueReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:update-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue reservations, update their status to "Terlambat", and calculate late fees.';

    /**
     * The late fee per day.
     *
     * @var int
     */
    const LATE_FEE_PER_DAY = 50000;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue reservations...');

        // Temukan semua order_id unik dari reservasi yang statusnya 'Disewa' dan sudah melewati tanggal pengembalian.
        $overdueOrderIds = Reservation::where('status', 'Disewa')
            ->whereDate('end_date', '<', Carbon::today())
            ->distinct()
            ->pluck('order_id');

        if ($overdueOrderIds->isEmpty()) {
            $this->info('No overdue reservations found.');
            return;
        }

        $this->info("Found {$overdueOrderIds->count()} overdue order(s). Updating status and late fees...");

        foreach ($overdueOrderIds as $orderId) {
            // Ambil semua item reservasi yang terkait dengan order_id ini.
            $reservationsInOrder = Reservation::where('order_id', $orderId)->get();
            
            // Gunakan item pertama untuk kalkulasi (karena tanggalnya sama untuk semua item dalam satu pesanan)
            $firstItem = $reservationsInOrder->first();
            $endDate = Carbon::parse($firstItem->end_date);
            $lateDays = Carbon::today()->diffInDays($endDate);
            $lateFee = $lateDays * self::LATE_FEE_PER_DAY;

            // Perbarui semua item dalam pesanan
            Reservation::where('order_id', $orderId)->update([
                'status' => 'Terlambat',
                'payment_status' => 'Pending', // Ubah status pembayaran menjadi Pending
                'late_fee' => $lateFee
            ]);

            $this->warn("Order #{$orderId} is overdue by {$lateDays} day(s). Status updated to 'Terlambat', payment status to 'Pending', with a fee of Rp" . number_format($lateFee) . ".");
        }

        $this->info('Finished updating overdue reservations.');
    }
}