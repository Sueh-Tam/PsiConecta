<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Altera a coluna document_type para incluir 'crp' como um tipo válido
        Schema::table('users', function (Blueprint $table) {
            // Primeiro, modifica a coluna para remover a restrição enum
            $table->string('document_type')->change();
        });

        // Em seguida, adiciona a nova restrição enum com o valor 'crp' incluído
        DB::statement("ALTER TABLE users MODIFY document_type ENUM('cpf', 'cnpj', 'rg', 'crp') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverte a alteração, removendo 'crp' da lista de tipos válidos
        Schema::table('users', function (Blueprint $table) {
            // Primeiro, modifica a coluna para remover a restrição enum
            $table->string('document_type')->change();
        });

        // Em seguida, adiciona a restrição enum original
        DB::statement("ALTER TABLE users MODIFY document_type ENUM('cpf', 'cnpj', 'rg') NOT NULL");
    }
};