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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('position_id')->constrained('positions')->onDelete('restrict');
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
            $table->string('photo')->nullable();
            $table->string('phone')->nullable();
            $table->string('work_location')->nullable();
            $table->enum('employment_status', ['tetap', 'kontrak', 'intership'])->default('tetap');
            $table->date('join_date');
            
            // Salary Structure
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('premium', 15, 2)->default(0);
            $table->decimal('tech_allowance', 15, 2)->default(0);
            $table->decimal('laptop_allowance', 15, 2)->default(0);
            $table->decimal('family_allowance', 15, 2)->default(0);
            $table->decimal('discipline_reward', 15, 2)->default(0);
            $table->decimal('prayer_reward', 15, 2)->default(0);
            $table->decimal('jht', 15, 2)->default(0);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
