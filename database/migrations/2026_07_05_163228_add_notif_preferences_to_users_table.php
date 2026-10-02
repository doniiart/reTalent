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
        Schema::table('users', function (Blueprint $table) {
        // boolean() menyimpan data 1 (nyala) atau 0 (mati). default(true) artinya otomatis nyala saat akun dibuat.
        $table->boolean('notif_email')->default(true);
        $table->boolean('notif_kehadiran')->default(true);
        $table->boolean('notif_cuti')->default(true);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['notif_email', 'notif_kehadiran', 'notif_cuti']);
    });
    }
};
