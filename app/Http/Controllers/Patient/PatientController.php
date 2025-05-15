<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
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
            $query->whereDate('dt_avaliability', $request->date);
        }

        // Aplicar filtro de status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query
        ->orderBy('dt_avaliability', 'asc')
        ->orderBy('hr_avaliability', 'asc')
        ->paginate(10);
        
        $psychologists = Auth::user()->patientAppointments()
            ->with('psychologist')
            ->select('psychologist_id')
            ->distinct()
            ->get()
            ->pluck('psychologist');
        
        $stats = [
            'next_appointment' => $appointments->where('status', 'scheduled')
                ->sortBy('date')
                ->first(),
            'completed_appointments' => $appointments->where('status', 'completed')
                ->where('date', '>=', now()->subMonth())
                ->count(),
            'pending_appointments' => $appointments->where('status', 'scheduled')->count()
        ];

        return view('Dashboard.Consults.index', compact('appointments', 'stats', 'psychologists'));
    }

    public function store(Request $request)
    {
        try {
            $request->merge([
                'document_number' => preg_replace('/[^0-9]/', '', $request->document_number)
            ]);
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'document_type' => 'required|in:cpf,rg',
                'document_number' => 'required|unique:users,document_number',
            ]);


            $paciente = new User();
            if(Auth::check() && Auth::user()->isAttendant()){
                $paciente->id_clinic = Auth::user()->id_clinic;
            }
            $paciente->name = $request->name;
            $paciente->email = $request->email;
            $paciente->password = bcrypt($request->password);
            $paciente->document_type = strtolower($request->document_type);
            $paciente->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $paciente->type = 'patient';
            $paciente->status = 'active';
            $paciente->save();
            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Paciente cadastrado com sucesso!')
                ->with('success_redirect', route('home'));
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }

    }



    public function update(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'document_number' => 'required|unique:users,document_number',
            'password' => 'nullable|min:6|confirmed',
        ]);
        $paciente = User::find(Auth::user()->id);

        if($paciente){
            $paciente->name = $request->name;
            $paciente->email = $request->email;
            $paciente->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
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
 
    public function patientByClinic()
    {
        $clinicId = Auth::user()->id_clinic;
        $clinic = User::find($clinicId);
        $patients = $clinic->patients()->paginate(10);

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
