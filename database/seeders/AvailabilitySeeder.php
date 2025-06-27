<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Appointment;
use App\Models\User; 
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    /**
     * Executa o seeder para criar disponibilidades.
     * Gera horários disponíveis para dias úteis (segunda a sexta) no período especificado.
     * Não cria disponibilidades para sábados e domingos.
     */
    public function run(): void
    {
        // Limpar registros existentes para evitar duplicações
        Availability::truncate();
        
        // Definir período - 4 semanas a partir da data atual
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->addWeeks(4)->endOfWeek();

        // Horários disponíveis conforme especificação
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
        $payment_methods= [ 'pix','cash','health_plan'];
        // Status possíveis para disponibilidades
        // Não precisamos mais dessa variável, pois o status será determinado pelo número aleatório

        // Obter todos os psicólogos
        $psychologists = User::where('type', 'psychologist')->get();
        
        if ($psychologists->isEmpty()) {
            $this->command->info('Nenhum psicólogo encontrado. Pulando criação de disponibilidades.');
            return;
        }

        $this->command->info('Criando disponibilidades para ' . $psychologists->count() . ' psicólogos...');
        
        // Contador para feedback
        $count = 0;

        // Percorrer cada dia no período
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            // Verificar se é dia útil (1 = Segunda, 5 = Sexta)
            if ($currentDate->dayOfWeek >= 1 && $currentDate->dayOfWeek <= 5) {
                // Para cada psicólogo
                foreach ($psychologists as $psychologist) {
                    // Selecionar aleatoriamente alguns horários disponíveis para este psicólogo e dia
                    $numHoursToSelect = rand(1, count($availableHours)); // Número aleatório de horários
                    $selectedHours = array_rand(array_flip($availableHours), $numHoursToSelect);
                    
                    // Para cada horário selecionado aleatoriamente
                    foreach ($selectedHours as $hour) {
                        // Gerar número aleatório entre 1 e 2
                        $randomNumber = rand(1, 2);
                        
                        // Extrair a hora inicial do bloco de horário (ex: '08:00-09:00' -> '08:00')
                        $startHour = explode('-', $hour)[0];
                        
                        // Criar disponibilidade com status baseado no número aleatório
                        $availability = Availability::create([
                            'id_psychologist' => $psychologist->id,
                            'dt_Availability' => $currentDate->format('Y-m-d'),
                            'hr_Availability' => $hour,
                            'status' => $randomNumber == 1 ? 'unvailable' : 'available',
                        ]);
                        
                        // Se o número aleatório for 1, criar um agendamento associado
                        if ($randomNumber == 1) {
                            // Buscar um paciente aleatório da clínica do psicólogo
                            $clinic = User::find($psychologist->id_clinic);
                            $patients = $clinic->patients;
                            
                            if ($patients->isNotEmpty()) {
                                $patient = $patients->random();
                                
                                // Buscar um pacote existente para este paciente e psicólogo
                                $package = \App\Models\Package::where('patient_id', $patient->id)
                                    ->where('psychologist_id', $psychologist->id)
                                    ->where('balance', '>', 0)
                                    ->first();
                                
                                // Se não encontrar um pacote com saldo, criar um novo
                                if (!$package) {
                                    $number_rand_consults = rand(1,6);
                                    $number_rand_balance = rand(0,$number_rand_consults);
                                    $package = \App\Models\Package::create([
                                        'patient_id' => $patient->id,
                                        'psychologist_id' => $psychologist->id,
                                        'total_appointments' => $number_rand_consults,
                                        'price' => $psychologist->appointment_price * $number_rand_consults,
                                        'balance' => $number_rand_balance,
                                        'payment_method' => $payment_methods[array_rand($payment_methods,1)],
                                    ]);
                                }
                                for($i = 0; $i < ($number_rand_consults - $number_rand_balance); $i++){
                                    
                                    $rand_status = ['scheduled','completed','cancelled','canceled_late','canceled_early'];
                                    $selected_status = $rand_status[array_rand($rand_status,1)];
                                    
                                    if($selected_status != 'scheduled' && $selected_status != 'completed'){
                                        $availability->update([
                                            'id_appointments' => null,
                                        ]);
                                    }

                                    Appointment::create([
                                        'clinic_id' => $clinic->id,
                                        'patient_id' => $patient->id,
                                        'psychologist_id' => $psychologist->id,
                                        'package_id' => $package->id,
                                        'status' => $selected_status,
                                        'payment_status' => 'paid',
                                        'medical_record' => $selected_status == 'completed' ? fake()->paragraph() : null,
                                        'dt_Availability' => $currentDate->format('Y-m-d'),
                                        'hr_Availability' => $startHour,
                                    ]);

                                    if($rand_status == 'canceled_early'){
                                        $i--;
                                    }
                                }
                            }
                        }
                        
                        $count++;
                        }
                    }
                }
            }
            $currentDate->addDay(1);
        }
        
        //$this->command->info("$count disponibilidades criadas com sucesso!");
}