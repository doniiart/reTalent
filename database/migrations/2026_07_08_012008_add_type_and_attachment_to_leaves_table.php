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
        Schema::table('leaves', function (Blueprint $table) {
            $table->enum('leave_type', ['tahunan', 'sakit', 'khusus'])->default('tahunan')->after('user_id');
            $table->string('attachment')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn(['leave_type', 'attachment']);
        });
    }
};
