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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_psychologist');
            $table->unsignedBigInteger('id_appointments')->nullable();
            $table->enum('status', ['available', 'unvailable']);
            $table->dateTime('dt_avaliability');
            $table->dateTime('hr_avaliability');

            $table->foreign('id_psychologist')->references('id')->on('users');
            $table->foreign('id_appointments')->references('id')->on('appointments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
