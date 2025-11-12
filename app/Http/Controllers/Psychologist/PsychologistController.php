<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PsychologistController extends Controller
{
    public function store(Request $request){
        $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'document_number' => 'required|unique:users,document_number',
                'appointment_price' => 'required|numeric',
                'status' => 'required|in:active,inactive',
            ],
        [
            'document_number.unique' => 'O número do documento já está em uso.',
            'appointment_price.numeric' => 'O preço do atendimento deve ser um número válido.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser active ou inactive.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser válido.',
            'email.unique' => 'O email já está em uso.',
            'name.required' => 'O nome é obrigatório.',
            'document_number.required' => 'O número do documento é obrigatório.',
            'appointment_price.required' => 'O preço do atendimento é obrigatório.',
        ]);
            $psychologist = new User();
            $psychologist->id_clinic = Auth::user()->isClinic() ? Auth::user()->id : Auth::user()->id_clinic;
            $psychologist->name = $request->name;
            $psychologist->email = $request->email;
            $psychologist->password = bcrypt($request->password);
            $psychologist->document_type = 'crp';
            $psychologist->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $psychologist->type = 'psychologist';
            $psychologist->status = $request->status;
            $psychologist->appointment_price = $request->appointment_price;
            $psychologist->save();

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Psicólogo cadastrado com sucesso!!');


    }

    public function update(Request $request){
        $request->validate([
                'name' => 'required',
                'email' => ['required', 'email', Rule::unique('users')->ignore($request->id)],
                'document_number' => ['required',Rule::unique('users')->ignore($request->id)],
                'appointment_price' => 'required|numeric',
                'password' => 'nullable|min:6|confirmed',
            ],
        [
            'document_number.unique' => 'O número do documento já está em uso.',
            'appointment_price.numeric' => 'O preço do atendimento deve ser um número válido.',
            'password.confirmed' => 'As senhas não coincidem.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.required' => 'A senha é obrigatória.',

        ]);
            if(Auth::user()->isPsychologist()){
                $idUser = Auth::user()->id;
            }else{
                $idUser = $request->id;
            }
            $psychologist = User::find($idUser);
            $psychologist->name = $request->name;
            $psychologist->email = $request->email;
            $psychologist->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $psychologist->status = $request->status;
            $psychologist->appointment_price = $request->appointment_price;
            $psychologist->document_type = 'crp';
            $psychologist->status = $request->status;
            if($request->password){
                $psychologist->password = bcrypt($request->password);
            }
            $psychologist->save();

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Psicólogo atualizado com sucesso!!');
    }

    public function psychologistByClinic(Request $request){

        $user = Auth::user();
        $psychologists = $user
            ->psychologists()
            ->paginate(10);
        return view('Dashboard.clinic.psychologist.index', ['psychologists' => $psychologists]);

    }
    public function consultsByPsychologist(Request $request){
        
        $today = Carbon::today();
        $clinicId = Auth::user()->id_clinic;
        $clinic = User::find($clinicId);
        $patients = $clinic->patients()
            ->with(['patientPackages' => function($query) {
                $query->latest();
            }])
            ->get()
            ->map(function($patient) {
                $activePackage = $patient->activePackage();
                // $patient->appointments_left = $activePackage 
                //     ? $activePackage->total_appointments - $activePackage->balance
                //     : 0;
                return $patient;
            });

        $query = \App\Models\Appointment::with(['psychologist', 'patient'])
            ->whereHas('psychologist', function($q) use ($clinicId) {
                $q->where('id_clinic', $clinicId);
            })
            ->where('psychologist_id', Auth::user()->id);

        if ($request->has('date') && $request->date) {
            $query->whereDate('dt_Availability', $request->date);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        $todayAppointments = $query
            ->whereDate('dt_Availability', $today)
            ->get();

        $psychologistAppointments = Auth::user()->psychologistAppointments()->where('dt_Availability','>=',$today);
        $appointments = $psychologistAppointments
            ->orderBy('dt_Availability', 'asc')
            ->orderBy('hr_Availability', 'asc')
            ->paginate(10)
            ->through(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->dt_Availability,
                    'start_time' => $appointment->hr_Availability,
                    'psychologist' => [
                        'name' => $appointment->psychologist->name,
                        'initials' => substr($appointment->psychologist->name, 0, 2)
                    ],
                    'patient' => [
                        'name' => $appointment->patient->name,
                        'initials' => substr($appointment->patient->name, 0, 2)
                    ],
                    'status' => $appointment->status
                ];
            });
        $stats = [
            'next_appointment' => $appointments->where('status', 'scheduled')
                ->sortBy('date')
                ->first(),
            'completed_appointments' => $appointments->where('status', 'completed')
                ->where('date', '>=', now()->subMonth())
                ->count(),
            'pending_appointments' => $appointments->where('status', 'scheduled')->count()
        ];
        return view('Dashboard.Psychologists.consults', ['appointments' => $appointments,'stats' => $stats, 'patients' => $patients, 'today' => $today, 'todayAppointments' => $todayAppointments]);
    }

    public function psychologistPatients(Request $request){

        if($request->searchPatient){

        }
        $packages = \App\Models\Package::where('psychologist_id', Auth::user()->id)
            ->distinct('patient_id')
            ->get();
        $patients = [];

        foreach($packages as $package){
            if($request->searchPatient){
                if($package->patient->document_number == $request->searchPatient){
                    $patients[] = $package->patient;
                }
            }else{
               $patients[] = $package->patient; 
            }
            
        }
        return view('Dashboard.Psychologists.patients')->with('patients', $patients);
    }

    public function patientDetails($id){
        $patient = \App\Models\User::find($id);
        $appointments = \App\Models\Appointment::where('patient_id', $id)
            ->where('psychologist_id', Auth::user()->id)
            ->where('status', 'completed')
            ->orderBy('dt_Availability', 'desc')
            ->get();
        return view('Dashboard.Psychologists.patient_details', ['patient' => $patient, 'appointments' => $appointments]);
    }

    public function getPsychologistAvailabilities($id,$idPsychologist){
        try{
            $psychologist = User::find($idPsychologist);

            if(!$psychologist){
                return response()->json(['error' => 'Psicólogo não encontrado'], 404);
            }
            if($psychologist->availabilities()->count() == 0 || $psychologist->availabilities() == null){
                return response()->json(['error' => 'O psicólogo não possui disponibilidade, no momento'], 404);
            }
            $availabilities = $psychologist->availabilities()->where('status','available')->get();
            return response()->json($availabilities);

        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function apiGetPsychologistAvailabilities($id){
        try{
            $psychologist = User::find($id);
            if(!$psychologist){
                return response()->json(['error' => 'Psicólogo não encontrado'], 404);
            }
            $availabilities = $psychologist->availabilities()->where('status','available')->where('dt_Availability','>',now())->orderBy('dt_Availability', 'asc')->get();
            if($availabilities->count() == 0 || $availabilities == null){
                return response()->json(['error' => 'O psicólogo não possui disponibilidade, no momento'], 404);
            }
            return response()->json($availabilities);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

}
