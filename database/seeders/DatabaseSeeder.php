<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        // Criar usuário admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@psiconecta.com',
            'password' => Hash::make('123456'),
            'document_type' => 'cpf',
            'document_number' => $faker->numberBetween(10000000000, 19999999999),
            'type' => 'admin',
            'situation' => 'valid',
            'status' => 'active',
        ]);

        // Criar 3 clínicas
        $clinics = [];
        for ($i = 1; $i <= 3; $i++) {
            $clinic = User::create([
                'name' => "Clínica $i",
                'email' => "clinica$i@psiconecta.com",
                'password' => Hash::make('123456'),
                'document_type' => 'cnpj',
                'document_number' => $faker->numberBetween(50000000000000, 59999999999999),
                'type' => 'clinic',
                'situation' => 'valid',
                'status' => 'active',
            ]);
            $clinics[] = $clinic;
        }

        // Criar 2 psicólogos para cada clínica
        foreach ($clinics as $clinic) {
            for ($i = 1; $i <= 2; $i++) {
                $name = $faker->name;
                User::create([
                    'id_clinic' => $clinic->id,
                    'name' => "Dr. {$name}",
                    'email' => "psicologo" . Str::random(4) . "@psiconecta.com",
                    'password' => Hash::make('123456'),
                    'document_type' => 'crp',
                    'document_number' => $faker->numberBetween(1000000, 99999999),
                    'appointment_price' => rand(100, 300),
                    'type' => 'psychologist',
                    'situation' => 'valid',
                    'status' => 'active',
                ]);
            }

            // Criar 1 atendente para cada clínica
            User::create([
                'id_clinic' => $clinic->id,
                'name' => "At. {$faker->name}",
                'email' => "atendente" . Str::random(4) . "@psiconecta.com",
                'password' => Hash::make('123456'),
                'document_type' => 'cpf',
                'document_number' => $faker->numberBetween(30000000000, 39999999999),
                'type' => 'attendant',
                'situation' => 'valid',
                'status' => 'active',
            ]);
        }

        // Criar 5 pacientes
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => "paciente" . Str::random(4) . "@example.com",
                'password' => Hash::make('123456'),
                'document_type' => 'cpf',
                'document_number' => $faker->numberBetween(40000000000, 49999999999),
                'type' => 'patient',
                'situation' => 'valid',
                'status' => 'active',
            ]);
        }
        
        // Executar o seeder para vincular pacientes a clínicas
        $this->call(ClinicPatientSeeder::class);

        // Executar o seeder para criar disponibilidades
        $this->call(AvaliabilitySeeder::class);
    }
}
