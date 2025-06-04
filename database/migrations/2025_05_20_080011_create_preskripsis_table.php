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
        Schema::create('preskripsis', function (Blueprint $table) {
            $table->string('id_preskripsi', 20)->primary();
            $table->string('id_pasien', 20);
            $table->string('id_kunjungan', 20)->unique();
            $table->date('tanggal');
            $table->string('dosis', 20);
            $table->integer('jumlah');
            $table->text('cara_penggunaan');
            $table->foreign('id_kunjungan')->references('id_kunjungan')->on('kunjungans');
            $table->foreign('id_pasien')->references('id_pasien')->on('pasiens');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preskripsis');
    }
};
