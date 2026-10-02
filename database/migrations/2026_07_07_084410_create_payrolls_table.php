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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('month');
            $table->integer('year');
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('premium', 15, 2)->default(0);
            $table->decimal('total_allowance', 15, 2)->default(0);
            $table->decimal('total_reward', 15, 2)->default(0);
            $table->decimal('jht_deduction', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);
            $table->enum('status', ['draft', 'paid'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
