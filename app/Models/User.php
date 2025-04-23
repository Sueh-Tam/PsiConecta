<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPSTORM_META\type;

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
    public function formatDocumentCnpj($documentNumber){

        // Remove todos os caracteres não numéricos
        $cnpj = preg_replace('/[^0-9]/', '', $documentNumber);

        // Verifica se tem 14 dígitos
        if (strlen($cnpj) !== 14) {
            return $cnpj; // Retorna sem formatação se não for CNPJ válido
        }

        // Aplica a formatação do CNPJ: 00.000.000/0000-00
        return substr($cnpj, 0, 2) . '.' .
            substr($cnpj, 2, 3) . '.' .
            substr($cnpj, 5, 3) . '/' .
            substr($cnpj, 8, 4) . '-' .
            substr($cnpj, 12, 2);
    }
    public function formartDocumentCPF($documentNumber){
        // Remove todos os caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $documentNumber);

        // Verifica se tem 11 dígitos
        if (strlen($cpf) !== 11) {
            return $cpf; // Retorna sem formatação se não for CPF válido
        }

        // Aplica a formatação do CPF: 000.000.000-00
        return substr($cpf, 0, 3) . '.' .
            substr($cpf, 3, 3) . '.' .
            substr($cpf, 6, 3) . '-' .
            substr($cpf, 9, 2);

    }
    public function formatDocumentCRP($documentNumber){
         // Remove todos os caracteres não numéricos
        $crp = preg_replace('/[^0-9]/', '', $documentNumber);

        // Verifica se tem 7 dígitos (formato completo)
        if (strlen($crp) !== 7) {
            return $crp;
        }

        // Aplica a formatação do CRP: 00.000-00
        return substr($crp, 0, 2) . '.' .
            substr($crp, 2, 3) . '-' .
            substr($crp, 5, 2);
    }
    /**
     * Membros da clínica (se este usuário for uma clínica)
     */
    public function members()
    {
        return $this->hasMany(User::class, 'id_clinic');
    }
    public function psychologists()
    {
        return $this->hasMany(User::class, 'id_clinic')
            ->where('type', 'psychologist');
    }
    public function attendants()
    {
        return $this->hasMany(User::class, 'id_clinic')
            ->where('type', 'attendant');
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
