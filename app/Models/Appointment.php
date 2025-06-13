<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Availability;
use App\Models\Package;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $fillable = [
        'clinic_id', 'patient_id', 'psychologist_id', 'package_id',
        'availability_id', 'status', 'medical_record', 'payment_status'
    ];

    /**
     * Relacionamento: Obtém o paciente associado à consulta
     * Relação: Muitos para Um (N:1) - Cada consulta pertence a um paciente
     */
    public function patient():BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Relacionamento: Obtém o psicólogo responsável pela consulta
     * Relação: Muitos para Um (N:1) - Cada consulta pertence a um psicólogo
     */
    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id','id');
    }
    /**
     * Relacionamento: Obtém o psicólogo como relação única
     * Relação: Um para Um (1:1) - Versão alternativa do relacionamento com psicólogo
     */
    public function psychologistOne()
    {
        return $this->hasOne(User::class, 'id','psychologist_id');
    }

    /**
     * Relacionamento: Obtém a clínica onde a consulta é realizada
     * Relação: Muitos para Um (N:1) - Cada consulta pertence a uma clínica
     */
    public function clinic()
    {
        return $this->belongsTo(User::class, 'clinic_id');
    }

    /**
     * Relacionamento: Obtém o pacote de consultas associado
     * Relação: Muitos para Um (N:1) - Cada consulta pertence a um pacote
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Relacionamento: Obtém a disponibilidade associada à consulta
     * Relação: Um para Um (1:1) - Cada consulta tem uma disponibilidade
     */
    public function availability()
    {
        return $this->hasOne(Availability::class, 'id_appointments');
    }
}
