<?php

namespace Database\Seeders;

use App\Models\ClinicPatient;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClinicPatientSeeder extends Seeder
{
    /**
     * Executa os seeders da tabela pivot clinic_patient.
     * Vincula pacientes a clínicas aleatoriamente para demonstração.
     */
    public function run(): void
    { 
        // Obter todas as clínicas e pacientes
        $clinics = User::where('type', 'clinic')->get();
        $patients = User::where('type', 'patient')->get();
        
        // Para cada paciente, vincular a pelo menos uma clínica aleatória
        foreach ($patients as $patient) {
            // Selecionar aleatoriamente entre 1 e 2 clínicas para cada paciente
            $randomClinics = $clinics->random(rand(1, min(2, $clinics->count())));
            
            foreach ($randomClinics as $clinic) {
                // Verificar se o relacionamento já existe
                $exists = ClinicPatient::where('id_clinic', $clinic->id)
                    ->where('id_patient', $patient->id)
                    ->exists();
                
                if (!$exists) {
                    // Criar o relacionamento na tabela pivot
                    ClinicPatient::create([
                        'id_clinic' => $clinic->id,
                        'id_patient' => $patient->id
                    ]);
                }
            }
        }
    }
}