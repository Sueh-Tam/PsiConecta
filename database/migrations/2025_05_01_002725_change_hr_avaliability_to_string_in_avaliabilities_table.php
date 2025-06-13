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
            $table->string('hr_Availability_temp')->nullable()->after('hr_Availability');
        });

        // 2. Converter e copiar os dados
        DB::table('availabilities')->update([
            'hr_Availability_temp' => DB::raw("DATE_FORMAT(hr_Availability, '%H:%i:%s')")
        ]);

        // 3. Remover coluna original
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn('hr_Availability');
        });

        // 4. Renomear coluna temporária
        Schema::table('availabilities', function (Blueprint $table) {
            $table->renameColumn('hr_Availability_temp', 'hr_Availability');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('availabilities', 'hr_Availability_temp')) {
            Schema::table('availabilities', function (Blueprint $table) {
                $table->datetime('hr_Availability_temp')->nullable()->after('hr_Availability');
            });
        }

        // 2. Converter e copiar os dados de volta
        DB::table('availabilities')->update([
            'hr_Availability_temp' => DB::raw("CONCAT('2023-01-01 ', SUBSTRING_INDEX(hr_Availability, '-', 1), ':00')")
        ]);

        // 3. Remover coluna string
        Schema::table('availabilities', function (Blueprint $table) {
            $table->dropColumn('hr_Availability');
        });

        // 4. Renomear coluna temporária
        Schema::table('availabilities', function (Blueprint $table) {
            $table->renameColumn('hr_Availability_temp', 'hr_Availability');
        });
    }
};
