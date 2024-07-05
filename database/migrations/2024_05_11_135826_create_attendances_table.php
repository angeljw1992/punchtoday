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
            $table->integer('employee_id');
            $table->enum('status',['present','on_leave'])->default('on_leave');
            $table->string('date')->nullable();
            $table->string('entrance')->nullable();
            $table->string('lunch_departure')->nullable();
            $table->string('lunch_entry')->nullable();
            $table->string('exit')->nullable();
            $table->string('hours_worked')->nullable();
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
