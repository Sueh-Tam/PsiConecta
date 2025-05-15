<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Avaliability;
use App\Models\Package;
class Appointment extends Model
{
    protected $fillable = [
        'clinic_id', 'patient_id', 'psychologist_id', 'package_id',
        'availability_id', 'status', 'medical_record', 'payment_status'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id','id');
    }
    public function psychologistOne()
    {
        return $this->hasOne(User::class, 'id','psychologist_id');
    }

    public function clinic()
    {
        return $this->belongsTo(User::class, 'clinic_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function availability()
    {
        return $this->hasOne(Avaliability::class, 'id_appointments');
    }
}
