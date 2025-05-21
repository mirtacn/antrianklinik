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
        Schema::create('antrian', function (Blueprint $table) {
            $table->id();
            $table->string('no_antrian');
            $table->foreignId('id_layanan')->constrained('layanan')->onDelete('cascade');
            $table->date('tanggal_antrian');
            $table->time('waktu_pilih');
            $table->time('waktu_estimasi');
            $table->string('status_antrian');
            $table->string('email');
            $table->string('no_telepon');
            $table->foreignId('id_jadwal')->constrained('jadwaldokter')->onDelete('cascade');
            $table->foreignId('id_pasienumum')->nullable()->constrained('pasien_umum')->onDelete('cascade');
            $table->foreignId('id_pasienbpjs')->nullable()->constrained('pasien_bpjs')->onDelete('cascade');
            $table->foreignId('id_poli')->constrained('poli')->onDelete('cascade');
            $table->integer('panggilan_count')->default(0);
            $table->timestamp('last_panggilan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian');
    }
};
