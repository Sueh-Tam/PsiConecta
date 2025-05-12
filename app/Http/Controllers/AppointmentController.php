<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Avaliability;
use App\Models\Package;
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
            $availability = Avaliability::where('id_psychologist', $request->psychologist_id)
                        ->where('dt_avaliability', $dataFormatada)
                        ->where('hr_avaliability', $request->time)
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
            if ($package->balance + 1 > $package->total_appointments) {
                if ($request->ajax()) {
                    return response()->json(['message' => 'É necessário renovar o pacote para agendar mais consultas'], 422);
                }
                return redirect()->back()
                    ->withErrors(['message' => 'É necessário renovar o pacote para agendar mais consultas'])
                    ->withInput();
            }

            $package->balance++;
            $package->save();
            // Cria uma nova consulta vinculada ao pacote
            $appointment = new Appointment();
            $appointment->clinic_id = Auth::user()->id_clinic;
            $appointment->patient_id = $request->patient_id;
            $appointment->psychologist_id = $request->psychologist_id;
            $appointment->package_id = $package->id;
            $appointment->dt_appointment = $availability->dt_avaliability;
            $appointment->hr_appointment = $availability->hr_avaliability;
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
        //
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
            $availability = Avaliability::where('id_appointments', $appointment->id)
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
