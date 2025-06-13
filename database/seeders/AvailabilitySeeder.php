<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\User; 
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Executa o seeder para criar disponibilidades.
     * Gera horários disponíveis para dias úteis no período especificado.
     */
    public function run(): void
    {
        // Definir período
        $startDate = Carbon::create(2025, 5, 12);
        $endDate = Carbon::create(2025, 6, 12);

        // Horários disponíveis
        $availableHours = [
            '08:00-09:00',
            '09:00-10:00',
            '10:00-11:00',
            '11:00-12:00',
            '13:00-14:00',
            '14:00-15:00',
            '15:00-16:00',
            '16:00-17:00',
            '17:00-18:00',
        ];

        // Obter todos os psicólogos
        $psychologists = User::where('type', 'psychologist')->get();

        // Percorrer cada dia no período
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            // Verificar se é dia útil (1 = Segunda, 5 = Sexta)
            if ($currentDate->dayOfWeek >= 1 && $currentDate->dayOfWeek <= 5) {
                // Para cada psicólogo
                foreach ($psychologists as $psychologist) {
                    // Para cada horário disponível
                    foreach ($availableHours as $hour) {
                        Availability::create([
                            'id_psychologist' => $psychologist->id,
                            'dt_Availability' => $currentDate->format('Y-m-d'),
                            'hr_Availability' => $hour,
                            'status' => 'available'
                        ]);
                    }
                }
            }
            $currentDate->addDay();
        }
    }
}