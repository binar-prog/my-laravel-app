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
        Schema::create('pemeriksaan_lanjutans', function (Blueprint $table) {
            $table->string('id_pemeriksaan', 20)->primary();
            $table->string('id_pasien', 20);
            $table->string('id_dokter',20);

            $table->date('tanggal_pemeriksaan');
            $table->string('jenis_pemeriksaan', 100);
            $table->text('hasil_pemeriksaan');
            $table->foreign('id_pasien')->references('id_pasien')->on('pasiens')->onDelete('cascade');
            $table->foreign('id_dokter')->references('id_dokter')->on('dokters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_lanjutans');
    }
};
