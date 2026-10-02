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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('lat_in', 10, 8)->nullable();
            $table->decimal('long_in', 11, 8)->nullable();
            $table->decimal('lat_out', 10, 8)->nullable();
            $table->decimal('long_out', 11, 8)->nullable();
            $table->string('photo_in')->nullable();
            $table->string('photo_out')->nullable();
            $table->string('location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['lat_in', 'long_in', 'lat_out', 'long_out', 'photo_in', 'photo_out', 'location']);
        });
    }
};
