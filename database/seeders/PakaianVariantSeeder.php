<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PakaianVariantSeeder extends Seeder
{
    public function run()
    {
        DB::table('pakaian_variants')->insert([
            ['pakaian_adat_id' => 1, 'size' => 'All Size', 'quantity' => 1],
            ['pakaian_adat_id' => 2, 'size' => 'All Size', 'quantity' => 1],
            ['pakaian_adat_id' => 3, 'size' => 'All Size', 'quantity' => 1],
            ['pakaian_adat_id' => 4, 'size' => 'All Size', 'quantity' => 1],
            ['pakaian_adat_id' => 5, 'size' => 'All Size', 'quantity' => 1],
            ['pakaian_adat_id' => 6, 'size' => 'All Size', 'quantity' => 1],
            ['pakaian_adat_id' => 7, 'size' => 'M', 'quantity' => 10],
            ['pakaian_adat_id' => 7, 'size' => 'L', 'quantity' => 10],
            ['pakaian_adat_id' => 7, 'size' => 'XL', 'quantity' => 10],
            ['pakaian_adat_id' => 7, 'size' => 'XXL', 'quantity' => 10],
            ['pakaian_adat_id' => 7, 'size' => 'XXXL', 'quantity' => 10],
            ['pakaian_adat_id' => 8, 'size' => 'M', 'quantity' => 10],
            ['pakaian_adat_id' => 8, 'size' => 'L', 'quantity' => 10],
            ['pakaian_adat_id' => 8, 'size' => 'XL', 'quantity' => 10],
            ['pakaian_adat_id' => 8, 'size' => 'XXL', 'quantity' => 10],
            ['pakaian_adat_id' => 8, 'size' => 'XXXL', 'quantity' => 10],
            ['pakaian_adat_id' => 9, 'size' => 'M', 'quantity' => 3],
            ['pakaian_adat_id' => 9, 'size' => 'L', 'quantity' => 3],
            ['pakaian_adat_id' => 9, 'size' => 'XL', 'quantity' => 3],
            ['pakaian_adat_id' => 10, 'size' => '5-12 tahun', 'quantity' => 3],
            ['pakaian_adat_id' => 10, 'size' => '13-15 tahun', 'quantity' => 3],
            ['pakaian_adat_id' => 10, 'size' => '16-20', 'quantity' => 5],
            ['pakaian_adat_id' => 11, 'size' => 'L', 'quantity' => 3],
            ['pakaian_adat_id' => 11, 'size' => 'XL', 'quantity' => 3],
            ['pakaian_adat_id' => 11, 'size' => 'XXL', 'quantity' => 3],
            ['pakaian_adat_id' => 12, 'size' => 'M', 'quantity' => 10],
            ['pakaian_adat_id' => 12, 'size' => 'L', 'quantity' => 10],
            ['pakaian_adat_id' => 12, 'size' => 'XL', 'quantity' => 10],
            ['pakaian_adat_id' => 12, 'size' => 'XXL', 'quantity' => 10],
            ['pakaian_adat_id' => 13, 'size' => 'M', 'quantity' => 5],
            ['pakaian_adat_id' => 13, 'size' => 'L', 'quantity' => 5],
            ['pakaian_adat_id' => 13, 'size' => 'XL', 'quantity' => 5],
            ['pakaian_adat_id' => 13, 'size' => 'XXL', 'quantity' => 3],
            ['pakaian_adat_id' => 14, 'size' => 'M', 'quantity' => 5],
            ['pakaian_adat_id' => 14, 'size' => 'L', 'quantity' => 10],
            ['pakaian_adat_id' => 14, 'size' => 'XL', 'quantity' => 10],
            ['pakaian_adat_id' => 14, 'size' => 'XXL', 'quantity' => 10],
            ['pakaian_adat_id' => 14, 'size' => 'XXXL', 'quantity' => 20],
            ['pakaian_adat_id' => 15, 'size' => 'L', 'quantity' => 2],
        ]);
    }
}
