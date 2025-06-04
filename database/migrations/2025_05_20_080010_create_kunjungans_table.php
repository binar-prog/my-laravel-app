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
        Schema::create('kunjungans', function (Blueprint $table) {
            $table->string('id_kunjungan', 20)->primary();
            $table->string('id_pasien', 20);
            $table->string('id_dokter',20);
            $table->string('nama_poli',20);
            $table->dateTime('tanggal_jam');
            $table->text('diagnosis');

            $table->foreign('id_pasien')->references('id_pasien')->on('pasiens')->onDelete('cascade');
            $table->foreign('id_dokter' )->references('id_dokter')->on('dokters')->onDelete('cascade');
            $table->foreign('nama_poli')->references('nama_poli')->on('polis')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungans');
    }
};
