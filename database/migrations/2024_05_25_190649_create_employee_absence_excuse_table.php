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
        Schema::create('employee_absence_excuse', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->longText('excuse');
            $table->string('date');
            $table->string('authorized_person');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_absence_excuse');
    }
};
