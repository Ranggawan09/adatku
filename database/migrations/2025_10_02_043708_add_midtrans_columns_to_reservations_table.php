<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Tambahkan kolom untuk menyimpan order_id unik dari Midtrans
            $table->string('order_id')->unique()->nullable()->after('id');
            // Tambahkan kolom untuk menyimpan snap_token dari Midtrans
            $table->string('snap_token')->nullable()->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'snap_token']);
        });
    }
};
