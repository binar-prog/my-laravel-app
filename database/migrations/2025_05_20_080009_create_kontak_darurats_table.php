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
        Schema::create('kontak_darurats', function (Blueprint $table) {
            $table->string('id_kontak', 20)->primary();
            $table->string('nama_kontak', 100);
            $table->string('no_darurat', 15);
            $table->string('hubungan', 20);
            $table->string('id_pasien', 20); // foreign key ke pasien

            // Foreign key ke tabel pasien
            $table->foreign('id_pasien')->references('id_pasien')->on('pasiens')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontak_darurats');
    }
};
