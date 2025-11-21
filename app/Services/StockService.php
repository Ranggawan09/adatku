<?php

namespace App\Services;

use App\Models\PakaianVariant;
use App\Models\Reservation;
use Carbon\Carbon;

class StockService
{
    /**
     * Menghitung jumlah stok yang tersedia untuk varian tertentu pada rentang tanggal yang diberikan.
     *
     * @param PakaianVariant $variant
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return int
     */
    public function getAvailableStockForRange(PakaianVariant $variant, Carbon $startDate, Carbon $endDate): int
    {
        // 1. Ambil total kuantitas fisik yang dimiliki varian ini.
        $totalPhysicalStock = $variant->quantity;

        // 2. Hitung jumlah maksimum item yang sudah dipesan (status Pending atau Disewa)
        //    pada satu hari dalam rentang tanggal yang diminta.
        $maxReservedOnDate = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $reservedOnThisDate = Reservation::where('pakaian_variant_id', $variant->id)
                ->whereIn('status', ['Pending', 'Disewa'])
                ->whereDate('start_date', '<=', $currentDate)
                ->whereDate('end_date', '>=', $currentDate)
                ->sum('quantity');

            if ($reservedOnThisDate > $maxReservedOnDate) {
                $maxReservedOnDate = $reservedOnThisDate;
            }
            $currentDate->addDay();
        }

        // 3. Stok yang tersedia adalah total stok dikurangi jumlah maksimum yang sudah dipesan.
        return $totalPhysicalStock - $maxReservedOnDate;
    }
}