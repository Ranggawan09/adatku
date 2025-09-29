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
        Schema::create('pakaian_adats', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['Pria', 'Wanita', 'Anak Laki-Laki', 'Anak Perempuan']);
            $table->string('asal');
            $table->text('deskripsi')->nullable();
            $table->string('warna')->nullable();
            $table->integer('price_per_day');
            $table->string('image')->nullable();
            $table->enum('status', ['Tersedia', 'Tidak Tersedia'])->default('Tersedia');
            $table->integer('reduce');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pakaian_adats');
    }
};
