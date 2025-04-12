<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'id_clinic',
        'name',
        'email',
        'password',
        'document_type',
        'document_number',
        'appointment_price',
        'type',
        'situation',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'document_type' => 'string',
        'type' => 'string',
        'situation' => 'string',
        'status' => 'string',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relação com a própria clínica (para psicólogos, pacientes e atendentes)
     */
    public function clinic()
    {
        return $this->belongsTo(User::class, 'id_clinic');
    }

    /**
     * Membros da clínica (se este usuário for uma clínica)
     */
    public function members()
    {
        return $this->hasMany(User::class, 'id_clinic');
    }

    /**
     * Pacotes onde o usuário é paciente
     */
    public function patientPackages()
    {
        return $this->hasMany(Package::class, 'patient_id');
    }

    /**
     * Pacotes onde o usuário é psicólogo
     */
    public function psychologistPackages()
    {
        return $this->hasMany(Package::class, 'psychologist_id');
    }

    /**
     * Consultas onde o usuário é paciente
     */
    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Consultas onde o usuário é psicólogo
     */
    public function psychologistAppointments()
    {
        return $this->hasMany(Appointment::class, 'psychologist_id');
    }

    /**
     * Consultas associadas à clínica (se for uma clínica)
     */
    public function clinicAppointments()
    {
        return $this->hasMany(Appointment::class, 'clinic_id');
    }

    /**
     * Disponibilidades do psicólogo
     */
    public function availabilities()
    {
        return $this->hasMany(Avaliability::class, 'id_psychologist');
    }

    /**
     * Escopo para usuários do tipo clínica
     */
    public function scopeClinics($query)
    {
        return $query->where('type', 'clinic');
    }

    /**
     * Escopo para usuários do tipo psicólogo
     */
    public function scopePsychologists($query)
    {
        return $query->where('type', 'psychologist');
    }

    /**
     * Escopo para usuários do tipo paciente
     */
    public function scopePatients($query)
    {
        return $query->where('type', 'patient');
    }

    /**
     * Escopo para usuários ativos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Verifica se o usuário é administrador
     */
    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    /**
     * Verifica se o usuário é uma clínica
     */
    public function isClinic()
    {
        return $this->type === 'clinic';
    }

    /**
     * Verifica se o usuário é psicólogo
     */
    public function isPsychologist()
    {
        return $this->type === 'psychologist';
    }

    /**
     * Verifica se o usuário é paciente
     */
    public function isPatient()
    {
        return $this->type === 'patient';
    }

    /**
     * Verifica se o usuário é atendente
     */
    public function isAttendant()
    {
        return $this->type === 'attendant';
    }
}
