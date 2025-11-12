<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Availability extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id_psychologist', 'status', 'dt_Availability', 'hr_Availability'
    ];

    /**
     * Relacionamento: Obtém o psicólogo associado à disponibilidade
     * Relação: Muitos para Um (N:1) - Cada disponibilidade pertence a um psicólogo
     */
    public function psychologist()
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Relacionamento: Obtém a consulta associada à disponibilidade
     * Relação: Um para Um (1:1) - Cada disponibilidade pode ter uma consulta
     */
    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'availability_id');
    }

    /**
     * Relacionamento: Alias para o relacionamento appointment()
     * Relação: Um para Um (1:1) - Método alternativo para acessar a consulta
     */
    public function appointments()
    {
        return $this->appointment();
    }
    public const TIME_BLOCKS = [
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
}
