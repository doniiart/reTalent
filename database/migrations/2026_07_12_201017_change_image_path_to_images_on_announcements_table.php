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
        Schema::table('announcements', function (Blueprint $table) {
            if (Schema::hasColumn('announcements', 'image_path')) {
                $table->dropColumn('image_path');
            }
            $table->json('images')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('images');
            $table->string('image_path')->nullable()->after('content');
        });
    }
};
