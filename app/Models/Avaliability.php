<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliability extends Model
{
    protected $fillable = [
        'id_psychologist', 'status', 'dt_avaliability', 'hr_avaliability'
    ];

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'id_psychologist');
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'availability_id');
    }
}
