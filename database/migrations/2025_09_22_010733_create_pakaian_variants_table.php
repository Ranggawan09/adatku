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
        Schema::create('pakaian_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pakaian_adat_id')->constrained('pakaian_adats')->onDelete('cascade');
            $table->string('size');
            $table->unsignedInteger('quantity');
            $table->timestamps();

            // Menambahkan unique constraint untuk memastikan tidak ada ukuran duplikat untuk satu produk
            $table->unique(['pakaian_adat_id', 'size']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakaian_variants');
    }
};
