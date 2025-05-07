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

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
