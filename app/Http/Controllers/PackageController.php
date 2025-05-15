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
    public function packagesByPatient(Request $request){
        // Obtém os pacotes ativos do paciente (onde ainda há consultas disponíveis)
        $packages = Auth::user()->patientPackages()
            ->with(['appointments' => function($query) {
                $query->whereIn('status', ['scheduled', 'completed']);
            }])
            ->paginate(10);
        
        $stats = [
            'total_packages' => $packages->count(),
            'total_available_appointments' => $packages->sum(function($package) {
                // Calcula consultas disponíveis subtraindo as já utilizadas do total
                return $package->total_appointments - $package->balance;
            }),
            'total_investment' => $packages->sum('price')
        ];
        $clinics = User::where('type', 'clinic')
        ->where('situation','valid')->where('status','active')->get();
        return view('Dashboard.packages.index', compact('packages', 'stats','clinics'));
    }
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

            // Verifica se existe um pacote anterior com o mesmo psicólogo
            $lastPackage = Package::where('patient_id', $validatedData['patient_id'])
                                 ->where('psychologist_id', $validatedData['psychologist_id'])
                                 ->latest()
                                 ->first();

            if ($lastPackage) {
                // Verifica se o pacote anterior está quitado
                if ($lastPackage->total_appointments != $lastPackage->balance) {
                    // Verifica se o novo psicólogo é da mesma clínica
                    $lastPsychologist = User::find($lastPackage->psychologist_id);
                    if ($lastPsychologist->clinic_id == $psychologist->clinic_id) {
                        throw new \Exception('Você possui um pacote em andamento com um psicólogo desta clínica. Complete todas as consultas do pacote atual antes de comprar um novo.');
                    }
                }
            }

            $totalPrice = $psychologist->appointment_price * $validatedData['total_appointments'];

            $package = Package::create([
                'patient_id' => $validatedData['patient_id'],
                'psychologist_id' => $validatedData['psychologist_id'],
                'total_appointments' => $validatedData['total_appointments'],
                'price' => $totalPrice,
                'balance' => 0,
                'payment_method' => $validatedData['payment_method'],
            ]);
            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Pacote comprado com sucesso!');

        } catch (\Exception $th) {
            return redirect()->back()
                ->withErrors(['error' => $th->getMessage(),'error_message' => 'Erro ao comprar pacote, por favor, tente novamente mais tarde.'])
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
