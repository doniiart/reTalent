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
            $table->boolean('notify_email')->default(true)->after('password');
            $table->boolean('notify_attendance')->default(true)->after('notify_email');
            $table->boolean('notify_leave')->default(true)->after('notify_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_email', 'notify_attendance', 'notify_leave']);
        });
    }
};
