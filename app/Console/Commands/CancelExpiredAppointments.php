<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\Availability;
use Carbon\Carbon;

class CancelExpiredAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancela appointments com status scheduled que passaram de um dia';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificação de appointments vencidos...');

        // Data limite: ontem (mais de 1 dia atrás)
        $cutoffDate = Carbon::now()->subDay()->format('Y-m-d');

        // Buscar appointments com status 'scheduled' que têm availability com data anterior a ontem
        $expiredAppointments = Appointment::where('status', 'scheduled')
            ->whereHas('availability', function ($query) use ($cutoffDate) {
                $query->where('dt_Availability', '<', $cutoffDate);
            })
            ->with('availability')
            ->get();

        if ($expiredAppointments->isEmpty()) {
            $this->info('Nenhum appointment vencido encontrado.');
            return 0;
        }

        $cancelledCount = 0;

        foreach ($expiredAppointments as $appointment) {
            // Atualizar o status do appointment para 'cancelled'
            $appointment->update(['status' => 'cancelled']);
            
            // Atualizar a availability para 'available' para liberar o horário
            if ($appointment->availability) {
                $appointment->availability->update(['status' => 'available']);
            }

            $cancelledCount++;
        }

        $this->info("Total de {$cancelledCount} appointments cancelados com sucesso.");
        
        return 0;
    }
}
