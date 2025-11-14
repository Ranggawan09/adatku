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
        $users = range(2, 31); // asumsi user_id 2–31 sudah ada
        $pakaianAdatIds = range(1, 15); 
        $pakaianVariantIds = range(1, 39);

        $userReservationCount = array_fill_keys($users, 0);

        // Buat sekitar 20 pesanan, beberapa di antaranya akan memiliki lebih dari satu item.
        for ($i = 1; $i <= 20; $i++) {
            // Buat satu order_id unik untuk setiap pesanan dalam loop ini.
            $orderId = time() + $i;
            $itemsInOrder = rand(1, 3); // Setiap pesanan akan memiliki 1-3 item.

            // Pilih user sekali untuk setiap pesanan.
            do {
                $userId = $users[array_rand($users)];
            } while ($userReservationCount[$userId] >= 2);
            $userReservationCount[$userId]++;

            // Tentukan tanggal sewa sekali untuk setiap pesanan.
            $start = Carbon::create(2025, 10, rand(1, 27));
            $end = $start->copy()->addDays(rand(0, 2)); // Durasi 1-3 hari
            $days = $start->diffInDays($end) + 1;
            
            // Tentukan status sekali untuk setiap pesanan agar konsisten.
            $status = ['Disewa', 'Selesai', 'Dibatalkan', 'Dibayar', 'Menunggu Pembayaran'][array_rand(['Disewa', 'Selesai', 'Dibatalkan', 'Dibayar', 'Menunggu Pembayaran'])];
            $paymentStatus = ($status === 'Dibayar' || $status === 'Disewa' || $status === 'Selesai') ? 'Lunas' : 'Pending';
            
            for ($j = 0; $j < $itemsInOrder; $j++) {
                $pakaianAdatId = $pakaianAdatIds[array_rand($pakaianAdatIds)];
                $pakaianVariantId = $pakaianVariantIds[array_rand($pakaianVariantIds)];
                $pricePerDay = [50000, 100000, 150000, 180000, 195000, 210000, 240000, 300000][array_rand([0,1,2,3,4,5,6,7])];
                
                $reservations[] = [
                    'order_id' => $orderId, // Gunakan order_id yang sama untuk semua item dalam pesanan ini.
                    'user_id' => $userId,
                    'pakaian_adat_id' => $pakaianAdatId,
                    'pakaian_variant_id' => $pakaianVariantId,
                    'start_date' => $start->toDateString(),
                    'end_date' => $end->toDateString(),
                    'days' => $days,
                    'price_per_day' => $pricePerDay,
                    'total_price' => $pricePerDay * $days,
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'created_at' => $start->copy()->subDays(rand(1, 5)), // Waktu dibuat sebelum tanggal sewa
                    'updated_at' => $start->copy()->subDays(rand(1, 5)),
                ];
            }
        }

        DB::table('reservations')->insert($reservations);
    }
}
