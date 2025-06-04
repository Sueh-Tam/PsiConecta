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
        Schema::table('avaliabilities', function (Blueprint $table) {
            $table->string('hr_avaliability_temp')->nullable()->after('hr_avaliability');
        });

        // 2. Converter e copiar os dados
        DB::table('avaliabilities')->update([
            'hr_avaliability_temp' => DB::raw("DATE_FORMAT(hr_avaliability, '%H:%i:%s')")
        ]);

        // 3. Remover coluna original
        Schema::table('avaliabilities', function (Blueprint $table) {
            $table->dropColumn('hr_avaliability');
        });

        // 4. Renomear coluna temporária
        Schema::table('avaliabilities', function (Blueprint $table) {
            $table->renameColumn('hr_avaliability_temp', 'hr_avaliability');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('avaliabilities', 'hr_avaliability_temp')) {
            Schema::table('avaliabilities', function (Blueprint $table) {
                $table->datetime('hr_avaliability_temp')->nullable()->after('hr_avaliability');
            });
        }

        // 2. Converter e copiar os dados de volta
        DB::table('avaliabilities')->update([
            'hr_avaliability_temp' => DB::raw("CONCAT('2023-01-01 ', SUBSTRING_INDEX(hr_avaliability, '-', 1), ':00')")
        ]);

        // 3. Remover coluna string
        Schema::table('avaliabilities', function (Blueprint $table) {
            $table->dropColumn('hr_avaliability');
        });

        // 4. Renomear coluna temporária
        Schema::table('avaliabilities', function (Blueprint $table) {
            $table->renameColumn('hr_avaliability_temp', 'hr_avaliability');
        });
    }
};
