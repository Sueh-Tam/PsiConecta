<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
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
            $validatedData = $request->validate([
                'patient_id' => 'required|integer|exists:users,id,type,patient',
                'psychologist_id' => 'required|integer|exists:users,id,type,psychologist',
                'total_appointments' => 'required|integer|min:1',
                'payment_method' => 'required|in:pix,cash',
            ]);

            $psychologist = User::where('id', $validatedData['psychologist_id'])
                                 ->where('type', 'psychologist')
                                 ->first();

            if (!$psychologist) {
                throw new \Exception('Psicólogo não encontrado.');
            }

            $patient = User::where('id', $validatedData['patient_id'])
                           ->where('type', 'patient')
                           ->first();

            if (!$patient) {
                throw new \Exception('Paciente não encontrado.');
            }

            $totalPrice = $psychologist->appointment_price * $validatedData['total_appointments'];

            // if ($patient->balance != $totalPrice) {
            //     throw new \Exception('O saldo do paciente não é suficiente para a compra do pacote.');
            // }

            // Criar o pacote
            $package = Package::create([
                'patient_id' => $validatedData['patient_id'],
                'psychologist_id' => $validatedData['psychologist_id'],
                'total_appointments' => $validatedData['total_appointments'],
                'price' => $totalPrice,
                'balance' => 0,
                'payment_method' => $validatedData['payment_method'],
            ]);

            // Criar as consultas baseadas no total_appointments
            for ($i = 0; $i < $validatedData['total_appointments']; $i++) {
                \App\Models\Appointment::create([
                    'clinic_id' => $psychologist->id_clinic,
                    'patient_id' => $validatedData['patient_id'],
                    'psychologist_id' => $validatedData['psychologist_id'],
                    'package_id' => $package->id,
                    'status' => 'scheduled',
                    'payment_status' => 'paid',
                    'medical_record' => null,
                ]);
            }

            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Pacote comprado com sucesso!');

        } catch (\Exception $th) {
            return redirect()->back()
                ->withErrors(['error' => $th->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        //
    }
}
