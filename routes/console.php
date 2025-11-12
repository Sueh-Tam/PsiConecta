<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendamento para cancelar appointments vencidos a cada 10 minutos
Schedule::command('appointments:cancel-expired')->everyTenMinutes();
