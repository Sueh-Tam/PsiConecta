<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\ClinicPatient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function dashboard(Request $request){
        $query = Auth::user()->patientAppointments();

        // Aplicar filtro de psicólogo
        if ($request->has('psicologo') && $request->psicologo) {
            $query->where('psychologist_id', $request->psicologo);
        }

        // Aplicar filtro de data
        //dd($request->date);
        if ($request->has('date') && $request->date) {            
            $query->whereDate('dt_Availability', $request->date);
        }

        // Aplicar filtro de status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        $todayAppointments = $query->whereDate('dt_Availability', now()->format('Y-m-d'))->orderBy('hr_Availability', 'asc')->get();
        $appointments = $query
        ->orderBy('dt_Availability', 'desc')
        ->orderBy('hr_Availability', 'asc')
        ->paginate(10);
        
        $psychologists = Auth::user()->patientAppointments()
            ->with('psychologist')
            ->select('psychologist_id')
            ->distinct()
            ->get()
            ->pluck('psychologist');

        $packages = Auth::user()->patientPackages()->whereRaw('balance != 0')
            ->orderBy('created_at', 'desc')
            ->get();
        $stats = [
            'next_appointment' => $appointments->where('status', 'scheduled')
                ->sortBy('hr_Availability')
                ->first(),
            'completed_appointments' => $appointments->where('status', 'completed')
                ->where('date', '>=', now()->subMonth())
                ->count(),
            'pending_appointments' => $appointments->where('status', 'scheduled')->count()
        ];

        return view('Dashboard.Consults.index', compact('appointments', 'stats', 'psychologists','packages','todayAppointments'));
    }

    public function store(Request $request)
    {
        $request->merge([
                'document_number' => preg_replace('/[^0-9]/', '', $request->document_number)
            ]);
            
            $request->validate([
                'name' => ['required'],
                'email' => ['required','email','unique:users'],
                'password' => ['required','min:6','confirmed'],
                'document_number' => ['required','unique:users,document_number','cpf'],
                'birth_date' => ['required','date','before_or_equal:'.now()->subYears(18)->format('Y-m-d')]
            ],
            [
                'name.required' => 'O nome é obrigatório',
                'email.required' => 'O email é obrigatório',
                'email.email' => 'Digite um email válido',
                'email.unique' => 'Este email já está em uso',
                'password.required' => 'A senha é obrigatória',
                'password.min' => 'A senha deve ter pelo menos 6 caracteres',
                'document_number.required' => 'O número do documento é obrigatório',
                'document_number.unique' => 'Este número de documento já está em uso',
                'birth_date.required' => 'A data de nascimento é obrigatória',
                'birth_date.date' => 'Digite uma data válida',
                'birth_date.before_or_equal' => 'Você deve ter pelo menos 18 anos',
                'document_number.cpf' => 'Digite um CPF válido',
                'password.confirmed' => 'A confirmação da senha não corresponde'
            ]);
            // if (!$validated) {
            //       return redirect()->back()
            //           ->withErrors($validated)
            //           ->withInput();
            //   }

            $paciente = new User();
            $paciente->name = $request->name;
            $paciente->email = $request->email;
            $paciente->password = bcrypt($request->password);
            $paciente->birth_date = $request->birth_date;
            $paciente->document_type = 'cpf';
            $paciente->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $paciente->type = 'patient';
            $paciente->status = 'active';
            $paciente->save();
            $clinicPaciente = new ClinicPatient();

            if(Auth::check() && Auth::user()->isAttendant()){
                $clinicPaciente->id_clinic = Auth::user()->id_clinic;
                $paciente->id_clinic = Auth::user()->id_clinic;
                $clinicPaciente->id_patient = $paciente->id;
                return redirect()->back()
                ->with('show_success_modal', true)
                    ->with('success_message', 'Paciente cadastrado com sucesso!');
            }

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Paciente cadastrado com sucesso!')
                ->with('success_redirect', route('home'));

    }



    public function update(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'document_number' => 'required|unique:users,document_number',
            'password' => 'nullable|min:6|confirmed',
            'birth_date' => [
                'required',
                'date',
                'before:' . Carbon::today()->subYears(18)->toDateString(),
            ]
        ], [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Por favor, insira um endereço de email válido.',
            'document_number.required' => 'O campo número do documento é obrigatório.',
            'document_number.unique' => 'Este número de documento já está sendo usado.',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
            'birth_date.required' => 'O campo data de nascimento é obrigatório.',
            'birth_date.date' => 'O campo data de nascimento deve ser uma data válida.',
            'birth_date.before' => 'Você deve ter pelo menos 18 anos.',
        ]);
        $paciente = User::find(Auth::user()->id);

        if($paciente){
            $paciente->name = $request->name;
            $paciente->email = $request->email;
            $paciente->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $paciente->birth_date = $request->birth_date;
            $paciente->save();
            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Paciente atualizado com sucesso!')
                ->with('success_redirect', route('patient.profile'));
        }else{
            return redirect()->back()
                ->withErrors(['mensagem' => 'Não foi possível atualizar o seu perfil'])
                ->withInput();
        }
    }
    public function delete(){
        $paciente = User::find(Auth::user()->id);
        if($paciente){
            $paciente->delete();
            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Conta inativada com sucesso!')
                ->with('success_redirect', route('home'));
        }else{
            return redirect()->back()
                ->withErrors(['mensagem' => 'Não foi possível inativar o seu perfil'])
                ->withInput();
        }

    }
 
    public function patientByClinic(Request $request)
    {
        $clinicId = Auth::user()->id_clinic;
        $clinic = User::find($clinicId);
        if($request->searchPatient){
            $patients = User::where('document_number', '=', $request->searchPatient)
            ->where('type','patient')
            ->paginate(10);
        }else{
            $patients = $clinic->patients()->paginate(10);
        }

        $psychologists = User::where('id_clinic', $clinicId)
            ->where('type', 'psychologist')
            ->whereHas('availabilities', function($query) {
                $query->where('status', 'available');
            })
            ->get();

        return view('Dashboard.clinic.patient.index')->with([
            'patients' => $patients,
            'psychologists' => $psychologists,
        ]);
    }
}
