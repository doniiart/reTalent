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
        Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke tabel users
        $table->date('tanggal');
        $table->time('waktu_masuk')->nullable();
        $table->time('waktu_keluar')->nullable();
        $table->string('status')->default('Hadir'); // Contoh: Hadir, Terlambat, Cuti, Izin
        $table->string('lokasi_masuk')->nullable(); // Untuk menyimpan titik koordinat/nama lokasi
        $table->string('lokasi_keluar')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
