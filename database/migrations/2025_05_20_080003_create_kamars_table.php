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
        Schema::create('kamars', function (Blueprint $table) {
            $table->string('id_kamar', 20)->primary();
            $table->string('gedung', 20);
            $table->string('lantai');
            $table->string('tipe',20);
            $table->string('no_kamar', 5);
            $table->integer('no_bed');
            $table->enum('status', ['terisi', 'kosong'])->default('kosong');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
