<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
    }


    public function create()
    {

    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'Por favor, informe um endereço de e-mail válido.',
                'email.unique' => 'Este e-mail já está sendo utilizado por outro usuário.',
            ]);
            
            if ($validated) {
                return redirect()->back()
                    ->withErrors($validated)
                    ->withInput();
            }
            $paciente = new User();
            $paciente->name = $request->name;
            $paciente->email = $request->email;
            $paciente->password = bcrypt($request->password);
            $paciente->document_type = $request->document_type;
            $paciente->document_number = $request->document_number;
            $paciente->type = 'patient';
            $paciente->status = 'active';
            $paciente->save();

        } catch (\Throwable $th) {
            return redirect()->back()
                         ->withErrors($th)
                         ->withInput();
        }
    }
    public function login(Request $request){
        try {
            $credential = $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6'
            ], [
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'Por favor, informe um endereço de e-mail válido.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve ter pelo menos 6 caracteres.'
            ]);
            $user = User::withTrashed()->where('email', $request->email)->first();
            if($user->isClinic() && $user->situation == 'invalid' || $user->status == 'inactive' || $user->situation == 'pending'){
                return back()->withErrors([
                    'acesso_rejeitado' => 'Não é possível acessar pois o cadastro da clínica está inativo ou pendente.',
                ]);
            }
            if($user->isPsychologist() || $user->isAttendant()){
                if($user->clinic->situation == 'invalid' || $user->clinic->status == 'inactive' || $user->situation == 'pending'){
                    return back()->withErrors([
                        'acesso_rejeitado' => 'Não é possível acessar pois a clínica está inativo ou pendente .',
                    ]);
                }
            }
            if ($user && $user->trashed()) {
                $user->restore();
                $user->save();
            }
            if(Auth::check()){
                if(Auth::user()->isPatient()){
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Usuário já está logado!')
                        ->with('success_redirect', route('patient.dashboard'));
                } elseif (Auth::user()->isClinic()) {
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Usuário já está logado!')
                        ->with('success_redirect', route('clinic.dashboard'));
                } elseif (Auth::user()->isAdmin()) {
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Usuário já está logado!')
                        ->with('success_redirect', route('admin.dashboard'));
                } elseif (Auth::user()->isAttendant()) {
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Usuário já está logado!')
                        ->with('success_redirect', route('clinic.dashboard'));
                }
            }
            if (Auth::attempt($credential)) {
                $request->session()->regenerate();
                if(Auth::user()->isPatient()){
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Usuário logado!')
                        ->with('success_redirect', route('patient.dashboard'));
                } elseif (Auth::user()->isClinic()) {
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Usuário logado!')
                        ->with('success_redirect', route('clinic.dashboard'));
                } elseif (Auth::user()->isAdmin()) {
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Usuário logado!')
                        ->with('success_redirect', route('admin.dashboard'));
                } elseif (Auth::user()->isAttendant()) {
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Usuário logado!')
                        ->with('success_redirect', route('clinic.dashboard'));
                } elseif (Auth::user()->isPsychologist()) {
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Usuário logado!')
                        ->with('success_redirect', route('psychologist.dashboard'));
                }
            } else {
                return back()->withErrors([
                    'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
                ])->onlyInput('email');
            }
        } catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }

    public function resetPassword(Request $request){
        try {
            $validated = $request->validate([
                'password' =>'required|min:6',
                'password_confirmation' =>'required|same:password',
                'email' =>'required|email',
                'cpf' =>'required|min:14|max:14'
            ],[
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve ter pelo menos 15 caracteres.',
                'password_confirmation.required' => 'O campo confirmação de senha é obrigatório.',
                'password_confirmation.same' => 'As senhas não coincidem.',
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'Por favor, informe um endereço de e-mail válido.',
                'cpf.required' => 'O campo CPF é obrigatório.',
                'cpf.min' => 'O CPF deve ter pelo menos 14 caracteres.',
                'cpf.max' => 'O CPF deve ter no máximo 14 caracteres.',
            ]);
            

            $cpf = preg_replace('/[^0-9]/', '', $validated['cpf']);
            
            $usuario = User::where('email', $request->email)->where('document_number', $cpf)->first();
            if ($usuario) {
                if($validated['password'] == $validated['password_confirmation']){
                    $usuario->password = bcrypt($validated['password']);
                    $usuario->save();
                    return redirect()->back()
                        ->with('show_success_modal', true)
                        ->with('success_message', 'Senha alterada com sucesso!')
                        ->with('success_redirect', route('home')); 
                }else{
                    return redirect()->back()
                        ->withErrors(['mensagem' => 'As senhas não coincidem.'])
                        ->withInput();
                }
                
            }else{
                return redirect()->back()
                ->withErrors(['mensagem' => 'Usuário não encontrado'])
                ->withInput();
            }
        }catch (\Throwable $th) {
            return redirect()->back()
                ->withErrors($th->getMessage())
                ->withInput();
        }
    }

    public function logout(Request $request):RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('show_success_modal', true)
            ->with('success_message', 'Logout feito com sucesso!')
            ->with('success_redirect', route('home'));

    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
