<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClinicController extends Controller
{
    public function dashboard(Request $request)
    {
        if(Auth::user()->isClinic()){
            $clinicId = Auth::user()->id;
        }else{
            $clinicId = Auth::user()->id_clinic;
        }
        
        $clinic = User::find($clinicId);
        // Buscar pacientes da clínica usando a relação many-to-many
        $patients = $clinic->patients()
            ->with(['patientPackages' => function($query) {
                $query->latest();
            }])
            ->get()
            ->map(function($patient) {
                $activePackage = $patient->activePackage();
                $patient->appointments_left = $activePackage 
                    ? $activePackage->total_appointments - $activePackage->balance
                    : 0; 
                return $patient;
            });
        // Iniciar a query de agendamentos
        $query = \App\Models\Appointment::with(['psychologist', 'patient'])
            ->whereHas('psychologist', function($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            });

        // Aplicar filtro de paciente se fornecido
        if (request()->has('patient') && request('patient')) {
            $query->where('patient_id', request('patient'));
        }

        // Aplicar filtro de psicólogo se fornecido
        if (request()->has('psychologist') && request('psychologist')) {
            $query->where('psychologist_id', request('psychologist'));
        }

        // Aplicar filtro de data se fornecido
        if (request()->has('date') && request('date')) {
            $query->whereDate('dt_avaliability', request('date'));
        }

        // Aplicar filtro de status se fornecido
        if (request()->has('status') && request('status')) {
            $query->where('status', request('status'));
        }

        // Executar a query com ordenação e paginação
        $appointments = $query
            ->orderBy('dt_avaliability', 'asc')
            ->orderBy('hr_avaliability', 'asc')
            ->paginate(10)
            ->through(function($appointment) {
                $psychologist = $appointment->psychologist;
                $patient = $appointment->patient;
                
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->dt_avaliability,
                    'start_time' => $appointment->hr_avaliability,
                    'psychologist' => [
                        'name' => $psychologist->name,
                        'initials' => strtoupper(substr($psychologist->name, 0, 2))
                    ],
                    'patient' => [
                        'name' => $patient->name,
                        'initials' => strtoupper(substr($patient->name, 0, 2))
                    ],
                    'status' => $appointment->status,
                    'can_be_completed' => $appointment->status === 'scheduled',
                    'can_be_cancelled' => $appointment->status === 'scheduled',
                    'can_be_cancelled_early' => $appointment->status === 'scheduled'
                ];
            });
        // Estatísticas para os cards
        $stats = [
            'next_appointment' => $appointments->where('status', 'scheduled')
                ->sortBy('date')
                ->first(),
            'completed_appointments' => $appointments->where('status', 'completed')
                ->where('date', '>=', now()->subMonth())
                ->count(),
            'pending_appointments' => $appointments->where('status', 'scheduled')->count()
        ];
        $psychologists = $clinic->psychologists()->get();
        $patients = $clinic->patients()->get();
       
        return view('Dashboard.clinic.index', compact('appointments', 'stats', 'patients','psychologists','patients'));
    }

    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'document_number' => 'required|unique:users,document_number',
            ]);

            $paciente = new User();
            $paciente->name = $request->name;
            $paciente->email = $request->email;
            $paciente->password = bcrypt($request->password);
            $paciente->document_type = 'cnpj';
            $paciente->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $paciente->type = 'clinic';
            $paciente->status = 'active';
            $paciente->situation = 'pending';
            $paciente->save();

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Solicitação de conta de clínica enviada com sucesso! Aguarde a validação do administrador.')
                ->with('success_redirect', route('home'));

        } catch (\Throwable $th) {
            return redirect()->back()
            ->withErrors($th->getMessage())
            ->withInput();
        }
    }


    public function clinics()
    {
        $clinics = User::where('type', 'clinic')->get();
        return view('Dashboard.admin.index', ['clinics' => $clinics]);
    }

    public function updateClinic(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
                'situation' => 'required|in:valid,invalid,pending', // Corrigido para match com o form
                'email' => 'required|email',
                'name' => 'required',
                'password' => 'nullable|min:6', // Adicionado nullable
                'status' => 'required|in:active,inactive', // Adicionado required
                'document_number' => [
                    'required',
                    Rule::unique('users')->ignore($request->id)
                ],
            ]);


            $clinic = User::findOrFail($request->id);

             $clinic->update([
                 'situation' => $request->situation, // Ou renomeie no banco para 'situacao'
                 'email' => $request->email,
                 'name' => $request->name,
                 'status' => $request->status,
                 'password' => $request->password ? bcrypt($request->password) : $clinic->password,
                 'document_number' => preg_replace('/[^0-9]/', '', $request->document_number),
             ]);

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Clínica atualizada com sucesso!');
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }
}
