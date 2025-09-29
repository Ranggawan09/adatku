<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        $reservations = [];
        $users = range(2, 31); // asumsi user_id 2–20 sudah ada
        $pakaianAdatIds = range(1, 15); 
        $pakaianVariantIds = range(1, 39);

        $userReservationCount = array_fill_keys($users, 0);

        for ($i = 1; $i <= 30; $i++) {
            // pilih user yang belum lebih dari 2x
            do {
                $userId = $users[array_rand($users)];
            } while ($userReservationCount[$userId] >= 2);

            $userReservationCount[$userId]++;

            $pakaianAdatId = $pakaianAdatIds[array_rand($pakaianAdatIds)];
            $pakaianVariantId = $pakaianVariantIds[array_rand($pakaianVariantIds)];

            // buat tanggal random di bulan Oktober 2025
            $start = Carbon::create(2025, 9, rand(1, 27));
            $end = $start; // 1 hari

            $pricePerDay = [50000, 100000, 150000, 180000, 195000, 210000, 240000, 750000][array_rand([0,1,2,3,4,5,6,7])];

            $reservations[] = [
                'user_id' => $userId,
                'pakaian_adat_id' => $pakaianAdatId,
                'pakaian_variant_id' => $pakaianVariantId,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'days' => 1,
                'price_per_day' => $pricePerDay,
                'total_price' => $pricePerDay,
                'status' => ['Pending', 'Active', 'Canceled'][array_rand(['Pending','Active','Canceled'])],
                'payment_status' => ['Pending', 'Paid', 'Canceled'][array_rand(['Pending','Paid','Canceled'])],
                'created_at' => $start->toDateString(),
                'updated_at' => $start->toDateString(),
            ];
        }

        DB::table('reservations')->insert($reservations);
    }
}
