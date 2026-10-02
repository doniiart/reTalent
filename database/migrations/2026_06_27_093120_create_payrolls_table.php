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
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('month_year'); // Contoh: Oktober 2023
        $table->integer('basic_salary');
        $table->integer('allowance')->default(0); // Tunjangan
        $table->integer('deduction')->default(0); // Potongan
        $table->integer('net_salary'); // Total Bersih
        $table->string('payment_method')->nullable();
        $table->enum('status', ['Diproses', 'Dibayarkan'])->default('Diproses');
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
