<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    //
    public function store(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'document_number' => 'required|unique:users,document_number',
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

        } catch (\Throwable $th) {
            return redirect()->back()
            ->withErrors($th->getMessage())
            ->withInput();
        }
    }
    public function login(Request $request){

        $credential = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'type' => 'clinic'
        ]);
        $user = User::withTrashed()->where('email', $request->email)->first();
        if ($user && $user->trashed()) {
            $user->restore();
            $user->save();
        }
        if(Auth::check()){
            return redirect()->route('home');
        }else{
            return redirect()->back()
                ->withErrors(['mensagem' => 'Acesso não autorizado'])
                ->withInput();
        }

    }
    public function clinics()
    {
        $clinics = User::where('type', 'clinic')->get();
        return view('Dashboard.admin.index', ['clinics' => $clinics]);
    }
}
