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
        Schema::create('stafs', function (Blueprint $table) {
            $table->string('id_staf', 20)->primary();
            $table->string('nama_staf', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('nama_poli', 50);
            $table->string('jabatan', 50);
            $table->string('unit_kerja', 15);
            $table->string('no_telepon', 15);
            $table->date('tanggal_masuk');

            $table->foreign('nama_poli')->references('nama_poli')->on('polis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stafs');
    }
};
