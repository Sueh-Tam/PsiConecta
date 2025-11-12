<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validação dos dados
            $validated = $request->validate([
                'patient_id' => 'required|exists:users,id',
                'psychologist_id' => 'required|exists:users,id',
                'day_of_week' => 'required',
                'time' => 'required'
            ]);

            $dataFormatada = Carbon::createFromFormat('d/m/Y', $request->day_of_week)->startOfDay()->toDateTimeString();            // Calcula a data usando o Carbon baseado no dia da semana

            // Verifica disponibilidade do psicólogo
            $availability = Availability::where('id_psychologist', $request->psychologist_id)
                        ->where('dt_Availability', $dataFormatada)
                        ->where('hr_Availability', $request->time)
                        ->where('status', 'available')
                        ->first();  

            if (!$availability) {
                if ($request->ajax()) {
                    return response()->json(['message' => 'Horário não está mais disponível'], 422);
                }
                return redirect()->back()
                    ->withErrors(['message' => 'Horário não está mais disponível'])
                    ->withInput();
            }

            // Busca o pacote ativo do paciente
            $package = Package::where('patient_id', $request->patient_id)
                            ->where('psychologist_id', $request->psychologist_id)
                            ->whereRaw('balance > 0')
                            ->orderBy('created_at', 'asc') // Ordena por id em ordem decrescente para obter o mais recente
                            ->first();

            if (!$package) {
                if ($request->ajax()) {
                    return response()->json(['message' => 'Não há pacote ativo para este paciente'], 422);
                }
                return redirect()->back()
                    ->withErrors(['message' => 'Não há pacote ativo para este paciente'])
                    ->withInput();
            }

            // Verifica se o pacote tem saldo disponível
            if (($package->balance - 1) <= 0) {
                if ($request->ajax()) {
                    return response()->json(['message' => 'É necessário renovar o pacote para agendar mais consultas'], 422);
                }
                return redirect()->back()
                    ->withErrors(['message' => 'É necessário renovar o pacote para agendar mais consultas'])
                    ->withInput();
            }

            $package->balance--;
            $package->save();
            // Cria uma nova consulta vinculada ao pacote
            $appointment = new Appointment();
            $appointment->clinic_id = $package->psychologist()->first()->id_clinic;
            $appointment->patient_id = $request->patient_id;
            $appointment->psychologist_id = $request->psychologist_id;
            $appointment->package_id = $package->id;
            $appointment->payment_status = 'paid';
            $appointment->dt_Availability = $availability->dt_Availability;
            $appointment->hr_Availability = explode('-',$availability->hr_Availability)[0];
            $appointment->status = 'scheduled';
            $appointment->save();

            $availability->status = 'unvailable';
            $availability->id_appointments = $appointment->id;
            $availability->save();

            if ($request->ajax()) {
                return response()->json(['message' => 'Consulta agendada com sucesso!']);
            }
            return redirect()->back()->with('success', 'Consulta agendada com sucesso!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Erro ao agendar consulta: ' . $e->getMessage()], 422);
            }
            return redirect()->back()
                ->withErrors(['message' => 'Erro ao agendar consulta: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        if($appointment->status == 'scheduled' || $appointment->status == 'completed'){
            $lastAppointments = Appointment::where('patient_id', $appointment->patient_id)
            ->where('status', 'completed')
            ->where('psychologist_id', $appointment->psychologist_id)
            ->where('clinic_id', $appointment->clinic_id)
            ->where('dt_Availability', '<=', $appointment->dt_Availability)
            ->get();

            return view('Dashboard.Consults.edit', compact('appointment','lastAppointments'));
        }else{
            return redirect()->back();
        }
        
    }

    public function finishAppointment(Appointment $appointment, Request $request){
        try {
            $appointment->medical_record = $request->medical_record;
            $appointment->status = 'completed';
            $appointment->save();
            return redirect()->back()->with(['show_success_modal' => true, 'message' => 'Prontuário salvo com sucesso!', 'title' => 'Consulta realizada com sucesso!']);
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
        
    }

    public function completAppointment(Appointment $appointment, $medical_record = ''){
        try {
            // Atualiza o status da consulta para concluída
            $appointment->status = 'completed';
            if(Auth::user()->isPsychologist()){
                $appointment->medical_record = $medical_record;
            }
            $appointment->save();
            return response()->json(['message' => 'Consulta realizada com sucesso!']);
        }catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar consulta: '. $e->getMessage()], 422);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        try {
            // Atualiza o status da consulta para cancelado
            $appointment->status = 'cancelled';
            $appointment->save();

            // Busca e atualiza a disponibilidade relacionada
            $availability = Availability::where('id_appointments', $appointment->id)
                                    ->first();
            
            if ($availability) {
                $availability->status = 'available';
                $availability->id_appointments = null;
                $availability->save();
            }

            return response()->json(['message' => 'Consulta cancelada com sucesso!']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cancelar consulta: ' . $e->getMessage()], 422);
        }
    }

    public function canceledEarly(Appointment $appointment){
        try {
            $appointment->status = 'canceled_early';
            $appointment->save();
            $package = Package::find($appointment->package_id);
            if ($package) {
                $package->balance--;
                $package->save();
            }
            $availability = Availability::where('id_appointments', $appointment->id)->first();

            if ($availability) {
                $availability->status = 'available';
                $availability->id_appointments = null;
                $availability->save();
            }

            return response()->json(['message' => 'Consulta cancelada com sucesso!']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cancelar consulta: ' . $e->getMessage()], 422);
        }
    }

    public function cancellByPatient(Appointment $appointment){
        try {
            $appointmentDateTime = Carbon::parse($appointment->dt_Availability)->setTimeFromTimeString($appointment->hr_Availability);          
            $now = Carbon::now();
            $hrLessToConult = $now->diffInHours($appointmentDateTime, false);
            
            if($hrLessToConult >= 24){
                $appointment->status = 'canceled_early';
                $package = Package::find($appointment->package_id);
                if ($package) {
                    $package->balance--;
                    $package->save();
                }
            }else{
                $appointment->status = 'canceled_late';
            }
            
            $appointment->save();
            
            $availability = Availability::where('id_appointments', $appointment->id)->first();

            if ($availability) {
                $availability->status = 'available';
                $availability->id_appointments = null;
                $availability->save();
            }

            return response()->json(['message' => 'Consulta cancelada com sucesso!']);

        }catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao cancelar consulta: '. $e->getMessage()], 422);
        }
    }

    public function ApiScheduleAppointment(Request $request){
        try{
            
            $package = Package::find($request->package_id);
            $psychologist = User::find($package->psychologist_id);
            if($package->balance <= 0){
                return response()->json(['message' => 'Pacote esgotado'], 422);
            }
            

            $availability = Availability::find($request->availability_id);

            if(!$availability){
                return response()->json(['message' => 'Horário indisponível'], 422);
            }
            if($availability->status != 'available'){
                return response()->json(['message' => 'Consulta não está disponível'], 422);
            }
            
            //return response()->json(['message' => $psychologist->name]);

            $appointment = new Appointment();
            $appointment->dt_Availability = $availability->dt_Availability;
            $appointment->hr_Availability = $availability->hr_Availability;
            $appointment->patient_id = $package->patient_id;
            $appointment->psychologist_id = $availability->id_psychologist;
            $appointment->clinic_id = $psychologist->id_clinic;
            $appointment->package_id = $package->id;
            $appointment->payment_status = 'paid';
            $appointment->status = 'scheduled';
            $appointment->save();
            
            $availability->id_appointments = $appointment->id;
            $availability->status = 'unvailable';
            $availability->save();

            $package->balance--;
            $package->save();
            return response()->json(['message' => 'Consulta agendada com sucesso!']);
        }catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao agendar consulta: '. $e->getMessage()], 422);
        }
    }

    public function ApiAllAppointments(Request $request){
        $user = User::find($request->user_id);
        $now = Carbon::now();
        //$appointments = $user->patientAppointments()->where('dt_Availability','>=', $now)->get();
                $appointments = $user->patientAppointments()->get();

        return response()->json($appointments);
    }

    public function ApiCancelAppointment(Request $request){
        $appointment = Appointment::find($request->appointment_id);
        $availability = Availability::where('id_appointments',$appointment->id)->first();

        if(!$availability){
            return response()->json(['message' => 'Disponibilidade não encontrada'], 422);
        }
        $package = Package::find($appointment->package_id);
        if(!$package){
            return response()->json(['message' => 'Pacote não encontrado'], 422);
        }
        if(!$appointment){
            return response()->json(['message' => 'Consulta não encontrada'], 422);
        }
        
        if($availability){
            $availability->status = 'available';
            $availability->id_appointments = null;
            $availability->save();
            
            $package->balance++;
            $package->save();
        }
        $appointment->status = 'canceled_early';
        $appointment->save();
        return response()->json(['message' => 'Consulta cancelada com sucesso!']);
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
