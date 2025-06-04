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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->string('id_tagihan', 20)->primary();
            $table->string('id_pasien', 20);
            $table->date('tanggal');
            $table->decimal('biaya_kunjungan', 10, 2);
            $table->decimal('biaya_pemeriksaan', 10, 2);
            $table->decimal('biaya_obat', 10, 2);
            $table->decimal('total_biaya', 10, 2)->default(0);
            $table->string('metode_pembayaran', 20);
            $table->foreign('id_pasien', 20)->references('id_pasien')->on('pasiens');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
