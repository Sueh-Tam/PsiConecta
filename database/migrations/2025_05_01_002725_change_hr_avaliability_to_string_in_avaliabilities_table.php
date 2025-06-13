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
        Schema::table('availabilities', function (Blueprint $table) {
            $table->string('hr_avaliability_temp')->nullable()->after('hr_avaliability');
        });

        // 2. Converter e copiar os dados
        DB::table('availabilities')->update([
            'hr_avaliability_temp' => DB::raw("DATE_FORMAT(hr_avaliability, '%H:%i:%s')")
        ]);

        // 3. Remover coluna original
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn('hr_avaliability');
        });

        // 4. Renomear coluna temporária
        Schema::table('availabilities', function (Blueprint $table) {
            $table->renameColumn('hr_avaliability_temp', 'hr_avaliability');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('availabilities', 'hr_avaliability_temp')) {
            Schema::table('availabilities', function (Blueprint $table) {
                $table->datetime('hr_avaliability_temp')->nullable()->after('hr_avaliability');
            });
        }

        // 2. Converter e copiar os dados de volta
        DB::table('availabilities')->update([
            'hr_avaliability_temp' => DB::raw("CONCAT('2023-01-01 ', SUBSTRING_INDEX(hr_avaliability, '-', 1), ':00')")
        ]);

        // 3. Remover coluna string
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn('hr_avaliability');
        });

        // 4. Renomear coluna temporária
        Schema::table('availabilities', function (Blueprint $table) {
            $table->renameColumn('hr_avaliability_temp', 'hr_avaliability');
        });
    }
};
