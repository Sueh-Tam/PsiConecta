<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');
        
        // Obter todos os pacotes
        $packages = Package::all();
        
        // Para cada pacote, criar consultas baseadas no total_appointments
        foreach ($packages as $package) {
            $patient = User::find($package->patient_id);
            $psychologist = User::find($package->psychologist_id);
            $clinic = User::find($psychologist->id_clinic);
            
            // Criar consultas para cada pacote
            for ($i = 0; $i < $package->total_appointments; $i++) {
                // Gerar data e hora aleatórias para a consulta (entre hoje e 3 meses no futuro)
                $appointmentDate = $faker->dateTimeBetween('now', '+3 months');
                $appointmentHour = sprintf("%02d:%02d", rand(8, 18), rand(0, 1) * 30); // Horas entre 8:00 e 18:30
                
                // Definir status aleatório para a consulta
                if($package->balance < $i){
                    $status = $faker->randomElement(['completed', 'cancelled', 'canceled_late', 'canceled_early']);
                }else{
                    $status = 'scheduled';
                }
                
                
                // Se a consulta foi completada, definir um registro médico
                $medicalRecord = null;
                if ($status === 'completed') {
                    $medicalRecord = $faker->paragraph(3);
                }
                
                // Criar a consulta
                Appointment::create([
                    'clinic_id' => $clinic->id,
                    'patient_id' => $patient->id,
                    'psychologist_id' => $psychologist->id,
                    'package_id' => $package->id,
                    'status' => $status,
                    'medical_record' => $medicalRecord,
                    'payment_status' => 'paid', // Todas as consultas de pacotes são pagas
                    'dt_avaliability' => Carbon::instance($appointmentDate)->format('Y-m-d'),
                    'hr_avaliability' => $appointmentHour,
                ]);
            }
        }
    }
}