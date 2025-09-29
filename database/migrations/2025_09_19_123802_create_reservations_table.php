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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('pakaian_adat_id');
            $table->foreign('pakaian_adat_id')->references('id')->on('pakaian_adats');

            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days');
            $table->decimal('price_per_day', 10, 2);
            $table->decimal('total_price');
            $table->string('status')->default('active');
            $table->string('payment_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
