<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
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
    public function login(Request $request):RedirectResponse{

        $credential = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'type' => 'patient'
        ]);
        $user = User::withTrashed()->where('email', $request->email)->first();
        if ($user && $user->trashed()) {
            $user->restore();
            $user->save();
        }
        if(Auth::check()){
            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Usuário já está logado!')
                ->with('success_redirect', route('home'));
        }
        if (Auth::attempt($credential)) {
            $request->session()->regenerate();
            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Login feito com sucesso!')
                ->with('success_redirect', route('home'));
        } else {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
    }
    public function logout(Request $request):RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back()
            ->with('show_success_modal', true)
            ->with('success_message', 'Logout feito com sucesso!')
            ->with('success_redirect', route('home'));
    }

    public function update(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'document_number' => 'required|unique:users,document_number',
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
}
