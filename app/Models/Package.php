<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'patient_id',
        'psychologist_id',
        'total_appointments',
        'price',
        'balance',
        'payment_method',
    ];

    /**
     * Relacionamento: Obtém o paciente que comprou o pacote
     * Relação: Muitos para Um (N:1) - Cada pacote pertence a um paciente
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Relacionamento: Obtém o psicólogo associado ao pacote
     * Relação: Muitos para Um (N:1) - Cada pacote está vinculado a um psicólogo
     */
    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }
    /**
     * Relacionamento: Obtém todas as consultas deste pacote
     * Relação: Um para Muitos (1:N) - Um pacote pode ter várias consultas
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
