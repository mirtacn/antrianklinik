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
        Schema::create('pasien_bpjs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_bpjs')->unique();
            $table->string('nama');
            $table->text('alamat');
            $table->date('tanggal_lahir');
            $table->string('nik')->unique();
            $table->string('faskes_tingkat_1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien_bpjs');
    }
};
