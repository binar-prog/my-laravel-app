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
        Schema::create('dokters', function (Blueprint $table) {
            $table->string('id_dokter', 20)->primary();
            $table->string('no_kualifikasi', 15);
            $table->string('nama_dokter', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nama_poli', 50);
            $table->string('spesialisasi', 50);
            $table->string('no_telepon', 15);
            $table->date('tanggal_masuk');
            $table->enum('hari_praktik', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->foreign('nama_poli')->references('nama_poli')->on('polis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokters');
    }
};
