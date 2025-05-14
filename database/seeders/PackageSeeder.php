<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');
        
        // Obter todos os pacientes
        $patients = User::where('type', 'patient')->get();
        
        // Obter todos os psicólogos
        $psychologists = User::where('type', 'psychologist')->get();
        
        // Para cada paciente, criar de 1 a 3 pacotes
        foreach ($patients as $patient) {
            $numPackages = rand(1, 3);
            
            for ($i = 0; $i < $numPackages; $i++) {
                // Selecionar um psicólogo aleatório
                $psychologist = $psychologists->random();
                
                // Definir número total de consultas (entre 1 e 10)
                $totalAppointments = rand(1, 10);
                $totalBalance = rand(1, $totalAppointments);
                
                // Calcular o preço total baseado no preço de consulta do psicólogo
                $totalPrice = $psychologist->appointment_price * $totalAppointments;
                
                // Criar o pacote com balance igual ao total_appointments
                Package::create([
                    'patient_id' => $patient->id,
                    'psychologist_id' => $psychologist->id,
                    'total_appointments' => $totalAppointments,
                    'price' => $totalPrice,
                    'balance' => $totalBalance, // balance igual ao total_appointments
                    'payment_method' => $faker->randomElement(['pix', 'cash']),
                ]);
            }
        }
    }
}