<?php

namespace App\Http\Controllers\Psychologist;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PsychologistController extends Controller
{
    public function store(Request $request){
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'document_number' => 'required|unique:users,document_number',
                'appointment_price' => 'required|numeric',
                'status' => 'required|in:active,inactive',
            ]);
            $psychologist = new User();
            $psychologist->id_clinic = Auth::user()->id;
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

        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }


    }

    public function update(Request $request){
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'document_number' => 'required|unique:users,document_number,'.Auth::user()->id,
                'appointment_price' => 'required|numeric',
                'password' => 'nullable|min:6|confirmed',
            ]);
            $psychologist = User::find(Auth::user()->id);
            $psychologist->name = $request->name;
            $psychologist->email = $request->email;
            $psychologist->document_number = preg_replace('/[^0-9]/', '',$request->document_number);
            $psychologist->status = $request->status;
            $psychologist->appointment_price = $request->appointment_price;
            $psychologist->document_type = 'crp';
            $psychologist->status = 'active';
            if($request->password){
                $psychologist->password = bcrypt($request->password);
            }
            $psychologist->save();

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Psicólogo atualizado com sucesso!!');

        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
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
                $patient->appointments_left = $activePackage 
                    ? $activePackage->total_appointments - $activePackage->balance
                    : 0;
                return $patient;
            });

        $query = \App\Models\Appointment::with(['psychologist', 'patient'])
            ->whereHas('psychologist', function($q) use ($clinicId) {
                $q->where('id_clinic', $clinicId);
            })
            ->where('psychologist_id', Auth::user()->id);

        if ($request->has('date') && $request->date) {
            $query->whereDate('dt_avaliability', $request->date);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query
            ->orderBy('dt_avaliability', 'asc')
            ->orderBy('hr_avaliability', 'asc')
            ->paginate(10)
            ->through(function($appointment) {
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->dt_avaliability,
                    'start_time' => $appointment->hr_avaliability,
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
        return view('Dashboard.Psychologists.consults', ['appointments' => $appointments,'stats' => $stats, 'patients' => $patients, 'today' => $today]);
    }

}
