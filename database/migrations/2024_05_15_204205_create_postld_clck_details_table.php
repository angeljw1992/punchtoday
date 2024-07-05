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
        Schema::create('postld_clck_details', function (Blueprint $table) {
            $table->id();
            $table->integer('ROWID');
            $table->string('BusinessDate');
            $table->integer('EmployeeID');
            $table->string('Punch_Type')->nullable();
            $table->string('Punch_TimeStamp')->nullable();
            $table->string('Modified_TimeStamp')->nullable();
            $table->string('AutorizadoPor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postld_clck_details');
    }
};
