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
        Schema::create('clinic_patient', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_clinic');
            $table->unsignedBigInteger('id_patient');
            $table->timestamps();
            
            $table->foreign('id_clinic')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_patient')->references('id')->on('users')->onDelete('cascade');
            
            // Adiciona índice único para evitar duplicações de relacionamentos
            $table->unique(['id_clinic', 'id_patient']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_patient');
    }
};
