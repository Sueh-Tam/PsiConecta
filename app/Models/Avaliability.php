<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Avaliability extends Model
{
    use SoftDeletes;
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
