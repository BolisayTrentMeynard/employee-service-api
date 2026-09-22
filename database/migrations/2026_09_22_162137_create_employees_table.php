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
        $table->foreignId('department_id')->constrained()->onDelete('cascade');
        $table->string('employee_number', 30)->unique();
        $table->string('first_name', 80);
        $table->string('last_name', 80);
        $table->string('email')->unique();
        $table->string('position', 100);
        $table->enum('employment_status', ['Active', 'Inactive']);
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
