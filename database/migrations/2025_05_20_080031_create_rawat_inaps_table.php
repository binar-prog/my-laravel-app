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
        Schema::create('rawat_inaps', function (Blueprint $table) {
            $table->string('id_rawat', 20)->primary();
            $table->string('id_pasien', 20);
            $table->string('id_kamar',20);
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();

            $table->foreign('id_pasien' )->references('id_pasien')->on('pasiens')->onDelete('cascade');
            $table->foreign('id_kamar', )->references('id_kamar')->on('kamars')->onDelete('cascade');

            $table->unique(['id_kamar', 'tanggal_masuk']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rawat_inaps');
    }
};
