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
        Schema::create('preskripsi__obats', function (Blueprint $table) {
            $table->string('id_preskripsi', 20);
            $table->string('id_obat', 20);
            $table->string('dosis', 20);
            $table->integer('jumlah');
            $table->text('cara_penggunaan');

            $table->primary(['id_preskripsi', 'id_obat']); // Composite primary key
            $table->foreign('id_preskripsi')->references('id_preskripsi')->on('preskripsis');
            $table->foreign('id_obat')->references('id_obat')->on('obats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preskripsi__obats');
    }
};
