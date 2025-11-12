<?php

namespace App\Http\Controllers;

use App\Models\ClinicPatient;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
    }


    public function packagesByPatient(Request $request){

        $packages = Auth::user()->patientPackages()
            ->with(['appointments' => function($query) {
                $query->whereIn('status', ['scheduled', 'completed']);
            }])
            ->paginate(10);
        
        $stats = [
            'total_packages' => $packages->count(),
            'total_available_appointments' => $packages->sum(function($package) {

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
                'payment_method' => 'required|in:pix,cash,health_plan',
            ], [
                'patient_id.required' => 'O campo paciente é obrigatório.',
                'patient_id.integer' => 'O campo paciente deve ser um número inteiro.',
                'patient_id.exists' => 'O paciente selecionado não existe ou não é válido.',
                'psychologist_id.required' => 'O campo psicólogo é obrigatório.',
                'psychologist_id.integer' => 'O campo psicólogo deve ser um número inteiro.',
                'psychologist_id.exists' => 'O psicólogo selecionado não existe ou não é válido.',
                'total_appointments.required' => 'O número total de consultas é obrigatório.',
                'total_appointments.integer' => 'O número total de consultas deve ser um número inteiro.',
                'total_appointments.min' => 'O número total de consultas deve ser pelo menos 1.',
                'payment_method.required' => 'O método de pagamento é obrigatório.',
                'payment_method.in' => 'O método de pagamento selecionado não é válido. Escolha entre pix, dinheiro ou plano de saúde.',
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
            $ClinicPatient = ClinicPatient::where('id_patient', $validatedData['patient_id'])
                ->where('id_clinic',$psychologist->id_clinic)
                ->first();
                
            if (!$ClinicPatient) {
                ClinicPatient::create([
                    'id_patient' => $validatedData['patient_id'],
                    'id_clinic' => $psychologist->id_clinic,
                ]);
            }

            $lastPackage = Package::where('patient_id', $validatedData['patient_id'])
                                 ->where('psychologist_id', $validatedData['psychologist_id'])
                                 ->latest()
                                 ->first();

            if ($lastPackage) {

                if ($lastPackage->total_appointments != $lastPackage->balance) {

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
                'balance' => $validatedData['total_appointments'],
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

    public function getPackages(Request $request){
        $patient = User::where('id',$request->user_id)->first();

        return response()->json($patient->patientPackages()->with(['psychologist','patient'])->get());
    }

    public function getPackage(Request $request){
        
        $package = Package::where('id',$request->id)->with(['psychologist','patient','appointments'])->first();
        return response()->json($package);
    }

    public function buyPackage(Request $request){
        
        $package = Package::where('patient_id',$request->patient_id)->where('psychologist_id',$request->psychologist_id)->where('balance','>',0)->first();
        if($package){
            return response()->json('Você já possui um pacote comprado com esse psicólogo, por favor, complete as consultas do pacote atual antes de comprar um novo.', 500);
        }
        if($request->total_appointments == 0){
            return response()->json('Número de consultas inválido', 500);
        }

        $psychologist = User::where('id',$request->psychologist_id)->where('type','psychologist')->first();
        if(!$psychologist){
            return response()->json('Psicólogo não encontrado', 500);
        }

        $package = new Package();
        $package->patient_id = $request->patient_id;
        $package->psychologist_id = $request->psychologist_id;
        $package->total_appointments = $request->total_appointments;
        $package->price = ($psychologist->appointment_price * $request->total_appointments);
        $package->balance = $request->total_appointments;
        $package->payment_method = $request->payment_method;
        $package->save();

        return response()->json('Pacote comprado com sucesso', 200);    
    }

    public function ApiActivePackages($id){
        $user = User::where('id',$id)->first();
        $packages = $user->activePackage();
        if($packages->count() == 0){
            return response()->json(['error' => 'Nenhum pacote ativo encontrado'], 404);
        }
        return response()->json($packages);
    }

    public function show(Package $package)
    {
    }

    public function edit(Package $package)
    {
    }

    public function update(Request $request, Package $package)
    {
    }

    public function destroy(Package $package)
    {
    }
}
