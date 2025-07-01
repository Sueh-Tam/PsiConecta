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
        $query = \App\Models\Appointment::with(['psychologist', 'patient'])
        ->whereHas('psychologist', function($q) use ($clinicId) {
            $q->where('id_clinic', $clinicId);
        });
        

        if (request()->has('patient') && request('patient')) {
            $query->where('patient_id', request('patient'));
        }

        if (request()->has('psychologist') && request('psychologist')) {
            $query->where('psychologist_id', request('psychologist'));
        }

        if (request()->has('date') && request('date')) {
            $query->whereDate('dt_Availability', request('date'));
        }

        if (request()->has('status') && request('status')) {
            $query->where('status', request('status'));
        }
        $allAppointments = $query
            ->orderBy('dt_Availability', 'desc')
            ->orderBy('hr_Availability', 'asc')
            ->get();

        // dd($allAppointments->where('status', 'completed')
        // ->where('date', '>=', now()->subMonth())
        // ->count());

        $appointments = $query
            ->orderBy('dt_Availability', 'desc')
            ->orderBy('hr_Availability', 'asc')
            ->paginate(10)
            ->through(function($appointment) {
                $psychologist = $appointment->psychologist;
                $patient = $appointment->patient;
                
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->dt_Availability,
                    'start_time' => $appointment->hr_Availability,
                    'psychologist' => [
                        'name' => $psychologist->name,
                        'initials' => strtoupper(substr($psychologist->name, 0, 2))
                    ],
                    'patient' => [
                        'name' => $patient->name,
                        'initials' => strtoupper(substr($patient->name, 0, 2))
                    ],
                    'status' => $appointment->status,
                ];
            });
        
        // Obter consultas do dia atual
        $todayQuery = clone $query;
        $todayAppointments = $todayQuery
            ->whereDate('dt_Availability', now()->toDateString())
            ->orderBy('hr_Availability', 'asc')
            ->get()
            ->map(function($appointment) {
                $psychologist = $appointment->psychologist;
                $patient = $appointment->patient;
                
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->dt_Availability,
                    'start_time' => $appointment->hr_Availability,
                    'psychologist' => [
                        'name' => $psychologist->name,
                        'initials' => strtoupper(substr($psychologist->name, 0, 2))
                    ],
                    'patient' => [
                        'name' => $patient->name,
                        'initials' => strtoupper(substr($patient->name, 0, 2))
                    ],
                    'status' => $appointment->status,
                ];
            });

        $stats = [
            'next_appointment' => $appointments->where('status', 'scheduled')
                ->sortBy('date')
                ->first(),
            'completed_appointments' => $allAppointments->where('status', 'completed')
                ->where('date', '>=', now()->subMonth())
                ->count(),
            'pending_appointments' => $appointments->where('status', 'scheduled')->count()
        ];
        $psychologists = $clinic->psychologists()->get();
        $patients = $clinic->patients()->get();
        
        return view('Dashboard.clinic.index', compact('appointments', 'todayAppointments', 'stats', 'patients', 'psychologists'));
    }

    public function store(Request $request){
        $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'document_number' => 'required|unique:users,document_number|cnpj',
            ],[
                'document_number.cnpj' => 'O número do documento deve ser um CNPJ válido.',
                'document_number.unique' => 'O número do documento já está em uso.',
                'document_number.required' => 'O número do documento é obrigatório.',
                'email.unique' => 'O email já está em uso.',
                'email.required' => 'O email é obrigatório.',
                'password.required' => 'A senha é obrigatória.',
                'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
                'password.confirmed' => 'As senhas não conferem.',
                'name.required' => 'O nome é obrigatório.',
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
        
        
        // try {
            

        // } catch (\Throwable $th) {
        //     return redirect()->back()
        //     ->withErrors($th->getMessage())
        //     ->withInput();
        // }
    }


    public function clinics()
    {
        $clinics = User::where('type', 'clinic')->get();
        return view('Dashboard.admin.index', ['clinics' => $clinics]);
    }

    public function updateClinic(Request $request)
    {
        $request->validate([
                'id' => 'required|exists:users,id',
                'situation' => 'required|in:valid,invalid,pending', // Corrigido para match com o form
                'email' => ['required', 'email', Rule::unique('users')->ignore($request->id)],
                'name' => 'required',
                'password' => 'nullable|min:6', // Adicionado nullable
                'status' => 'required|in:active,inactive', // Adicionado required
                'document_number' => [
                    'required',
                    Rule::unique('users')->ignore($request->id)
                ],
            ],
        [
            'email.unique' => 'O email já está em uso.',
            'document_number.unique' => 'O número do documento já está em uso.',
            'document_number.required' => 'O número do documento é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'situation.required' => 'A situação é obrigatória.',
            'situation.in' => 'A situação deve ser válida.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser válido.',
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
    }
}
